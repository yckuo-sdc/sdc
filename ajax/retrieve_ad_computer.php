<?php
require	'../ldap_admin_config.inc.php';
require '../login/function.php';
require '../libraries/Database.php';
$db = Database::get();

$host_ip = "172.16.10.101";
$ldapconn = ldap_connect("ldap://".$host_ip) or die("Could not connect to LDAP server.");
$set = ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
if($ldapconn){
	$ldap_bd = ldap_bind($ldapconn,$account."@".$host_dn,$password);
	$keyword = "(objectClass=computer)";
	echo "host_ip=".$host_ip."<br>\n";
	$ou = ["TainanLocalUser","TainanComputer"];
	$result = ldap_search($ldapconn,"OU=TainanComputer,dc=tainan,dc=gov,dc=tw",$keyword) or die ("Error in query");
	$data = ldap_get_entries($ldapconn,$result);
	$count = 0;
	echo $data["count"]. " entries returned\n";
	if($data["count"]!=0){
		$table = "ad_comupter_list";
		$key_column = "1";
		$id = "1"; 
		$db->delete($table, $key_column, $id); 
		for($i=0; $i<$data["count"]; $i++) {
			$desc=get_ou_desc($data[$i]['distinguishedname'][0],$ldapconn);
			if(isset($data[$i]['dnshostname'][0])){
				$output = shell_exec("/usr/bin/dig +short ".$data[$i]['dnshostname'][0]);
				$ips = explode(PHP_EOL,$output);
				$size = sizeof($ips);
				$status['DnsHostname']= $db->getEscapedString(trim($data[$i]['dnshostname'][0]));
			}else{
				$status['DnsHostname']= "";
				$status['IP']= "";
			}	
			if(isset($data[$i]['OperatingSystem'][0])){
				$status['OperatingSystem']= $db->getEscapedString(trim($data[$i]['operatingsystem'][0]));
			}else{
				$status['OperatingSystem']= "";
			}
			if(isset($data[$i]['OperatingSystemVersion'][0])){
				$status['OperatingSystemVersion']= $db->getEscapedString(trim($data[$i]['operatingsystemversion'][0]));
			}else{
				$status['OperatingSystemVersion']= "";
			}
			$status['CommonName']= $db->getEscapedString(trim($data[$i]['cn'][0]));
			$status['DistinguishedName']= $db->getEscapedString(trim($data[$i]['distinguishedname'][0]));
			$status['OrgName']= $db->getEscapedString(trim($desc));
			if(!isset($size) || $size==1){
				$status['IP']= "";
				$db->insert($table, $status);
				$count = $count + 1;
			}else{
				for($j=0;$j<$size-1;$j++){
					$status['IP']= $db->getEscapedString(trim($ips[$j]));
					$db->insert($table, $status);
					$count = $count + 1;
				}
			}
		}
		$nowTime = date("Y-m-d H:i:s");
		echo "The ".$count." records have been inserted or updated into the ad_computer_list on ".$nowTime."\n\r<br>";
		$status = 200;
	}else{
		echo "No target-data \n\r<br>";
		$status = 400;
	}
}	
ldap_close($ldapconn);

$table = "api_list"; // 設定你想查詢資料的資料表
$condition = "class LIKE 'AD' and name LIKE '個人電腦清單' ";
$api_list = $db->query($table, $condition, $order_by = "1", $fields = "*", $limit = "");
$table = "api_status"; // 設定你想新增資料的資料表
$data_array['api_id'] = $api_list[0]['id'];
$data_array['url'] = "";
$data_array['status'] = $status;
$data_array['data_number'] = $count;
$data_array['last_update'] = $nowTime;
$db->insert($table, $data_array);
