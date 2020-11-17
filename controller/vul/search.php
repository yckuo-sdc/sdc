<?php 
$limit = 10;
$links = 4;
$page = isset( $_GET['page']) ? $_GET['page'] : 1;
$query = "SELECT * FROM ip_and_url_scanResult ORDER BY scan_no DESC,ou DESC,system_name DESC,status DESC";

$Paginator = new Paginator($query);
$vuls = $Paginator->getData($limit, $page);
$last_num_rows = $Paginator->getTotal();

require 'view/header/default.php'; 
require 'view/body/vul/search.php';
require 'view/footer/default.php'; 
