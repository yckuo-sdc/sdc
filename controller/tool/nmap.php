<?php 
$table = "application_system"; // 設定你想查詢資料的資料表
$condition = "1 = ?";
$systems = $db->query($table, $condition, $order_by = "1", $fields = "*", $limit = "", [1]);

require 'view/header/default.php'; 
require 'view/body/tool/nmap.php';
require 'view/footer/default.php'; 
