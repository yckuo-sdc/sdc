<?php
require_once __DIR__ . '/../../vendor/autoload.php';

$db = Database::get();
$table = "portscan_targets"; 
$aps = $db->query($table, $condition = "1 = ?", $order_by = "1", $fields = "*", $limit = "", [1]);

foreach($aps as $ap) {
	$subnet_name = $ap['name'];
	$sid = $ap['id'];
	$ipv4 = $ap['ip'];
	$ip_integer = ip2long($ipv4);
	echo $ipv4."\n";
	$output = shell_exec("/usr/bin/nmap -Pn ".$ipv4);
	$res = NmapParser($output);

    $scan_time = date('Y-m-d H:i:s'); 
	foreach($res as $v1) {
		foreach($v1 as $v2) {
			echo $v2." ";
		}
		echo "\n";
		$scan['subnet_name'] = $subnet_name;
		$scan['sid'] = $sid;
		$scan['ip_integer'] = $ip_integer;
		$scan['ipv4'] = $ipv4;
		$scan['port_number'] = $v1[0];
		$scan['protocol'] = $v1[1];
		$scan['status'] = $v1[2];
		$scan['service'] = $v1[3];
		$scan['scan_time'] = $scan_time;	

		$table = "portscan_results";
		$db->insert($table, $scan);
	}

    $table = "portscan_targets"; 
	$data_array['scan_result'] = $output;
	$key_column = "id";
	$id = $sid;
	$db->update($table, $data_array, $key_column, $id);
}
