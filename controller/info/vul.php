<?php 
// just show tainan gov
$sql = 
"SELECT 
    oid,
    ou,
    SUM(number) AS number,
    SUM(fixed_number) AS fixed_number,
    SUM(high_risk_number) AS high_risk_number,
    SUM(fixed_high_risk_number) AS fixed_high_risk_number,
    SUM(number - overdue_high_risk_number - overdue_medium_risk_number) AS undue_number,
    IFNULL(SUM(fixed_number)*100.0 / SUM(number), 0) AS completion,
    IFNULL(SUM(fixed_high_risk_number)*100.0 / SUM(high_risk_number), 0) AS high_risk_completion,
    IFNULL(SUM(number - overdue_high_risk_number - overdue_medium_risk_number)*100.0 / SUM(number), 0) AS undue_completion 
FROM 
    scan_stats
WHERE 
    oid LIKE '2.16.886.101.90028.20002%'
GROUP BY 
    oid, ou
ORDER BY 
    completion ASC, oid ASC";
$ou_vul = $db->execute($sql, []);

$sql = 
"SELECT 
    SUM(number) AS number,
    SUM(fixed_number) AS fixed_number,
    SUM(high_risk_number) AS high_risk_number,
    SUM(fixed_high_risk_number) AS fixed_high_risk_number,
    SUM(number - overdue_high_risk_number - overdue_medium_risk_number) AS undue_number,
    IFNULL(SUM(fixed_number)*100.0 / SUM(number), 0) AS completion,
    IFNULL(SUM(fixed_high_risk_number)*100.0 / SUM(high_risk_number), 0) AS high_risk_completion,
    IFNULL(SUM(number - overdue_high_risk_number - overdue_medium_risk_number)*100.0 / SUM(number), 0) AS undue_completion 
FROM 
    scan_stats
WHERE 
    oid LIKE '2.16.886.101.90028.20002%'";
$total_vul = $db->execute($sql)[0];

$sql = 
"SELECT 
    COUNT(DISTINCT ip) AS host_num, 
    SUM(CASE domain WHEN '' THEN 0 ELSE LENGTH(domain)-LENGTH(REPLACE(domain,';',''))+1 END) AS url_num 
FROM 
    scan_targets
WHERE 
    oid LIKE '2.16.886.101.90028.20002%'";
$targets = $db->execute($sql, [])[0];
$host_num = $targets['host_num'];
$url_num = $targets['url_num'];

$fixed_high_VUL	= $total_vul['fixed_high_risk_number'];
$total_high_VUL = $total_vul['high_risk_number'];
$high_completion = $total_vul['high_risk_completion'];

require 'view/header/default.php'; 
require 'view/body/info/vul.php';
require 'view/footer/default.php'; 
