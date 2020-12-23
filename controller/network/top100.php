<?php
$table = "top_source_traffic";
$condition = "location = :location";
$order_by = "id ASC";

$data_array = [':location' => 'yonghua'];
$yh_entries = $db->query($table, $condition, $order_by, $fields = "*", $limit = "", $data_array);

$data_array = [':location' => 'minjhih'];
$mj_entries = $db->query($table, $condition, $order_by, $fields = "*", $limit = "", $data_array);

$sql = "SELECT * FROM api_status WHERE api_id IN(SELECT id FROM apis WHERE class ='網路流量' AND name = '流量來源排名') AND status = 200 ORDER BY id DESC LIMIT 1";
$apis = $db->execute($sql)[0];

require 'view/header/default.php'; 
require 'view/body/network/top100.php';
require 'view/footer/default.php'; 
