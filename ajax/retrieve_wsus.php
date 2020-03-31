<?php
namespace wsus\api;

require("../mysql_connect.inc.php");
date_default_timezone_set("Asia/Taipei");

$file_path = "/var/www/html/sdc/upload/upload_wsus/GetComputerStatus.csv";
$row = 1;
$count = 0;
$status = array();
if (($handle = fopen($file_path, "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
		$num = count($data);
		if($num > 1){
			//echo "$num fields in line $row:\n";
			$row++;
			$status['TargetID']= mysqli_real_escape_string($conn,trim($data[0]));
			$status['LastSyncTime']= mysqli_real_escape_string($conn,trim($data[1]));
			$status['LastReportedStatusTime']= mysqli_real_escape_string($conn,trim($data[2]));
			$status['LastReportedRebootTime']= mysqli_real_escape_string($conn,trim($data[3]));
			$status['IPAddress']= mysqli_real_escape_string($conn,trim($data[4]));
			$status['FullDomainName']= mysqli_real_escape_string($conn,trim($data[5]));
			$status['EffectiveLastDetectionTime']= mysqli_real_escape_string($conn,trim($data[6]));
			$status['LastSyncResult']= mysqli_real_escape_string($conn,trim($data[7]));
			$status['Unknown']= mysqli_real_escape_string($conn,trim($data[8]));
			$status['NotInstalled']= mysqli_real_escape_string($conn,trim($data[9]));
			$status['Downloaded']= mysqli_real_escape_string($conn,trim($data[10]));
			$status['Installed']= mysqli_real_escape_string($conn,trim($data[11]));
			$status['Failed']= mysqli_real_escape_string($conn,trim($data[12]));
			$status['InstalledPendingReboot']= mysqli_real_escape_string($conn,trim($data[13]));
			$status['LastChangeTime']= mysqli_real_escape_string($conn,trim($data[14]));
			
			// INSERT to table ON DUPLICATE KEY UPDATE data
			$sql = "insert into wsus_computer_status(TargetID,LastSyncTime,LastReportedStatusTime,LastReportedRebootTime,IPAddress,FullDomainName,EffectiveLastDetectionTime,LastSyncResult,Unknown,NotInstalled,Downloaded,Installed,Failed,InstalledPendingReboot,LastChangeTime) values(".$status['TargetID'].",'".$status['LastSyncTime']."','".$status['LastReportedStatusTime']."','".$status['LastReportedRebootTime']."','".$status['IPAddress']."','".$status['FullDomainName']."','".$status['EffectiveLastDetectionTime']."','".$status['LastSyncResult']."','".$status['Unknown']."',".$status['NotInstalled'].",'".$status['Downloaded']."',".$status['Installed'].",'".$status['Failed']."','".$status['InstalledPendingReboot']."','".$status['LastChangeTime']."')
			ON DUPLICATE KEY UPDATE LastSyncTime = '".$status['LastSyncTime']."',LastReportedStatusTime = '".$status['LastReportedStatusTime']."',LastReportedRebootTime = '".$status['LastReportedRebootTime']."',IPAddress = '".$status['IPAddress']."',FullDomainName = '".$status['FullDomainName']."',EffectiveLastDetectionTime = '".$status['EffectiveLastDetectionTime']."',LastSyncResult = '".$status['LastSyncResult']."',Unknown = '".$status['Unknown']."',NotInstalled = '".$status['NotInstalled']."',Downloaded = '".$status['Downloaded']."',Installed = '".$status['Installed']."',Failed = '".$status['Failed']."',InstalledPendingReboot = '".$status['InstalledPendingReboot']."',LastChangeTime = '".$status['LastChangeTime']."'";
			if ($conn->query($sql) == TRUE) {
				$count = $count + 1;							
			} else {
				echo "Error: " . $sql . "<br>" . $conn->error."<p>\n\r";
			}
		}
    }
    fclose($handle);
	$nowTime 	= date("Y-m-d H:i:s");
	echo "The ".$count." records have been inserted or updated into the wsus_computer_status on ".$nowTime."\n\r<br>";
	$status = 200;
}else{
	echo "No target-data \n\r<br>";
	$status = 400;
}
$sql = "SELECT * FROM api_list WHERE class LIKE 'WSUS' and name LIKE '用戶端清單' ";
$result = mysqli_query($conn,$sql);
$row = mysqli_fetch_assoc($result);
$sql = "INSERT INTO api_status(api_id,url,status,data_number,last_update) VALUES(".$row['id'].",'',".$status.",".$count.",'".$nowTime."')";
if ($conn->query($sql) == TRUE){
}else {
	echo "Error: " . $sql . "<br>" . $conn->error."<p>\n\r";
}


$file_path = "/var/www/html/sdc/upload/upload_wsus/GetUpdateStatusKBID.csv";
$row = 1;
$count = 0;
$status = array();
if (($handle = fopen($file_path, "r")) !== FALSE) {
	$sql = "TRUNCATE TABLE wsus_computer_updatestatus_kbid";	
	$conn->query($sql); 
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
		$num = count($data);
		if($num > 1){
			//echo "$num fields in line $row:\n";
			$row++;
			$status['TargetID']= mysqli_real_escape_string($conn,trim($data[0]));
			$status['KBArticleID']= mysqli_real_escape_string($conn,trim($data[1]));
			$status['UpdateState']= mysqli_real_escape_string($conn,trim($data[2]));
			
			// INSERT to table ON DUPLICATE KEY UPDATE data
			$sql = "insert into wsus_computer_updatestatus_kbid(TargetID,KBArticleID,UpdateState) values(".$status['TargetID'].",".$status['KBArticleID'].",'".$status['UpdateState']."')";
			if ($conn->query($sql) == TRUE) {
				$count = $count + 1;
				//echo $count."<br>\n";				
			} else {
				echo "Error: " . $sql . "<br>" . $conn->error."<p>\n\r";
			}
		}
    }
    fclose($handle);
	$nowTime 	= date("Y-m-d H:i:s");
	echo "The ".$count." records have been inserted or updated into the wsus_computer_status on ".$nowTime."\n\r<br>";
	$status = 200;
}else{
	echo "No target-data \n\r<br>";
	$status = 400;
}
$sql = "SELECT * FROM api_list WHERE class LIKE 'WSUS' and name LIKE '更新資訊' ";
$result = mysqli_query($conn,$sql);
$row = mysqli_fetch_assoc($result);
$sql = "INSERT INTO api_status(api_id,url,status,data_number,last_update) VALUES(".$row['id'].",'',".$status.",".$count.",'".$nowTime."')";
if ($conn->query($sql) == TRUE){
}else {
	echo "Error: " . $sql . "<br>" . $conn->error."<p>\n\r";
}

$conn->close();


?>
