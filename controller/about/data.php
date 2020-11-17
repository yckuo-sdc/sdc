<?php 
$table = "api_list";
$condition = "1 = ?";
$api_list = $db->query($table, $condition, $order_by = "1", $fields = "*", $limit = "", [1]);

require 'view/header/default.php'; 
require 'view/body/about/data.php';
require 'view/footer/default.php'; 
