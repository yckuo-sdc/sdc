<?php 
$table = "portscan_targets"; 
$condition = "1 = ?";
$systems = $db->query($table, $condition, $order_by = "1", $fields = "*", $limit = "", [1]);

foreach($systems as $index => $system){
    $sid = $system['id'];
    $table = "portscan_results";
    $condition = "sid = :sid_1 AND scan_time = (SELECT MAX(scan_time) FROM portscan_results WHERE sid = :sid_2)";
    $ports = $db->query($table, $condition, $order_by = "1", $fields = "*", $limit = "", [':sid_1'=>$sid, ':sid_2'=>$sid]);
    $size = $db->getLastNumRows();
    $systems[$index]['ports'] = $ports;
    $systems[$index]['size'] = $size;
}

$target = "localhost vision.tainan.gov.tw";

require 'view/header/default.php'; 
require 'view/body/tool/nmap.php';
require 'view/footer/default.php'; 
