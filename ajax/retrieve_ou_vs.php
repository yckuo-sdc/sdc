<?php
	//mysql
	require("../mysql_connect.inc.php");
	//create view
	/* CREATE VIEW V_VUL_tableau AS 
		SELECT REPLACE(ou, '/臺南市政府/', '') as ou, system_name, count(system_name) as total_VUL, sum(CASE WHEN status IN ('已修補','豁免','誤判') THEN 1 ELSE 0 END) fixed_VUL
	   FROM (
	   SELECT ou,system_name,status FROM ipscanResult UNION ALL SELECT ou,system_name,status FROM urlscanResult
	   )A GROUP BY ou, system_name 
	*/
	//select row_number,and other field value
	$sql = "SELECT ou,sum(total_VUL) as total_VUL,sum(fixed_VUL) as fixed_VUL FROM V_VUL_tableau GROUP BY ou ORDER BY ou desc";
	$result = mysqli_query($conn,$sql);
	$rowcount = mysqli_num_rows($result);
	echo "共有".$rowcount."筆資料！";
	echo "<div class='flex-container'>";                    
	while($row = mysqli_fetch_assoc($result)) {
		//Query of system name,count(total_VUL),count(fixed_VUL) 
		//$sql_s = "SELECT * FROM V_VUL_tableau WHERE ou LIKE '".$row['ou']."' ORDER BY system_name ";

		$sql_s = "SELECT system_name,count(system_name) as total_VUL,sum(CASE WHEN status IN ('已修補','豁免','誤判') THEN 1 ELSE 0 END) fixed_VUL 
			FROM (
				SELECT system_name,status FROM ipscanResult WHERE ou LIKE '/臺南市政府/".$row['ou']."' UNION ALL SELECT system_name,status FROM urlscanResult WHERE ou LIKE '/臺南市政府/".$row['ou']."'
			)A GROUP BY system_name	ORDER BY system_name";
		$result_s = mysqli_query($conn,$sql_s);
		//Query of unFixed vulnerabilities
		$sql_d = "SELECT * FROM(
					SELECT vitem_name,system_name,ip,scan_date,scan_no,CONCAT('http://',ip) as url FROM ipscanResult WHERE ou LIKE '/臺南市政府/".$row['ou']."' AND status IN ('待處理','待處理(經複查仍有弱點','豁免(待簽核)','誤判(待簽核)','已修補(待複檢)') UNION ALL SELECT vitem_name,system_name,ip,scan_date,scan_no,affect_url as url FROM urlscanResult WHERE ou LIKE '/臺南市政府/".$row['ou']."' AND status IN ('待處理','待處理(經複查仍有弱點)','豁免(待簽核)','誤判(待簽核)','已修補(待複檢)')
				)A ORDER BY scan_date desc LIMIT 10";
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
				echo $row_s['system_name']."(".$row_s['total_VUL']."/".$row_s['fixed_VUL'].")<br>";
			}
			echo "<a><div style='text-align:right;cursor:pointer;'>last 10 unfixed items...<i class='angle double down icon'></i></div></a>";
			echo "<div class='description'>";
				echo "<ol>";
					while($row_d = mysqli_fetch_assoc($result_d)) {
						echo "<li>".$row_d['system_name']." | <a href='".$row_d['url']."' target='_blank'>".$row_d['vitem_name']."</a> |  ".date_format(new DateTime($row_d['scan_date']),'Y-m-d')."</li>";
					}
					 	echo "</ol>";
			echo "</div>";
		echo "</div>";
	}
	echo "</div>"; //End of ou_block
	$conn->close();
?>
