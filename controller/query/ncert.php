<?php 
$limit = 10;
$links = 4;
$page = isset( $_GET['page']) ? $_GET['page'] : 1;
$query = "SELECT * FROM security_ncert ORDER BY IncidentID DESC";

$Paginator = new Paginator($query);
$incidents = $Paginator->getData($limit, $page);
$last_num_rows = $Paginator->getTotal();

require 'view/header/default.php'; 
require 'view/body/query/ncert.php';
require 'view/footer/default.php'; 
