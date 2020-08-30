<?php
require '../libraries/Database.php';
$db = Database::get();

$file = "/var/www/html/sdc/upload/upload_antivirus/OfficeScan_agent_listing.csv";
$count = 0;
if (($handle = fopen($file, "r")) !== FALSE) {
	$table = "antivirus_client_list";
	$key_column = "1";
	$id = "1"; 
	$db->delete($table, $key_column, $id); 
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
			$num = count($data);
			if($num >= 70){
				$status['ClientName']= $db->getEscapedString(mb_convert_encoding($data[1],"utf-8","big5"));
				$status['IP']= $db->getEscapedString(mb_convert_encoding($data[2],"utf-8","big5"));
				$status['DomainLevel']= $db->getEscapedString(mb_convert_encoding($data[4],"utf-8","big5"));
				$status['ConnectionState']= $db->getEscapedString(mb_convert_encoding($data[5],"utf-8","big5"));
				$status['GUID']= $db->getEscapedString(mb_convert_encoding($data[6],"utf-8","big5"));
				$status['ScanMethod']= $db->getEscapedString(mb_convert_encoding($data[7],"utf-8","big5"));
				$status['DLPState']= $db->getEscapedString(mb_convert_encoding($data[8],"utf-8","big5"));
				$status['VirusNum']= $db->getEscapedString(mb_convert_encoding($data[13],"utf-8","big5"));
				$status['SpywareNum']= $db->getEscapedString(mb_convert_encoding($data[14],"utf-8","big5"));
				$status['OS']= $db->getEscapedString(mb_convert_encoding($data[19],"utf-8","big5"));
				$status['BitVersion']= $db->getEscapedString(mb_convert_encoding($data[20],"utf-8","big5"));
				$status['MAC']= $db->getEscapedString(mb_convert_encoding($data[21],"utf-8","big5"));
				$status['ClientVersion']= $db->getEscapedString(mb_convert_encoding($data[22],"utf-8","big5"));
				$status['VirusPatternVersion']= $db->getEscapedString(mb_convert_encoding($data[24],"utf-8","big5"));
				$status['LogonUser']= $db->getEscapedString(mb_convert_encoding($data[60],"utf-8","big5"));
				
				$db->insert($table, $status);
				$count = $count + 1;							
			}
    }
    fclose($handle);
	$nowTime = date("Y-m-d H:i:s");
	echo "The ".$count." records have been inserted or updated into the antivirus_clinet_list on ".$nowTime."\n\r<br>";
	$status = 200;
}else{
	echo "No target-data \n\r<br>";
	$status = 400;
}

$table = "api_list"; // 設定你想查詢資料的資料表
$condition = "class LIKE '防毒軟體' and name LIKE '用戶端清單' ";
$api_list = $db->query($table, $condition, $order_by = "1", $fields = "*", $limit = "");
$table = "api_status"; // 設定你想新增資料的資料表
$data_array['api_id'] = $api_list[0]['id'];
$data_array['url'] = "";
$data_array['status'] = $status;
$data_array['data_number'] = $count;
$data_array['last_update'] = $nowTime;
$db->insert($table, $data_array);

