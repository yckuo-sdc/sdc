<?php
namespace officescan\api;
require("../mysql_connect.inc.php");
date_default_timezone_set("Asia/Taipei");

function array2csv($list){
	if (count($list) == 0) {
		   return null;
	}
	$fp = fopen('file.csv', 'w');
	fwrite($fp,"\xEF\xBB\xBF");
	foreach ($list as $fields) {
	    fputcsv($fp, $fields);
	}
	fclose($fp);
	return true;
}

$file = "/var/www/html/sdc/upload/upload_drip/IP_MAC_USED_IP_List.csv";
$row = 1;
$count = 0;
if (($handle = fopen($file, "r")) !== FALSE) {
	$sql = "TRUNCATE TABLE drip_ip_mac_used_list";
	$conn->query($sql); 
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
			$num = count($data);
			//echo "<p> $num fields in line $row: <br /></p>\n";
			$row++;
			if($num>=44 && $data[3]!='' && $data[3]!=''){
				$status['isOnline']= mysqli_real_escape_string($conn,trim($data[0]));
				$status['DetectorName']= mysqli_real_escape_string($conn,trim($data[2]));
				$status['DetectorIP']= mysqli_real_escape_string($conn,trim($data[29]));
				$status['DetectorGroup']= mysqli_real_escape_string($conn,trim($data[3]));
				$status['IP']= mysqli_real_escape_string($conn,trim($data[4]));
				$status['MAC']= mysqli_real_escape_string($conn,trim($data[5]));
				$status['IP_Block']= mysqli_real_escape_string($conn,trim($data[6]));
				$status['MAC_Block']= mysqli_real_escape_string($conn,trim($data[7]));
				$status['GroupName']= mysqli_real_escape_string($conn,trim($data[8]));
				$status['ClientName']= mysqli_real_escape_string($conn,trim($data[9]));
				$status['SwitchIP']= mysqli_real_escape_string($conn,trim($data[12]));
				$status['SwitchName']= mysqli_real_escape_string($conn,trim($data[13]));
				$status['PortName']= mysqli_real_escape_string($conn,trim($data[14]));
				$status['NICProductor']= mysqli_real_escape_string($conn,trim($data[11]));
				$status['LastOnlineTime']= mysqli_real_escape_string($conn,trim($data[23]));
				$status['LastOfflineTime']= mysqli_real_escape_string($conn,trim($data[24]));
				$status['IPMAC_Bind']= mysqli_real_escape_string($conn,trim($data[25]));
				$status['IP_Grant']= mysqli_real_escape_string($conn,trim($data[31]));
				$status['MAC_Grant']= mysqli_real_escape_string($conn,trim($data[32]));
				$status['IP_BlockReason']= mysqli_real_escape_string($conn,trim($data[41]));
				$status['MAC_BlockReason']= mysqli_real_escape_string($conn,trim($data[42]));
				$status['MemoByMAC']= mysqli_real_escape_string($conn,trim($data[44]));
				$status['MemoByIP']= mysqli_real_escape_string($conn,trim($data[45]));
				
				// INSERT to table ON DUPLICATE KEY UPDATE data
				$sql = "insert into drip_ip_mac_used_list(isOnline,DetectorName,DetectorIP,DetectorGroup,IP,MAC,IP_Block,MAC_Block,GroupName,ClientName,SwitchIP,SwitchName,PortName,NICProductor,LastOnlineTime,LastOfflineTime,IPMAC_Bind,IP_Grant,MAC_Grant,IP_BlockReason,MAC_BlockReason,MemoByMAC,MemoByIP) values('".$status['isOnline']."','".$status['DetectorName']."','".$status['DetectorIP']."','".$status['DetectorGroup']."','".$status['IP']."','".$status['MAC']."','".$status['IP_Block']."','".$status['MAC_Block']."','".$status['GroupName']."','".$status['ClientName']."','".$status['SwitchIP']."','".$status['SwitchName']."','".$status['PortName']."','".$status['NICProductor']."','".$status['LastOnlineTime']."','".$status['LastOfflineTime']."','".$status['IPMAC_Bind']."','".$status['IP_Grant']."','".$status['MAC_Grant']."','".$status['IP_BlockReason']."','".$status['MAC_BlockReason']."','".$status['MemoByMAC']."','".$status['MemoByIP']."')";
				if ($conn->query($sql) == TRUE) {
					$count = $count + 1;							
				} else {
					echo "Error: " . $sql . "<br>" . $conn->error."<p>\n\r";
				}
			}
    }
    fclose($handle);
	$nowTime 	= date("Y-m-d H:i:s");
	echo "The ".$count." records have been inserted or updated into the drip_clinet_list on ".$nowTime."\n\r<br>";
	$status = 200;
}else{
	echo "No target-data \n\r<br>";
	$status = 400;
}
	$sql = "SELECT * FROM api_list WHERE class LIKE 'IP管理' and name LIKE '使用IP清單' ";
	$result = mysqli_query($conn,$sql);
	$row = mysqli_fetch_assoc($result);
	$sql = "INSERT INTO api_status(api_id,url,status,data_number,last_update) VALUES(".$row['id'].",'',".$status.",".$count.",'".$nowTime."')";
	if ($conn->query($sql) == TRUE){
	}else {
		echo "Error: " . $sql . "<br>" . $conn->error."<p>\n\r";
	}
	
	$conn->close();
?>
