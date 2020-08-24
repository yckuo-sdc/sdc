<?php
//mysql
require("../mysql_connect.inc.php");
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

//select row_number,and other field value
$sql = "SELECT ou,sum(total_VUL) as total_VUL,sum(fixed_VUL) as fixed_VUL FROM V_VUL_tableau GROUP BY ou ORDER BY ou desc";
$result = mysqli_query($conn,$sql);
$rowcount = mysqli_num_rows($result);
$sql_scan = "SELECT ou,system_name FROM V_VUL_tableau";
$result_scan = mysqli_query($conn,$sql_scan);
$rowcount_scan = mysqli_num_rows($result_scan);
echo "共有".$rowcount."個單位,".$rowcount_scan."筆掃描設備(含歷史紀錄)！<br>";
echo "<div class='flex-container'>";                    
while($row = mysqli_fetch_assoc($result)) {
	$sql_s = "SELECT system_name,sum(total_VUL) as total_VUL ,sum(fixed_VUL) as fixed_VUL FROM(
	 SELECT system_name,'0' as total_VUL,'0' as fixed_VUL FROM scanTarget WHERE ou LIKE '/臺南市政府/".$row['ou']."' UNION ALL
	SELECT system_name,count(system_name) as total_VUL,sum(CASE WHEN status IN ('已修補','豁免','誤判') THEN 1 ELSE 0 END) fixed_VUL FROM ip_and_url_scanResult WHERE ou LIKE '/臺南市政府/".$row['ou']."' GROUP BY system_name	ORDER BY system_name
)v1 GROUP BY system_name ORDER BY system_name";

	$result_s = mysqli_query($conn,$sql_s);
	$sql_d = "SELECT * FROM scanTarget WHERE ou LIKE '/臺南市政府/".$row['ou']."'";
	$result_d = mysqli_query($conn,$sql_d);
	echo "<div class='ou_block'>";
		echo "<span style='background-color:#fde087;font-size:3vmin'><i class='user circle icon'></i>".$row['ou']."</span>";
		echo "<div style='background-color:#009efb;text-align:center;padding:2%;line-height:1.5'>";
			echo "<h3>".$row['total_VUL']."</h3>";
			echo "<h5 style='margin: 2% 0%;'>vulnerabilities</h5>";
		echo "</div>";
		echo "<div style='background-color:#55ce63;text-align:center;padding:2%''>";
			echo "<h3>".$row['fixed_VUL']."</h3>";
			echo "<h5 style='margin: 2% 0%;'>fixed items</h5>";
		echo "</div>";
			
		while($row_s = mysqli_fetch_assoc($result_s)) {
			//echo $row_s['system_name']."(".$row_s['total_VUL']."/".$row_s['fixed_VUL'].")<br>";
			if($row_s['total_VUL'] == 0) echo "<span style='color:#BBBBBB'>".$row_s['system_name']."(".$row_s['total_VUL']."/".$row_s['fixed_VUL'].")</span><br>";
			else echo "<span>".$row_s['system_name']."(".$row_s['total_VUL']."/".$row_s['fixed_VUL'].")</span><br>";
		}
		echo "<a><div style='text-align:right;cursor:pointer;'>Scan Target...<i class='angle double down icon'></i></div></a>";
		echo "<div class='description'>";
			echo "<ol>";
				while($row_d = mysqli_fetch_assoc($result_d)) {
					//echo "<li>".$row_d['system_name']." | <a href='".$row_d['url']."' target='_blank'>".$row_d['vitem_name']."</a> |  ".date_format(new DateTime($row_d['scan_date']),'Y-m-d')."</li>";
					echo "<li>".$row_d['system_name']." | ".$row_d['ip']." | "."<a href='".$row_d['domain']."' target='_blank'>".$row_d['domain']."</a> |  ".$row_d['manager']." | ".$row_d['email']."</li>";
				}
					echo "</ol>";
		echo "</div>";
	echo "</div>";
}
echo "</div>"; //End of ou_block
$conn->close();
