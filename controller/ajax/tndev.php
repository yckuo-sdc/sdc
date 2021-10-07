<?php
require_once __DIR__ .'/../../vendor/autoload.php';

$db = Database::get();

$table = "tndevs";
$entries = $db->query($table, $condition = "1 = ?", $order_by = "1", $fields = "*", $limit = "", $data_array = [1]);

$data = array();
$data['data'] = $entries;

echo json_encode($data, JSON_UNESCAPED_UNICODE);
