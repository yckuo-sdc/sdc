<?php
$limit = 10;
$links = 4;
$page = isset( $_GET['page']) ? $_GET['page'] : 1;
$query = "SELECT a.*, b.rank FROM( SELECT * FROM security_contact UNION SELECT * FROM security_contact_extra ORDER by OID asc,person_type asc )a LEFT JOIN security_rank AS b ON a.OID = b.OID";

$Paginator = new Paginator($query);
$contacts = $Paginator->getData($limit, $page);
$last_num_rows = $Paginator->getTotal();

$table = "(SELECT * FROM security_contact UNION SELECT * FROM security_contact_extra)a";
$condition = "1 = ? GROUP BY OID";
$fields = "OID";
$db->query($table, $condition, $order_by = "1" , $fields, $limit = "", [1]);
$oid_num = $db->getLastNumRows();

require 'view/header/default.php'; 
require 'view/body/query/contact.php';
require 'view/footer/default.php'; 
