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
    system_living,
    SUM(number) AS number,
    SUM(fixed_number) AS fixed_number
FROM 
    scan_stats
WHERE 
    oid LIKE :oid
GROUP BY
    system_name, system_living
ORDER BY 
    system_living DESC, system_name ASC";

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
