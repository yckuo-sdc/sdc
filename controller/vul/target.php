<?php 
$table = "scan_targets"; 
$condition = "1 = ?";
$order_by = "oid";
$scanTarget = $db->query($table, $condition, $order_by, $fields = "*", $limit = "", [1]);
$last_num_rows = $db->getLastNumRows();

$sql = "SELECT COUNT(DISTINCT ip) as host_num FROM scan_targets";
$host_num = $db->execute($sql)[0]['host_num'];
$sql = "SELECT SUM(CASE domain WHEN '' THEN 0 ELSE LENGTH(domain)-LENGTH(REPLACE(domain,';',''))+1 END) AS url_num FROM scan_targets";
$url_num = $db->execute($sql)[0]['url_num'];

$sql = 
"SELECT 
    *,
    DATEDIFF(valid_to_time, CURDATE()) AS date_diff
FROM 
    scan_target_web_certificates AS a 
INNER JOIN 
    scan_targets AS b 
ON
    a.scan_target_id = b.id
WHERE 
    valid_to_time <= (NOW() + INTERVAL 1 MONTH) OR status != 'Success'
ORDER BY
    oid";
$web_cert_failures = $db->execute($sql);

$failure_num = $db->getLastNumRows();

$sql = 
"SELECT 
    *
FROM 
    scan_target_redirect_to_https AS a 
INNER JOIN 
    scan_targets AS b 
ON
    a.scan_target_id = b.id
WHERE 
    valid = 0 
ORDER BY
    oid";
$redirect_to_https_failures = $db->execute($sql);
$redirect_to_https_failure_num = $db->getLastNumRows();

require 'view/header/default.php'; 
require 'view/body/vul/target.php';
require 'view/footer/default.php'; 
