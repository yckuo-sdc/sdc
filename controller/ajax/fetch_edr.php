<?php
require_once 'vendor/autoload.php';

$db = Database::get();
$file = "/var/www/html/sdc/upload/edr/endpoints.csv";
$count = 0;
$skip_row = true;
if (($handle = fopen($file, "r")) !== FALSE) {
	$table = "edr_endpoints";
	$key_column = "1";
	$id = "1"; 
	$db->delete($table, $key_column, $id); 
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
		if($skip_row){
			$skip_row = false;
			continue;
		}
		$status['host_name']= mb_convert_encoding($data[0],"utf-8","big5");
		$status['ip']= mb_convert_encoding($data[1],"utf-8","big5");
		$status['status']= mb_convert_encoding($data[2],"utf-8","big5");
		$status['last_online_time']= mb_convert_encoding($data[3],"utf-8","big5");
		$status['os']= mb_convert_encoding($data[4],"utf-8","big5");
		$status['close_number']= mb_convert_encoding($data[5],"utf-8","big5");
		$status['processing_number']= mb_convert_encoding($data[6],"utf-8","big5");
		$status['total_number']= mb_convert_encoding($data[7],"utf-8","big5");
		$status['hidden_state']= mb_convert_encoding($data[8],"utf-8","big5");
		
		$db->insert($table, $status);
		$count = $count + 1;						
    }
    fclose($handle);
	$nowTime = date("Y-m-d H:i:s");
	$nowTime = date("Y-m-d H:i:s", filemtime($file));
	echo "The ".$count." records have been inserted or updated into the antivirus_clinet_list on ".$nowTime."\n\r<br>";
	$status = 200;
}else{
	echo "No target-data \n\r<br>";
	$status = 400;
}

$error = $db->getErrorMessageArray();
if(@count($error) > 0) {
	return;
}

$table = "apis"; // 設定你想查詢資料的資料表
$condition = "class LIKE :class and name LIKE :name";
$apis = $db->query($table, $condition, $order_by = "1", $fields = "*", $limit = "", [':class'=>'edr', ':name'=>'用戶端清單']);
$table = "api_status"; // 設定你想新增資料的資料表
$data_array['api_id'] = $apis[0]['id'];
$data_array['url'] = "";
$data_array['status'] = $status;
$data_array['data_number'] = $count;
$data_array['last_update'] = $nowTime;
$db->insert($table, $data_array);
