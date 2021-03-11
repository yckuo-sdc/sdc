<?php
require_once __DIR__ .'/../../vendor/autoload.php';

$db = Database::get();

$file = "/var/www/html/sdc/upload/antivirus/OfficeScan_agent_listing.csv";
$count = 0;
if (($handle = fopen($file, "r")) !== FALSE) {
	$table = "antivirus_client_list";
	$key_column = "1";
	$id = "1"; 
	$db->delete($table, $key_column, $id); 
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
			$num = count($data);
			if($num >= 70){
				$status['ClientName']= mb_convert_encoding($data[1],"utf-8","big5");
				$status['IP']= mb_convert_encoding($data[2],"utf-8","big5");
				$status['DomainLevel']= mb_convert_encoding($data[4],"utf-8","big5");
				$status['ConnectionState']= mb_convert_encoding($data[5],"utf-8","big5");
				$status['GUID']= mb_convert_encoding($data[6],"utf-8","big5");
				$status['ScanMethod']= mb_convert_encoding($data[7],"utf-8","big5");
				$status['DLPState']= mb_convert_encoding($data[8],"utf-8","big5");
				$status['VirusNum']= mb_convert_encoding($data[13],"utf-8","big5");
				$status['SpywareNum']= mb_convert_encoding($data[14],"utf-8","big5");
				$status['OS']= mb_convert_encoding($data[19],"utf-8","big5");
				$status['BitVersion']= mb_convert_encoding($data[20],"utf-8","big5");
				$status['MAC']= mb_convert_encoding($data[21],"utf-8","big5");
				$status['ClientVersion']= mb_convert_encoding($data[22],"utf-8","big5");
				$status['VirusPatternVersion']= mb_convert_encoding($data[24],"utf-8","big5");
				$status['LogonUser']= mb_convert_encoding($data[60],"utf-8","big5");
				
				$db->insert($table, $status);
				$count = $count + 1;							
			}
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
if(!empty($error)) {
	return;
}

$table = "apis"; // 設定你想查詢資料的資料表
$condition = "class LIKE :class and name LIKE :name";
$apis = $db->query($table, $condition, $order_by = "1", $fields = "*", $limit = "", [':class'=>'防毒軟體', ':name'=>'用戶端清單']);
$table = "api_status"; // 設定你想新增資料的資料表
$data_array['api_id'] = $apis[0]['id'];
$data_array['url'] = "";
$data_array['status'] = $status;
$data_array['data_number'] = $count;
$data_array['updated_at'] = $nowTime;
$db->insert($table, $data_array);
