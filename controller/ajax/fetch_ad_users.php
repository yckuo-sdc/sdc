<?php
require_once __DIR__ .'/../../vendor/autoload.php';

$db = Database::get();
$ld = new MyLDAP();

$user_base = "ou=TainanLocalUser, dc=tainan, dc=gov, dc=tw";
$user_ou = "TainanLocalUser";
$user_description = "臺南市政府使用者AD帳號";
$users = $ld->getAllUsersByRecursion($user_base, $user_ou, $user_description);

$count = 0;
echo count($users) . " entries returned" . PHP_EOL;

if (empty($users)) {
    echo "No target-data" . PHP_EOL;
    $status = 400;
} else {
    $table = "ad_users";
    $key_column = "1";
    $id = "1"; 
    $db->delete($table, $key_column, $id); 

    foreach($users as $user) {
        $sections = explode(",", $user['distinguishedname']);
        if(substr_compare($sections[1], "OU", 0, 2) == 0) {
            $user['belong_to_ou'] = substr($sections[1], 3);  
        }
        $db->insert($table, $user);
        $count = $count + 1;
    }

    $nowTime = date("Y-m-d H:i:s");
    echo "The " . $count . " records have been inserted or updated into the ad_users on " . $nowTime . PHP_EOL;
    $status = 200;
}

$error = $db->getErrorMessageArray();
if(!empty($error)) {
	return;
}

$table = "apis"; // 設定你想查詢資料的資料表
$condition = "class LIKE :class and name LIKE :name";
$apis = $db->query($table, $condition, $order_by = "1", $fields = "*", $limit = "", [':class'=>'ad', ':name'=>'使用者清單']);
$table = "api_status"; // 設定你想新增資料的資料表
$data_array = array();
$data_array['api_id'] = $apis[0]['id'];
$data_array['url'] = "";
$data_array['status'] = $status;
$data_array['data_number'] = $count;
$data_array['updated_at'] = $nowTime;
$db->insert($table, $data_array);
