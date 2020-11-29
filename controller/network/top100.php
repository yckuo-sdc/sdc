<?php
$table = "top_source_traffic";
$condition = "location = :location";
$order_by = "id ASC";

$data_array = [':location' => 'yonghua'];
$yh_entries = $db->query($table, $condition, $order_by, $fields = "*", $limit = "", $data_array);
$yh_max_bytes = $yh_entries[0]['bytes'];

$data_array = [':location' => 'minjhih'];
$mj_entries = $db->query($table, $condition, $order_by, $fields = "*", $limit = "", $data_array);
$mj_max_bytes = $mj_entries[0]['bytes'];

$sql = "SELECT * FROM api_status WHERE api_id IN(SELECT id FROM api_list WHERE class ='網路流量' AND name = '流量來源排名') AND status = 200 ORDER BY id DESC LIMIT 1";
$api = $db->execute($sql, [])[0];

require 'view/header/default.php'; 
require 'view/body/network/top100.php';
require 'view/footer/default.php'; 
