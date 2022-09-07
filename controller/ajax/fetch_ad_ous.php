<?php
require_once __DIR__ .'/../../vendor/autoload.php';

$db = Database::get();
$ld = new MyLDAP();

$base = "ou=TainanLocalUser, dc=tainan, dc=gov, dc=tw";
$ou = "TainanLocalUser";
$description = "臺南市政府使用者AD帳號";
$parent_ou = "NULL";
$level = 0;
$ous = $ld->getAllOUsByRecursion($base, $ou, $description, $parent_ou, $level);

$key_array = array(
    array('input' => 'ou', 'output' => 'id', 'default_value' => 0),
    array('input' => 'parent_ou', 'output' => 'parent_id', 'default_value' => 0),
    array('input' => 'description', 'output' => 'name', 'default_value' => NULL),
    array('input' => 'enabled', 'output' => 'enabled', 'default_value' => 1),
    array('input' => 'level', 'output' => 'level', 'default_value' => 0),
);

$count = 0;
echo count($ous) . " entries returned" . PHP_EOL;

if (empty($ous)) {
    echo "No target-data" . PHP_EOL;
    $status = 400;
} else {
    $table = "ad_ous";
    $key_column = "1";
    $id = "1"; 
    $db->delete($table, $key_column, $id); 

    foreach($ous as $ou) {
        $entry = array();

        foreach($key_array as $key) {
            $entry[$key['output']] = empty($ou[$key['input']]) ? $key['default_value'] : $ou[$key['input']];
        }

        if (preg_match('/\((.*)\)$/', trim($entry['name']))) {
            $entry['enabled'] = 0;
        }

        $db->insert($table, $entry);
        $count = $count + 1;
    }

    $nowTime = date("Y-m-d H:i:s");
    echo "The " . $count . " records have been inserted or updated into the ad_ous on " . $nowTime . PHP_EOL;
    $status = 200;
}

$error = $db->getErrorMessageArray();
if(!empty($error)) {
	return;
}

$table = "apis"; // 設定你想查詢資料的資料表
$condition = "class LIKE :class and name LIKE :name";
$apis = $db->query($table, $condition, $order_by = "1", $fields = "*", $limit = "", [':class'=>'ad', ':name'=>'組織單位清單']);
$table = "api_status"; // 設定你想新增資料的資料表
$data_array = array();
$data_array['api_id'] = $apis[0]['id'];
$data_array['url'] = "";
$data_array['status'] = $status;
$data_array['data_number'] = $count;
$data_array['updated_at'] = $nowTime;
$db->insert($table, $data_array);
