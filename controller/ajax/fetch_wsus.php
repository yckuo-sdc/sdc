<?php
require_once 'vendor/autoload.php';

$db = Database::get();
$file = "/var/www/html/sdc/upload/wsus/GetComputerStatus.csv";
$row = 1;
$count = 0;
$status = array();
if (($handle = fopen($file, "r")) !== FALSE) {
	$table = "wsus_computer_status";
	$key_column = "1";
	$id = "1"; 
	$db->delete($table, $key_column, $id); 
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
		$num = count($data);
		if($num > 1){
			//echo "$num fields in line $row:\n";
			$row++;
			$status['TargetID']= trim($data[0]);
			$status['LastSyncTime']= trim($data[1]);
			$status['LastReportedStatusTime']= trim($data[2]);
			$status['LastReportedRebootTime']= trim($data[3]);
			$status['IPAddress']= trim($data[4]);
			$status['FullDomainName']= trim(mb_convert_encoding($data[5],"utf-8","big5"));
			$status['EffectiveLastDetectionTime']= trim($data[6]);
			$status['LastSyncResult']= trim($data[7]);
			$status['Unknown']= trim($data[8]);
			$status['NotInstalled']= trim($data[9]);
			$status['Downloaded']= trim($data[10]);
			$status['Installed']= trim($data[11]);
			$status['Failed']= trim($data[12]);
			$status['InstalledPendingReboot']= trim($data[13]);
			$status['LastChangeTime']= trim($data[14]);
			$status['ComputerMake']= trim($data[15]);
			$status['ComputerModel']= trim($data[16]);
			$status['OSDescription']= trim($data[17]);
			
			$db->insert($table, $status);
			$count = $count + 1;							
		}
    }
    fclose($handle);
	$nowTime = date("Y-m-d H:i:s");
	$nowTime = date("Y-m-d H:i:s", filemtime($file));
	echo "The ".$count." records have been inserted or updated into the wsus_computer_status on ".$nowTime."\n\r<br>";
	$status = 200;
}else{
	echo "No target-data \n\r<br>";
	$status = 400;
}

$table = "apis"; // 設定你想查詢資料的資料表
$condition = "class LIKE :class and name LIKE :name";
$apis = $db->query($table, $condition, $order_by = "1", $fields = "*", $limit = "", [':class'=>'wsus', ':name'=>'用戶端清單']);
$table = "api_status"; // 設定你想新增資料的資料表
$data_array['api_id'] = $apis[0]['id'];
$data_array['url'] = "";
$data_array['status'] = $status;
$data_array['data_number'] = $count;
$data_array['last_update'] = $nowTime;
$db->insert($table, $data_array);

$file = "/var/www/html/sdc/upload/wsus/GetUpdateStatusKBID.csv";
$row = 1;
$count = 0;
$status = array();
if (($handle = fopen($file, "r")) !== FALSE) {
	$table = "wsus_computer_updatestatus_kbid";
	$key_column = "1";
	$id = "1"; 
	$db->delete($table, $key_column, $id); 
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
		$num = count($data);
		if($num > 1){
			//echo "$num fields in line $row:\n";
			$row++;
			$status['TargetID']= trim($data[0]);
			$status['KBArticleID']= trim($data[1]);
			$status['UpdateState']= trim($data[2]);
			
			$db->insert($table, $status);
			$count = $count + 1;							
		}
    }
    fclose($handle);
	$nowTime = date("Y-m-d H:i:s");
	$nowTime = date("Y-m-d H:i:s", filemtime($file));
	echo "The ".$count." records have been inserted or updated into the wsus_computer_status on ".$nowTime."\n\r<br>";
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
$apis = $db->query($table, $condition, $order_by = "1", $fields = "*", $limit = "", [':class'=>'wsus', ':name'=>'更新資訊']);
$table = "api_status"; // 設定你想新增資料的資料表
$data_array['api_id'] = $apis[0]['id'];
$data_array['url'] = "";
$data_array['status'] = $status;
$data_array['data_number'] = $count;
$data_array['last_update'] = $nowTime;
$db->insert($table, $data_array);
