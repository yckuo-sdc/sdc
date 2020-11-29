<?php 
$table = "application_system"; // 設定你想查詢資料的資料表
$condition = "1 = ?";
$systems = $db->query($table, $condition, $order_by = "1", $fields = "*", $limit = "", [1]);

foreach($systems as $index => $system){
    $SID = $system['SID'];
    $table = "portscanResult"; // 設定你想查詢資料的資料表
    $condition = "ScanTime = (SELECT MAX(ScanTime) FROM portscanResult WHERE SID = :SID_1) AND SID = :SID_2";
    $ports = $db->query($table, $condition, $order_by = "1", $fields = "*", $limit = "", [':SID_1'=>$SID, ':SID_2'=>$SID]);
    $size = $db->getLastNumRows();
    $systems[$index]['ports'] = $ports;
    $systems[$index]['size'] = $size;
}

require 'view/header/default.php'; 
require 'view/body/tool/nmap.php';
require 'view/footer/default.php'; 
