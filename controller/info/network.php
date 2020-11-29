<?php 
$pa = new PaloAltoAPI();
$data = $pa->getLogList($log_type = 'threat', $dir = 'backward', $nlogs = 10, $skip = 0, $query = '');

$report_type = 'predefined';
$report_name = 'top-destination-countries';	
$res = $pa->getReportList($report_type, $report_name);
$country_xml = simplexml_load_string($res) or die("Error: Cannot create object");

$max_count = 10;
$count = 0;
$entries = array();
$bytes_array = array();
$sessions_array = array();

foreach($country_xml->result->entry as $log){
	if($count >= $max_count){
		break;
	}
    $entries[$count]['dstloc'] = strval($log->dstloc); 
    $entries[$count]['bytes'] = intval($log->bytes); 
    $entries[$count]['sessions'] = intval($log->sessions); 
    $bytes_array[$count] = intval($log->bytes);
    $sessions_array[$count] = intval($log->sessions);
	$count = $count + 1;
}

$max_bytes = max($bytes_array);
$max_sessions = max($sessions_array);

require 'view/header/default.php'; 
require 'view/body/info/network.php';
require 'view/footer/default.php'; 
