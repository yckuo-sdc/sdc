<?php 
$dashboad_number_array = array();

$sql = 
"SELECT 
    COUNT(*) AS number
FROM 
    security_event"; 
$dashboad_number_array['event'] = $db->execute($sql)[0]['number'];

$sql = 
"SELECT 
    SUM(number) AS number
FROM 
    scan_stats
WHERE 
    oid LIKE '2.16.886.101.90028.20002%'";
$dashboad_number_array['vul'] = $db->execute($sql)[0]['number'];

$sql = 
"SELECT 
    COUNT(*) AS number
FROM
    drip_client_list 
WHERE 
    type LIKE 'computer'";
$dashboad_number_array['client'] = $db->execute($sql)[0]['number'];

$sql = 
"SELECT 
    COUNT(*) AS number
FROM
    ncert_malicious_sites"; 
$dashboad_number_array['c2'] = $db->execute($sql)[0]['number'];

$sql = 
"SELECT 
    COUNT(*) AS number
FROM
    tndevs"; 
$dashboad_number_array['server'] = $db->execute($sql)[0]['number'];

$sql = 
"SELECT 
    COUNT(*) AS number
FROM
    apis"; 
$dashboad_number_array['api'] = $db->execute($sql)[0]['number'];

var_dump($dashboad_number_array);
