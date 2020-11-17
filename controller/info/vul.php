<?php 
$sql = "SELECT ou,sum(total_VUL) as total_VUL,sum(fixed_VUL) as fixed_VUL,sum(fixed_VUL)*100.0 / NULLIF(SUM(total_VUL), 0) as total_completion,sum(total_high_VUL) as total_high_VUL, sum(fixed_high_VUL) as fixed_high_VUL,sum(fixed_high_VUL)*100.0 / NULLIF(SUM(total_high_VUL), 0) as high_completion,sum(total_VUL - overdue_high_VUL - overdue_medium_VUL) as non_overdue_VUL, sum(total_VUL - overdue_high_VUL - overdue_medium_VUL)*100.0 / NULLIF(SUM(total_VUL), 0) as non_overdue_completion	FROM V_VUL_tableau GROUP BY ou ORDER BY total_completion ASC";
$ou_vul = $db->execute($sql, []);
$sql = "SELECT sum(total_VUL) as total_VUL,sum(fixed_VUL) as fixed_VUL,sum(fixed_VUL)*100.0 / NULLIF(SUM(total_VUL), 0) as total_completion,sum(total_high_VUL) as total_high_VUL, sum(fixed_high_VUL) as fixed_high_VUL, sum(fixed_high_VUL)*100.0 / NULLIF(SUM(total_high_VUL), 0) as high_completion ,sum(total_VUL - overdue_high_VUL - overdue_medium_VUL) as non_overdue_VUL, sum(total_VUL - overdue_high_VUL - overdue_medium_VUL)*100.0 / NULLIF(SUM(total_VUL), 0) as non_overdue_completion	FROM V_VUL_tableau";
$total_vul = $db->execute($sql, []);
$sql = "SELECT COUNT(DISTINCT ip) as host_num FROM scanTarget";
$host_num = $db->execute($sql, [])[0]['host_num'];
$sql = "SELECT SUM(CASE domain WHEN '' THEN 0 ELSE LENGTH(domain)-LENGTH(REPLACE(domain,';',''))+1 END) AS url_num FROM scanTarget";
$url_num = $db->execute($sql, [])[0]['url_num'];
$fixed_high_VUL	= $total_vul[0]['fixed_high_VUL'];
$total_high_VUL = $total_vul[0]['total_high_VUL'];
$high_completion = $total_vul[0]['high_completion'];

require 'view/header/default.php'; 
require 'view/body/info/vul.php';
require 'view/footer/default.php'; 
