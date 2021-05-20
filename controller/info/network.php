<?php 
$pa = new PaloAltoAPI();

// $policy_data = $pa->getPoliciesList($policy_type = 'SecurityRules', $name = '');
// print $policy_data;

$threat_data = $pa->getLogList($log_type = 'threat', $dir = 'backward', $nlogs = 10, $skip = 0, $query = '');
$report_data = $pa->getSyncReport($report_type = 'predefined', $report_name = 'top-destination-countries');

$max_count = 10;
$count = 0;
$entries = array();
$bytes_array = array();
$sessions_array = array();

foreach($report_data['logs'] as $log){
	if($count >= $max_count){
		break;
	}
    $entries[$count]['dstloc'] = $log['dstloc']; 
    $entries[$count]['bytes'] = $log['bytes']; 
    $entries[$count]['sessions'] = $log['sessions']; 
    $bytes_array[$count] = $log['bytes'];
    $sessions_array[$count] = $log['sessions'];
	$count = $count + 1;
}

if(!empty($bytes_array)){
    $max_bytes = max($bytes_array);
}
if(!empty($sessions_array)){
    $max_sessions = max($sessions_array);
}

require 'view/header/default.php'; 
require 'view/body/info/network.php';
require 'view/footer/default.php'; 
