<?php
require '../libraries/Database.php';
$db = Database::get();

$file_path = "/var/www/html/sdc/upload/upload_wsus/GetComputerStatus.csv";
$row = 1;
$count = 0;
$status = array();
if (($handle = fopen($file_path, "r")) !== FALSE) {
	$table = "wsus_computer_status";
	$key_column = "1";
	$id = "1"; 
	$db->delete($table, $key_column, $id); 
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
		$num = count($data);
		if($num > 1){
			//echo "$num fields in line $row:\n";
			$row++;
			$status['TargetID']= $db->getEscapedString(trim($data[0]));
			$status['LastSyncTime']= $db->getEscapedString(trim($data[1]));
			$status['LastReportedStatusTime']= $db->getEscapedString(trim($data[2]));
			$status['LastReportedRebootTime']= $db->getEscapedString(trim($data[3]));
			$status['IPAddress']= $db->getEscapedString(trim($data[4]));
			$status['FullDomainName']= $db->getEscapedString(trim(mb_convert_encoding($data[5],"utf-8","big5")));
			$status['EffectiveLastDetectionTime']= $db->getEscapedString(trim($data[6]));
			$status['LastSyncResult']= $db->getEscapedString(trim($data[7]));
			$status['Unknown']= $db->getEscapedString(trim($data[8]));
			$status['NotInstalled']= $db->getEscapedString(trim($data[9]));
			$status['Downloaded']= $db->getEscapedString(trim($data[10]));
			$status['Installed']= $db->getEscapedString(trim($data[11]));
			$status['Failed']= $db->getEscapedString(trim($data[12]));
			$status['InstalledPendingReboot']= $db->getEscapedString(trim($data[13]));
			$status['LastChangeTime']= $db->getEscapedString(trim($data[14]));
			$status['ComputerMake']= $db->getEscapedString(trim($data[15]));
			$status['ComputerModel']= $db->getEscapedString(trim($data[16]));
			$status['OSDescription']= $db->getEscapedString(trim($data[17]));
			
			$db->insert($table, $status);
			$count = $count + 1;							
		}
    }
    fclose($handle);
	$nowTime = date("Y-m-d H:i:s");
	echo "The ".$count." records have been inserted or updated into the wsus_computer_status on ".$nowTime."\n\r<br>";
	$status = 200;
}else{
	echo "No target-data \n\r<br>";
	$status = 400;
}
$table = "api_list"; // 設定你想查詢資料的資料表
$condition = "class LIKE 'WSUS' and name LIKE '用戶端清單' ";
$api_list = $db->query($table, $condition, $order_by = "1", $fields = "*", $limit = "");
$table = "api_status"; // 設定你想新增資料的資料表
$data_array['api_id'] = $api_list[0]['id'];
$data_array['url'] = "";
$data_array['status'] = $status;
$data_array['data_number'] = $count;
$data_array['last_update'] = $nowTime;
$db->insert($table, $data_array);

$file_path = "/var/www/html/sdc/upload/upload_wsus/GetUpdateStatusKBID.csv";
$row = 1;
$count = 0;
$status = array();
if (($handle = fopen($file_path, "r")) !== FALSE) {
	$table = "wsus_computer_updatestatus_kbid";
	$key_column = "1";
	$id = "1"; 
	$db->delete($table, $key_column, $id); 
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
		$num = count($data);
		if($num > 1){
			//echo "$num fields in line $row:\n";
			$row++;
			$status['TargetID']= $db->getEscapedString(trim($data[0]));
			$status['KBArticleID']= $db->getEscapedString(trim($data[1]));
			$status['UpdateState']= $db->getEscapedString(trim($data[2]));
			
			$db->insert($table, $status);
			$count = $count + 1;							
		}
    }
    fclose($handle);
	$nowTime = date("Y-m-d H:i:s");
	echo "The ".$count." records have been inserted or updated into the wsus_computer_status on ".$nowTime."\n\r<br>";
	$status = 200;
}else{
	echo "No target-data \n\r<br>";
	$status = 400;
}
$table = "api_list"; // 設定你想查詢資料的資料表
$condition = "class LIKE 'WSUS' and name LIKE '更新資訊' ";
$api_list = $db->query($table, $condition, $order_by = "1", $fields = "*", $limit = "");
$table = "api_status"; // 設定你想新增資料的資料表
$data_array['api_id'] = $api_list[0]['id'];
$data_array['url'] = "";
$data_array['status'] = $status;
$data_array['data_number'] = $count;
$data_array['last_update'] = $nowTime;
$db->insert($table, $data_array);
