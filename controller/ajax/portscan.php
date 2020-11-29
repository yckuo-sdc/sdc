<?php
require_once 'vendor/autoload.php';

$db = Database::get();

$table = "application_system"; // 設定你想查詢資料的資料表
$aps = $db->query($table, $condition = "1 = ?", $order_by = "1", $fields = "*", $limit = "", [1]);

foreach($aps as $ap) {
	$SubnetName = $ap['Name'];
	$SID = $ap['SID'];
	$IPv4 = $ap['IP'];
	$IPInteger = ip2long($IPv4);
	echo $IPv4."\n";
	$output = shell_exec("/usr/bin/nmap -Pn ".$IPv4);
	$res = NmapParser($output);
	//print_r($res);
	foreach($res as $v1){
		foreach($v1 as $v2){
			echo $v2." ";
		}
		echo "\n";
		$scan['SubnetName'] = $SubnetName;
		$scan['SID'] = $SID;
		$scan['IPInteger'] = $IPInteger;
		$scan['IPv4'] = $IPv4;
		$scan['PortNumber'] = $v1[0];
		$scan['Protocol'] = $v1[1];
		$scan['Status'] = $v1[2];
		$scan['Service'] = $v1[3];
		$scan['ScanTime'] = date('Y-m-d h:i:s');	

		$table = "portscanResult"; // 設定你想新增資料的資料表
		$db->insert($table, $scan);
	}
	$table = "application_system";
	$data_array['Scan_Result'] = $output;
	$key_column = "SID";
	$id = $SID;
	$db->update($table, $data_array, $key_column, $id);
}
