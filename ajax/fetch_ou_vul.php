<?php
require '../vendor/autoload.php';

$db = Database::get();

$sql = "SELECT ou,sum(total_VUL) as total_VUL,sum(fixed_VUL) as fixed_VUL FROM V_VUL_tableau GROUP BY ou ORDER BY ou desc";
$ou_vuls = $db->execute($sql,[]);
$rowcount = $db->getLastNumRows();

$sql = "SELECT ou,system_name FROM V_VUL_tableau";
$system_vuls = $db->execute($sql,[]);
$rowcount_scan = $db->getLastNumRows();

echo "共有".$rowcount."個單位,".$rowcount_scan."筆掃描設備(含歷史紀錄)！<br>";
echo "<div class='flex-container'>";                    

foreach($ou_vuls as $ou_vul) {
	$sql = "SELECT system_name,sum(total_VUL) as total_VUL ,sum(fixed_VUL) as fixed_VUL FROM(
	 SELECT system_name,'0' as total_VUL,'0' as fixed_VUL FROM scanTarget WHERE ou LIKE '/臺南市政府/".$ou_vul['ou']."' UNION ALL
	SELECT system_name,count(system_name) as total_VUL,sum(CASE WHEN status IN ('已修補','豁免','誤判') THEN 1 ELSE 0 END) fixed_VUL FROM ip_and_url_scanResult WHERE ou LIKE '/臺南市政府/".$ou_vul['ou']."' GROUP BY system_name	ORDER BY system_name
)v1 GROUP BY system_name ORDER BY system_name";
	$details = $db->execute($sql,[]);

	$sql = "SELECT * FROM scanTarget WHERE ou LIKE '/臺南市政府/".$ou_vul['ou']."'";
	$targets = $db->execute($sql,[]);
	
	echo "<div class='ou_block'>";
		echo "<span style='background-color:#fde087;font-size:3vmin'><i class='user circle icon'></i>".$ou_vul['ou']."</span>";
		echo "<div style='background-color:#009efb;text-align:center;padding:2%;line-height:1.5'>";
			echo "<h3>".$ou_vul['total_VUL']."</h3>";
			echo "<h5 style='margin: 2% 0%;'>vulnerabilities</h5>";
		echo "</div>";
		echo "<div style='background-color:#55ce63;text-align:center;padding:2%''>";
			echo "<h3>".$ou_vul['fixed_VUL']."</h3>";
			echo "<h5 style='margin: 2% 0%;'>fixed items</h5>";
		echo "</div>";
			
		foreach($details as $detail) {
			if($detail['total_VUL'] == 0) echo "<span style='color:#BBBBBB'>".$detail['system_name']."(".$detail['total_VUL']."/".$detail['fixed_VUL'].")</span><br>";
			else echo "<span>".$detail['system_name']."(".$detail['total_VUL']."/".$detail['fixed_VUL'].")</span><br>";
		}
		echo "<a><div style='text-align:right;cursor:pointer;'>Scan Target...<i class='angle double down icon'></i></div></a>";
		echo "<div class='description'>";
			echo "<ol>";
				foreach($targets as $target) {
					echo "<li>".$target['system_name']." | ".$target['ip']." | "."<a href='".$target['domain']."' target='_blank'>".$target['domain']."</a> |  ".$target['manager']." | ".$target['email']."</li>";
				}
					echo "</ol>";
		echo "</div>";
	echo "</div>";
}
echo "</div>"; //End of ou_block


//create view
/* CREATE VIEW V_VUL_tableau AS 

 SELECT oid,ou,system_name,sum(total_VUL) as total_VUL ,sum(fixed_VUL) as fixed_VUL,sum(total_high_VUL) as total_high_VUL,sum(fixed_high_VUL) as fixed_high_VUL 
 FROM(
   SELECT oid,REPLACE(ou, '/臺南市政府/', '') as ou,system_name,'0' as total_VUL,'0' as fixed_VUL,'0' as total_high_VUL,'0' as fixed_high_VUL FROM scanTarget
   UNION ALL
   SELECT OID as oid,REPLACE(ou, '/臺南市政府/', '') as ou,system_name, count(system_name) as total_VUL, sum(CASE WHEN status IN ('已修補','豁免','誤判') THEN 1 ELSE 0 END) as fixed_VUL, sum(CASE WHEN severity IN ('High','Critical') THEN 1 ELSE 0 END) as total_high_VUL, sum(CASE WHEN severity IN ('High','Critical') AND status IN ('已修補','豁免','誤判') THEN 1 ELSE 0 END) as fixed_high_VUL
		FROM (
			  SELECT OID,ou,system_name,status,severity FROM ipscanResult UNION ALL SELECT OID,ou,system_name,status,severity FROM urlscanResult
			  )A GROUP BY OID, ou, system_name
  ) v1 GROUP BY oid, ou, system_name order by oid,system_name
 */

//Alter view (insert overdue_high_VUL,overdue_medium_VUL)
/*
ALTER VIEW V_VUL_tableau AS
SELECT oid,ou,system_name,sum(total_VUL) as total_VUL ,sum(fixed_VUL) as fixed_VUL,sum(total_high_VUL) as total_high_VUL,sum(fixed_high_VUL) as fixed_high_VUL,sum(overdue_high_VUL) as overdue_high_VUL,sum(overdue_medium_VUL) as overdue_medium_VUL
FROM(
	SELECT oid,REPLACE(ou, '/臺南市政府/', '') as ou,system_name,'0' as total_VUL,'0' as fixed_VUL,'0' as total_high_VUL,'0' as fixed_high_VUL,'0' as overdue_high_VUL,'0' as overdue_medium_VUL FROM scanTarget
	UNION ALL
	SELECT OID as oid,REPLACE(ou, '/臺南市政府/', '') as ou,system_name, count(system_name) as total_VUL, sum(CASE WHEN status IN ('已修補','豁免','誤判') THEN 1 ELSE 0 END) as fixed_VUL, sum(CASE WHEN severity IN ('High','Critical') THEN 1 ELSE 0 END) as total_high_VUL, sum(CASE WHEN severity IN ('High','Critical') AND status IN ('已修補','豁免','誤判') THEN 1 ELSE 0 END) as fixed_high_VUL,sum(CASE WHEN severity IN ('High','Critical') AND status NOT IN ('已修補','豁免','誤判') AND scan_date < DATE_SUB(NOW(), INTERVAL 1 MONTH) THEN 1 ELSE 0 END) as overdue_high_VUL, sum(CASE WHEN severity IN ('Medium') AND status NOT IN ('已修補','豁免','誤判') AND scan_date < DATE_SUB(NOW(), INTERVAL 2 MONTH) THEN 1 ELSE 0 END) as overdue_medium_VUL_VUL
	FROM (																	  
		SELECT OID,ou,system_name,status,severity,scan_date FROM ipscanResult UNION ALL SELECT OID,ou,system_name,status,severity,scan_date FROM urlscanResult
	)A GROUP BY OID, ou, system_name
) v1 GROUP BY oid, ou, system_name order by oid,system_name
*/

