<?php 
require_once __DIR__ . '/../../vendor/autoload.php';

$db = Database::get();
$cs = new CrowdStrikeAPIAdapter(); 

$data_array = array();
$data_array['filter'] = "";
$data_array['offse'] = 0;
$data_array['limit'] = 5000;
$data_array['sort'] = "";
$resource_ids = $cs->getDevicesQueries($data_array);

$crowdstrikes = array();
foreach ($resource_ids as $resource_id) {
    $data_array = array();
    $data_array['ids'] = $resource_id;
    $res = $cs->getDevicesEntities($data_array);
    $crowdstrikes[] = $res;
}

$table = "edr_crowdstrikes";
$key_column = "1";
$id = "1"; 
$db->delete($table, $key_column, $id); 

$count = 0;
$now = time();

foreach($crowdstrikes as $crowdstrike) {
    $crowdstrike['last_seen'] = date('Y-m-d H:i:s', strtotime($crowdstrike['last_seen']));
    $last_reported_date = strtotime(date('Y-m-d', strtotime($crowdstrike['last_seen'])));
    $date_diff = round(($now - $last_reported_date) / (60 * 60 * 24));
    $crowdstrike['unreported_day'] = $date_diff;
    $db->insert($table, $crowdstrike);
    $count = $count + 1;						
}

$nowTime = date("Y-m-d H:i:s");
echo "The " . $count . " records have been inserted or updated into the edr_crowdstrikes on " . $nowTime . PHP_EOL;
$status = 200;

$error = $db->getErrorMessageArray();
if(!empty($error)) {
	return;
}

$table = "apis"; // 設定你想查詢資料的資料表
$condition = "class LIKE :class and name LIKE :name";
$apis = $db->query($table, $condition, $order_by = "1", $fields = "*", $limit = "", [':class'=>'edr', ':name'=>'crowdstrike 設備清單']);
$table = "api_status"; // 設定你想新增資料的資料表
$data_array = array();
$data_array['api_id'] = $apis[0]['id'];
$data_array['url'] = "";
$data_array['status'] = $status;
$data_array['data_number'] = $count;
$data_array['updated_at'] = $nowTime;
$db->insert($table, $data_array);
