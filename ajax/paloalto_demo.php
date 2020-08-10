<?php
	use paloalto\api as pa;
	require_once("paloalto_api.php");
	require_once("paloalto_config.inc.php");
	$host = '10.6.2.102';
	$pa = new pa\PaloaltoAPI($host, $username, $password);
	$s = $pa->GetProperty();
	$nlogs = 10;
	$log_type = "traffic";
	$dir = "backward";
	$skip = 0;
	$query = "";

	$res = $pa->GetLogList($log_type, $dir, $nlogs, 0, $query);
	$xml = simplexml_load_string($res) or die("Error: Cannot create object");
	$job = $xml->result->job;
	
	$type = "op";
	$cmd = "<show><query><result><id>$job</id><skip>0</skip></result></query></show>";
	$res = $pa->GetXmlCmdResponse($type, $cmd);
	$xml = simplexml_load_string($res) or die("Error: Cannot create object");
	#echo $res."<br>";

	$report_type = 'predefined';
	$report_name = 'top-applications';
	$res = $pa->GetReportList($report_type, $report_name);
	$xml = simplexml_load_string($res) or die("Error: Cannot create object");
	$count = 0;
	foreach($xml->result->entry as $log){
		if($count >= 10){
			break;
		}
		foreach($log as $key => $val){
			echo "$key:$val ";	
		}
		echo "<br>";
		$count = $count + 1;
	}

	$report_type = 'predefined';
	$report_name = 'top-attacks';
	$res = $pa->GetReportList($report_type, $report_name);
	$xml = simplexml_load_string($res) or die("Error: Cannot create object");
	$count = 0;
	print_r($xml);
	foreach($xml->result->entry as $log){
		if($count >= 10){
			break;
		}
		foreach($log as $key => $val){
			echo "$key:$val ";	
		}
		echo "<br>";
		$count = $count + 1;
	}
?>
