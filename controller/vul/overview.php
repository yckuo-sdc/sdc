<?php
$sql = 
"SELECT 
    oid,
    ou, 
    SUM(number) AS number,
    SUM(fixed_number) AS fixed_number
FROM 
    scan_stats
GROUP BY 
    oid, ou
ORDER BY 
    oid";
$ou_vuls = $db->execute($sql,[]);
$rowcount = $db->getLastNumRows();

$sql_details = 
"SELECT 
    system_name,
    living,
    SUM(number) AS number,
    SUM(fixed_number) AS fixed_number
FROM 
    scan_stats
WHERE 
    oid LIKE :oid
GROUP BY
    system_name, living
ORDER BY 
    living DESC, system_name ASC";

$sql_targets = 
"SELECT 
    * 
FROM 
    scan_targets 
WHERE 
    oid LIKE :oid";

require 'view/header/default.php'; 
require 'view/body/vul/overview.php';
require 'view/footer/default.php'; 
