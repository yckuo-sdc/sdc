<?php
require_once __DIR__ .'/../../vendor/autoload.php';

$db = Database::get();

$table = "tndevs";
$sql = 
"SELECT 
    A.*, CONCAT(A.edr_fireeye, ',', B.unreported_day) AS edr_fireeye_concat 
FROM 
    tndevs AS A
LEFT JOIN 
    edr_fireeyes AS B 
ON 
    INSTR(CONCAT(',', A.ip, ','), CONCAT(',', B.ip, ',')) > 0 ";
$entries = $db->execute($sql);

//$entries = $db->query($table, $condition = "1 = ?", $order_by = "1", $fields = "*", $limit = "", $data_array = [1]);

$data = array();
$data['data'] = $entries;

echo json_encode($data, JSON_UNESCAPED_UNICODE);
