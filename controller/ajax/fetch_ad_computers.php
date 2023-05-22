<?php
require_once __DIR__ .'/../../vendor/autoload.php';

$db = Database::get();
$ld = new MyLDAP();

$computer_base = "ou=TainanComputer, dc=tainan, dc=gov, dc=tw";
$computer_ou = "TainanComputer";
$computer_description = "臺南市政府公務個人電腦";
$computers = $ld->getAllComputersByRecursion($computer_base, $computer_ou, $computer_description);

$unsigned_computer_base = "cn=Computers, dc=tainan, dc=gov, dc=tw";
$unsigned_computer_ou = "Computers";
$unsigned_computer_description = "網域新增電腦帳戶的預設容器";
$unsigned_computers = $ld->getAllComputersByRecursion($unsigned_computer_base, $unsigned_computer_ou, $unsigned_computer_description);

$computers = array_merge($computers, $unsigned_computers);

$count = 0;
echo count($computers) . " entries returned" . PHP_EOL;

if (empty($computers)) {
    echo "No target-data" . PHP_EOL;
    $status = 400;
} else {
    $table = "ad_computers";
    $key_column = "1";
    $id = "1"; 
    $db->delete($table, $key_column, $id); 

    foreach($computers as $computer) {

        $computer['orgname'] = $ld->getSingleOUDescription($computer_base, $computer['distinguishedname']);

        if (!empty($computer['dnshostname'])) {
            $output = shell_exec("/usr/bin/dig +short " . $computer['dnshostname']);
            $ips = explode(PHP_EOL, $output);
            $size = sizeof($ips);
        }

        if (!isset($size) || $size == 1) {
            $computer['ip']= "";
            $db->insert($table, $computer);
            $count = $count + 1;
        } else {
            for($j=0; $j<$size-1; $j++) {
                $computer['ip']= $ips[$j];
                $db->insert($table, $computer);
                $count = $count + 1;
            }
        }
    }

    $nowTime = date("Y-m-d H:i:s");
    echo "The " . $count . " records have been inserted or updated into the ad_computers on " . $nowTime . PHP_EOL;
    $status = 200;
}

$error = $db->getErrorMessageArray();
if(!empty($error)) {
	return;
}

$table = "apis"; // 設定你想查詢資料的資料表
$condition = "class LIKE :class and name LIKE :name";
$apis = $db->query($table, $condition, $order_by = "1", $fields = "*", $limit = "", [':class'=>'ad', ':name'=>'電腦清單']);
$table = "api_status"; // 設定你想新增資料的資料表
$data_array = array();
$data_array['api_id'] = $apis[0]['id'];
$data_array['url'] = "";
$data_array['status'] = $status;
$data_array['data_number'] = $count;
$data_array['updated_at'] = $nowTime;
$db->insert($table, $data_array);
