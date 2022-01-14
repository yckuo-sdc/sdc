<?php
require_once __DIR__ .'/../../vendor/autoload.php';

$db = Database::get();

// fetch tndevs's json
$url = "https://tndev.tainan.gov.tw/api/values/5";
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => $url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_SSL_VERIFYPEER => false,
  CURLOPT_SSL_VERIFYHOST => false,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_HTTPHEADER => array("Content-Type:: application/json")
));
$res = curl_exec($curl);
curl_close($curl);

$entries = json_decode($res, true);

if (empty($entries)) {
	return;
}

// insert entries into database
$table = "tndevs";
$key_column = "1";
$id = "1"; 
$db->delete($table, $key_column, $id); 

$table = "tndev_ips";
$key_column = "1";
$id = "1"; 
$db->delete($table, $key_column, $id); 

$count = 0;
$id = 1;
foreach ($entries as $entry) {
    $table = "tndevs";
    $data_array = array();
    $data_array['id'] = $entry['id'];
    $data_array['ip'] = $entry['ipv4'];
    $data_array['name'] = $entry['svname'];
    $data_array['description'] = $entry['usage']; 
    $data_array['owner'] = $entry['DEPT_USER'];
    $data_array['department'] = $entry['DEPT'];
    $data_array['section'] = $entry['DEPT_SECTION'];
    $data_array['tel'] = $entry['CONTACT_INFO'];
    $data_array['mail'] = $entry['CONTACT_MAIL'];

    if (!empty($entry['STOP_DATE'])) {
        $data_array['disabled_at'] = date('Y-m-d H:i:s', strtotime($entry['STOP_DATE']));
    }
    if (!empty($entry['DOWN_DATE'])) {
        $data_array['shut_at'] = date('Y-m-d H:i:s', strtotime($entry['DOWN_DATE']));
    }

    $db->insert($table, $data_array);
    $count = $count + 1;

    $ips = explode(",", $entry['ipv4']);
    foreach ($ips as $ip) {
        $table = "tndev_ips";
        $data_array = array();
        $data_array['id'] = $id;
        $data_array['tndev_id'] = $entry['id'];
        $data_array['ip'] = $ip;
        $db->insert($table, $data_array);
        $id = $id + 1;
    }
}

$nowTime = date("Y-m-d H:i:s");
$status = 200;
echo "The " . $count . " records have been inserted or updated into the tndevs on " . $nowTime . PHP_EOL;

$error = $db->getErrorMessageArray();
if(!empty($error)) {
	return;
}

$table = "apis"; // 設定你想查詢資料的資料表
$condition = "class LIKE :class and name LIKE :name";
$apis = $db->query($table, $condition, $order_by = "1", $fields = "*", $limit = "", [':class'=>'網管系統', ':name'=>'設備清單']);
$table = "api_status"; // 設定你想新增資料的資料表
$data_array = array();
$data_array['api_id'] = $apis[0]['id'];
$data_array['url'] = $url;
$data_array['status'] = $status;
$data_array['data_number'] = $count;
$data_array['updated_at'] = $nowTime;
$db->insert($table, $data_array);
