<?php 
$table = "scanTarget"; 
$condition = "1 = ?";
$order_by = "ou";
$scanTarget = $db->query($table, $condition, $order_by, $fields = "*", $limit = "", [1]);
$last_num_rows = $db->getLastNumRows();

$sql = "SELECT COUNT(DISTINCT ip) as host_num FROM scanTarget";
$host_num = $db->execute($sql)[0]['host_num'];
$sql = "SELECT SUM(CASE domain WHEN '' THEN 0 ELSE LENGTH(domain)-LENGTH(REPLACE(domain,';',''))+1 END) AS url_num FROM scanTarget";
$url_num = $db->execute($sql)[0]['url_num'];

require 'view/header/default.php'; 
require 'view/body/vul/target.php';
require 'view/footer/default.php'; 
