<?php
	use paloalto\api as pa;
	require_once("paloalto_api.php");
	require_once("paloalto_config.inc.php");
	$pa = new pa\PaloaltoAPI($host, $username, $password);
	$s = $pa->GetProperty();
	print_r($s);
	$nlogs = 10;
	$log_type = "traffic";
	$dir = "backward";
	$skip = 0;
	$query = "";

	$res = $pa->GetLogList($log_type, $dir, 90, 0, $query);
	$xml = simplexml_load_string($res) or die("Error: Cannot create object");
	$job = $xml->result->job;
	echo $job;
	
	$type = "op";
	$cmd = "<show><query><result><id>$job</id><skip>0</skip></result></query></show>";
	$res = $pa->GetXmlCmdResponse($type, $cmd);
	$xml = simplexml_load_string($res) or die("Error: Cannot create object");
	#echo $res."<br>";
	
	$pa = new pa\PaloaltoAPI();
	$pa->SetProperty($s['host'],$s['apikey']);
	$type = "op";
	$cmd = "<show><query><result><id>$job</id><skip>80</skip></result></query></show>";
	$res = $pa->GetXmlCmdResponse($type, $cmd);
	$xml = simplexml_load_string($res) or die("Error: Cannot create object");
	#echo $res."<br>";

	if($xml['status'] != 'success'){
		echo "很抱歉，該分類目前沒有資料！";
		return ;
	}
	$count = 0;
	foreach($xml->result->log->logs->entry as $log){
		if($count >= 10){
			break;
		}
		echo $log->receive_time."&nbsp&nbsp";
		echo "<span style='background:#fde087'>".$log->rule."</span>&nbsp&nbsp";
		echo $log->src."&nbsp&nbsp";
		echo "<span style='background:#dddddd'>".$log->dst."</span>&nbsp&nbsp";
		echo $log->dport."&nbsp&nbsp";
		echo $log->app."&nbsp&nbsp";
		echo $log->action."&nbsp&nbsp";
		echo "<br>";
		$count = $count + 1;
	}	
?>
