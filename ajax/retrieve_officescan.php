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

$file = "/var/www/html/sdc/upload/upload_antivirus/OfficeScan_agent_listing.csv";
$row = 2;
$count = 0;
if (($handle = fopen($file, "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
			//echo "$num fields in line $row:\n";
			$num = count($data);
			//echo "<p> $num fields in line $row: <br /></p>\n";
			$row++;
			if($num >= 70){
				$status['ClientName']= mysqli_real_escape_string($conn,trim(mb_convert_encoding($data[1],"utf-8","big5")));
				$status['IP']= mysqli_real_escape_string($conn,trim(mb_convert_encoding($data[2],"utf-8","big5")));
				$status['DomainLevel']= mysqli_real_escape_string($conn,trim(mb_convert_encoding($data[4],"utf-8","big5")));
				$status['ConnectionState']= mysqli_real_escape_string($conn,trim(mb_convert_encoding($data[5],"utf-8","big5")));
				$status['GUID']= mysqli_real_escape_string($conn,trim(mb_convert_encoding($data[6],"utf-8","big5")));
				$status['ScanMethod']= mysqli_real_escape_string($conn,trim(mb_convert_encoding($data[7],"utf-8","big5")));
				$status['DLPState']= mysqli_real_escape_string($conn,trim(mb_convert_encoding($data[8],"utf-8","big5")));
				$status['VirusNum']= mysqli_real_escape_string($conn,trim(mb_convert_encoding($data[13],"utf-8","big5")));
				$status['SpywareNum']= mysqli_real_escape_string($conn,trim(mb_convert_encoding($data[14],"utf-8","big5")));
				$status['OS']= mysqli_real_escape_string($conn,trim(mb_convert_encoding($data[19],"utf-8","big5")));
				$status['BitVersion']= mysqli_real_escape_string($conn,trim(mb_convert_encoding($data[20],"utf-8","big5")));
				$status['MAC']= mysqli_real_escape_string($conn,trim(mb_convert_encoding($data[21],"utf-8","big5")));
				$status['ClientVersion']= mysqli_real_escape_string($conn,trim(mb_convert_encoding($data[22],"utf-8","big5")));
				$status['VirusPatternVersion']= mysqli_real_escape_string($conn,trim(mb_convert_encoding($data[24],"utf-8","big5")));
				$status['LogonUser']= mysqli_real_escape_string($conn,trim(mb_convert_encoding($data[60],"utf-8","big5")));
				
				// INSERT to table ON DUPLICATE KEY UPDATE data
				$sql = "insert into antivirus_client_list(ClientName,IP,DomainLevel,ConnectionState,GUID,ScanMethod,DLPState,VirusNum,SpywareNum,OS,BitVersion,MAC,ClientVersion,VirusPatternVersion,LogonUser) values('".$status['ClientName']."','".$status['IP']."','".$status['DomainLevel']."','".$status['ConnectionState']."','".$status['GUID']."','".$status['ScanMethod']."','".$status['DLPState']."','".$status['VirusNum']."','".$status['SpywareNum']."','".$status['OS']."','".$status['BitVersion']."','".$status['MAC']."','".$status['ClientVersion']."','".$status['VirusPatternVersion']."','".$status['LogonUser']."')
				ON DUPLICATE KEY UPDATE ClientName = '".$status['ClientName']."',IP = '".$status['IP']."',DomainLevel = '".$status['DomainLevel']."',ConnectionState = '".$status['ConnectionState']."',ScanMethod = '".$status['ScanMethod']."',DLPState = '".$status['DLPState']."',VirusNum = '".$status['VirusNum']."',SpywareNum = '".$status['SpywareNum']."',OS = '".$status['OS']."',BitVersion = '".$status['BitVersion']."',MAC = '".$status['MAC']."',ClientVersion = '".$status['ClientVersion']."',VirusPatternVersion = '".$status['VirusPatternVersion']."',LogonUser = '".$status['LogonUser']."'";
				if ($conn->query($sql) == TRUE) {
					$count = $count + 1;							
				} else {
					echo "Error: " . $sql . "<br>" . $conn->error."<p>\n\r";
				}
			}
    }
    fclose($handle);
	$nowTime 	= date("Y-m-d H:i:s");
	echo "The ".$count." records have been inserted or updated into the antivirus_clinet_list on ".$nowTime."\n\r<br>";
	$status = 200;
}else{
	echo "No target-data \n\r<br>";
	$status = 400;
}
	$sql = "SELECT * FROM api_list WHERE class LIKE '防毒軟體' and name LIKE '用戶端清單' ";
	$result = mysqli_query($conn,$sql);
	$row = mysqli_fetch_assoc($result);
	$sql = "INSERT INTO api_status(api_id,url,status,data_number,last_update) VALUES(".$row['id'].",'',".$status.",".$count.",'".$nowTime."')";
	if ($conn->query($sql) == TRUE){
	}else {
		echo "Error: " . $sql . "<br>" . $conn->error."<p>\n\r";
	}
	$conn->close();
?>
