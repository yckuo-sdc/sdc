<?php 
$pa = new PaloAltoAPI();
$data = $pa->GetLogList($log_type = 'threat', $dir = 'backward', $nlogs = 10, $skip = 0, $query = '');

$report_type = 'predefined';
$report_name = 'top-destination-countries';	
$res = $pa->GetReportList($report_type, $report_name);
$country_xml = simplexml_load_string($res) or die("Error: Cannot create object");
$max_count = 10;
$max_bytes = 0;
$max_sessions = 0;
$count = 0;
foreach($country_xml->result->entry as $log){
	if($count >= $max_count){
		break;
	}elseif($count == 0){
		$max_sessions = $log->sessions;
	}	
	if( ($log->bytes - $max_bytes) > 0){
		$max_bytes = $log->bytes;
	}
	$count = $count + 1;
}

require 'view/header/default.php'; 
require 'view/body/info/network.php';
require 'view/footer/default.php'; 
