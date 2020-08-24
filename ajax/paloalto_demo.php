<?php
use paloalto\api as pa;
require_once("paloalto_api.php");
require_once("paloalto_config.inc.php");
$host = '172.16.254.209';
$pa = new pa\PaloaltoAPI($host, $username, $password);
/*
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
*/

$object_type = 'ApplicationGroups'; 
$name = '';
$res = $pa->GetObjectList($object_type, $name);
//echo $res;
$res = json_decode($res);
print_r( $res->result);
//print_r( $res->result->{'@total-count'});
echo "<table border=1>";
echo "<thead>";
		echo "<tr>";
			echo "<th>名稱</th>";
			echo "<th>成員</th>";
			echo "<th>應用程式</th>";
		echo "</tr>";
echo "</thead>";
echo "<tbody>";
foreach($res->result->entry as $key => $entry){
	$size = count($entry->members->member);
	$name = $entry->{'@name'};
	foreach($entry->members->member as $key => $val){
		echo "<tr>";
		if($key == 0){
			echo "<td rowspan=".$size.">".$name."</td>";
			echo "<td rowspan=".$size.">".$size."</td>";
			echo "<td>".$val."</td>";	
		}else{
			echo "<td>".$val."</td>";	
		}
		echo "</tr>";
	}
}
echo "</tbody>";
echo "</table>";
