<?php 
$sql = "SELECT ou, SUM(total_VUL) AS total_VUL, SUM(fixed_VUL) AS fixed_VUL, SUM(fixed_VUL)*100.0 / NULLIF(SUM(total_VUL), 0) AS total_completion, SUM(total_high_VUL) AS total_high_VUL, SUM(fixed_high_VUL) AS fixed_high_VUL, SUM(fixed_high_VUL)*100.0 / NULLIF(SUM(total_high_VUL), 0) AS high_completion, SUM(total_VUL - overdue_high_VUL - overdue_medium_VUL) AS non_overdue_VUL, SUM(total_VUL - overdue_high_VUL - overdue_medium_VUL)*100.0 / NULLIF(SUM(total_VUL), 0) AS non_overdue_completion FROM V_VUL_tableau GROUP BY ou ORDER BY total_completion ASC";
$ou_vul = $db->execute($sql, []);

$sql = "SELECT SUM(total_VUL) AS total_VUL, SUM(fixed_VUL) AS fixed_VUL, SUM(fixed_VUL)*100.0 / NULLIF(SUM(total_VUL), 0) AS total_completion, SUM(total_high_VUL) AS total_high_VUL, SUM(fixed_high_VUL) AS fixed_high_VUL, SUM(fixed_high_VUL)*100.0 / NULLIF(SUM(total_high_VUL), 0) AS high_completion, SUM(total_VUL - overdue_high_VUL - overdue_medium_VUL) AS non_overdue_VUL, SUM(total_VUL - overdue_high_VUL - overdue_medium_VUL)*100.0 / NULLIF(SUM(total_VUL), 0) AS non_overdue_completion FROM V_VUL_tableau";
$total_vul = $db->execute($sql)[0];

$sql = "SELECT COUNT(DISTINCT ip) AS host_num FROM scanTarget";
$host_num = $db->execute($sql, [])[0]['host_num'];

$sql = "SELECT SUM(CASE domain WHEN '' THEN 0 ELSE LENGTH(domain)-LENGTH(REPLACE(domain,';',''))+1 END) AS url_num FROM scanTarget";
$url_num = $db->execute($sql, [])[0]['url_num'];
$fixed_high_VUL	= $total_vul['fixed_high_VUL'];
$total_high_VUL = $total_vul['total_high_VUL'];
$high_completion = $total_vul['high_completion'];

require 'view/header/default.php'; 
require 'view/body/info/vul.php';
require 'view/footer/default.php'; 
