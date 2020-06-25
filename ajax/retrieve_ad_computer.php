<?php
namespace ad\api;
require("../mysql_connect.inc.php");
require("../ldap_config.inc.php");
require("../login/function.php");
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
$host_ip = "172.16.10.101";
$ldapconn = ldap_connect("ldap://".$host_ip) or die("Could not connect to LDAP server.");
$set = ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
if($ldapconn){
	//bind user
	$ldap_bd = ldap_bind($ldapconn,$account."@".$host_dn,$password);
	$keyword = "(objectClass=computer)";
	echo "host_ip=".$host_ip."<br>\n";
	$ou = ["TainanLocalUser","TainanComputer"];
	$result = ldap_search($ldapconn,"OU=TainanComputer,dc=tainan,dc=gov,dc=tw",$keyword) or die ("Error in query");
	$data = ldap_get_entries($ldapconn,$result);
	$count = 0;
	echo $data["count"]. " entries returned\n";
	if($data["count"]!=0){
		$sql = "TRUNCATE TABLE ad_comupter_list";
		$conn->query($sql); 
		for($i=0;$i<$data["count"];$i++) {
			$desc=get_ou_desc($data[$i]['distinguishedname'][0],$ldapconn);
			if(isset($data[$i]['dnshostname'][0])){
				$output = shell_exec("/usr/bin/dig +short ".$data[$i]['dnshostname'][0]);
				$ips = explode(PHP_EOL,$output);
				$size = sizeof($ips);
				$status['DnsHostname']= mysqli_real_escape_string($conn,trim($data[$i]['dnshostname'][0]));
			}else{
				$status['DnsHostname']= "";
				$status['IP']= "";
			}	
			if(isset($data[$i]['OperatingSystem'][0])){
				$status['OperatingSystem']= mysqli_real_escape_string($conn,trim($data[$i]['operatingsystem'][0]));
			}else{
				$status['OperatingSystem']= "";
			}
			if(isset($data[$i]['OperatingSystemVersion'][0])){
				$status['OperatingSystemVersion']= mysqli_real_escape_string($conn,trim($data[$i]['operatingsystemversion'][0]));
			}else{
				$status['OperatingSystemVersion']= "";
			}
			$status['CommonName']= mysqli_real_escape_string($conn,trim($data[$i]['cn'][0]));
			$status['DistinguishedName']= mysqli_real_escape_string($conn,trim($data[$i]['distinguishedname'][0]));
			$status['OrgName']= mysqli_real_escape_string($conn,trim($desc));
			if(!isset($size) || $size==1){
				$status['IP']= "";
				// INSERT to table ON DUPLICATE KEY UPDATE data
				$sql = "insert into ad_comupter_list(CommonName,DnsHostname,OperatingSystem,OperatingSystemVersion,IP,DistinguishedName,OrgName) values('".$status['CommonName']."','".$status['DnsHostname']."','".$status['OperatingSystem']."','".$status['OperatingSystemVersion']."','".$status['IP']."','".$status['DistinguishedName']."','".$status['OrgName']."')";
				if ($conn->query($sql) == TRUE) {
					$count = $count + 1;							
				} else {
					echo "Error: " . $sql . "<br>" . $conn->error."<p>\n\r";
				}
			}else{
				for($j=0;$j<$size-1;$j++){
					// INSERT to table ON DUPLICATE KEY UPDATE data
					$status['IP']= mysqli_real_escape_string($conn,trim($ips[$j]));
					$sql = "insert into ad_comupter_list(CommonName,DnsHostname,OperatingSystem,OperatingSystemVersion,IP,DistinguishedName,OrgName) values('".$status['CommonName']."','".$status['DnsHostname']."','".$status['OperatingSystem']."','".$status['OperatingSystemVersion']."','".$status['IP']."','".$status['DistinguishedName']."','".$status['OrgName']."')";
					if ($conn->query($sql) == TRUE) {
						$count = $count + 1;							
					} else {
						echo "Error: " . $sql . "<br>" . $conn->error."<p>\n\r";
					}
				}
			}
		}
		$nowTime 	= date("Y-m-d H:i:s");
		echo "The ".$count." records have been inserted or updated into the ad_computer_list on ".$nowTime."\n\r<br>";
		$status = 200;
	}else{
		echo "No target-data \n\r<br>";
		$status = 400;
	}
}	
ldap_close($ldapconn);

$sql = "SELECT * FROM api_list WHERE class LIKE 'AD' and name LIKE '個人電腦清單' ";
$result = mysqli_query($conn,$sql);
$row = mysqli_fetch_assoc($result);
$sql = "INSERT INTO api_status(api_id,url,status,data_number,last_update) VALUES(".$row['id'].",'',".$status.",".$count.",'".$nowTime."')";
if ($conn->query($sql) == TRUE){
}else {
	echo "Error: " . $sql . "<br>" . $conn->error."<p>\n\r";
}
$conn->close();

?>
