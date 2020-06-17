<?php
	if(!empty($_GET['chartID'])){
		//過濾特殊字元(')
		$chartID = $_GET['chartID'];
	   
		require("../mysql_connect.inc.php");
		
		switch($chartID){
			case "chartA":
				$date_today = date('Y-m-d',strtotime('now'));
				$date_3month_ago = date('Y-m-d',strtotime('-3 month'));

				$sql =" SELECT OccurrenceTime,COUNT(OccurrenceTime) as count 
						FROM security_event 
						WHERE OccurrenceTime BETWEEN '".$date_3month_ago."' AND '".$date_today."' 
						GROUP BY OccurrenceTime ORDER by OccurrenceTime asc";
				$result 	= mysqli_query($conn,$sql);
				$rowcount	= mysqli_num_rows($result);

				echo "<?xml version=\"1.0\"?>";
				echo "<data>";	
					while($row = mysqli_fetch_assoc($result)) {
						echo "<OccurrenceTime>".date('Y-m-d',strtotime($row['OccurrenceTime']))."</OccurrenceTime>";
						echo "<count>".$row['count']."</count>";
						$sql_done =" SELECT OccurrenceTime,COUNT(OccurrenceTime) as count 
							FROM security_event 
							WHERE OccurrenceTime LIKE '".$row['OccurrenceTime']."' AND Status LIKE '已結案'
							GROUP BY OccurrenceTime ORDER by OccurrenceTime asc";
						$result_done= mysqli_query($conn,$sql_done);
						$row_done = mysqli_fetch_assoc($result_done);
						echo "<OccurrenceTime_done>".date('Y-m-d',strtotime($row_done['OccurrenceTime']))."</OccurrenceTime_done>";
						echo "<count_done>".$row_done['count']."</count_done>";
					}
				echo "</data>";
				break;
			case "chartB":
				//select row_number,and other field value
				$NowYear = date("Y");
				$LastYear = $NowYear-1;
				$sql2018 = "SELECT MONTH(OccurrenceTime) as month,COUNT(*) as count FROM security_event WHERE YEAR(OccurrenceTime) LIKE '".$LastYear."' GROUP BY MONTH(OccurrenceTime)";
				$sql2019 = "SELECT MONTH(OccurrenceTime) as month,COUNT(*) as count FROM security_event WHERE YEAR(OccurrenceTime) LIKE '".$NowYear."' GROUP BY MONTH(OccurrenceTime)";
				$result2018 	= mysqli_query($conn,$sql2018);
				$result2019 	= mysqli_query($conn,$sql2019);
				$rowcount2018	= mysqli_num_rows($result2018);
				$rowcount2019	= mysqli_num_rows($result2019);
				echo "<?xml version=\"1.0\"?>";
				echo "<data>";	
					while($row = mysqli_fetch_assoc($result2018)) {
						 echo "<month2018>".$row['month']."月</month2018>";
						 echo "<count2018>".$row['count']."</count2018>";
					}
					while($row = mysqli_fetch_assoc($result2019)) {
						 echo "<month2019>".$row['month']."月</month2019>";
						 echo "<count2019>".$row['count']."</count2019>";
					}																							
				echo "</data>";
				break;
			case "chartC":
				//select row_number,and other field value
				$sql = "SELECT EventTypeName,COUNT(EventTypeName) as count FROM security_event GROUP BY EventTypeName ORDER by count desc";
				$result 	= mysqli_query($conn,$sql);
				$rowcount	= mysqli_num_rows($result);
				echo "<h3></h3>"; 
				echo "<?xml version=\"1.0\"?>";
				echo "<data>";	
					while($row = mysqli_fetch_assoc($result)) {
						echo "<EventType>";
							echo "<name>".$row['EventTypeName']."</name>";
							echo "<count>".$row['count']."</count>";
						echo "</EventType>";
					}
				$sql = "SELECT AgencyName,COUNT(AgencyName) as count FROM security_event WHERE NOT AgencyName LIKE '' GROUP BY AgencyName ORDER by count desc";
				$result 	= mysqli_query($conn,$sql);
				$rowcount	= mysqli_num_rows($result);
					while($row = mysqli_fetch_assoc($result)) {
						$sql = "SELECT distinct IP FROM security_event WHERE AgencyName ='".$row['AgencyName']."'";
						$res 	= mysqli_query($conn,$sql);
						$IP_count	= mysqli_num_rows($res);
						$name = explode("_", $row['AgencyName']);
						echo "<AgencyName>";
						echo "<name>".$name[1]."</name>";
						echo "<count>".$row['count']."</count>";
						echo "<IP_count>".$IP_count."</IP_count>";
						echo "</AgencyName>";
					}
				$sql = "SELECT UnitName,COUNT(UnitName) as count FROM security_event WHERE NOT UnitName LIKE '' GROUP BY UnitName ORDER by count desc LIMIT 10";
				$result 	= mysqli_query($conn,$sql);
				$rowcount	= mysqli_num_rows($result);
					while($row = mysqli_fetch_assoc($result)) {
						echo "<UnitName>".$row['UnitName']."</UnitName>";
						echo "<Unitcount>".$row['count']."</Unitcount>";
					}
				echo "</data>";
				break;
			case "chartD":
				//select row_number,and other field value
				$sql = "SELECT ou,(sum(total_VUL)-sum(fixed_VUL)) as unfixed_VUL,sum(total_VUL) as total_VUL,sum(fixed_VUL) as fixed_VUL FROM V_VUL_tableau WHERE ou NOT LIKE '區公所' GROUP BY ou ORDER BY total_VUL desc";
				$result 	= mysqli_query($conn,$sql);
				$rowcount	= mysqli_num_rows($result);
				echo "<h3></h3>"; 
				echo "<?xml version=\"1.0\"?>";
				echo "<data>";	
					while($row = mysqli_fetch_assoc($result)) {
						echo "<VUL>";
							echo "<ou>".$row['ou']."</ou>";
							echo "<unfixed_VUL>".$row['unfixed_VUL']."</unfixed_VUL>";
							echo "<fixed_VUL>".$row['fixed_VUL']."</fixed_VUL>";
						echo "</VUL>";
					}
				echo "</data>";
				break;
			case "chartE":
				//select row_number,and other field value
				//$sql = "SELECT b.name,COUNT(b.name) as count FROM gcb_client_list as a,gcb_os as b WHERE a.OSEnvID = b.id GROUP BY b.name ORDER by count desc";
				$sql = "SELECT b.name,COUNT(b.name) as count FROM gcb_client_list as a LEFT JOIN gcb_os as b ON a.OSEnvID = b.id GROUP BY b.name ORDER by count desc";
				$result 	= mysqli_query($conn,$sql);
				$rowcount	= mysqli_num_rows($result);
				echo "<h3></h3>"; 
				echo "<?xml version=\"1.0\"?>";
				echo "<data>";	
					while($row = mysqli_fetch_assoc($result)) {
						echo "<OSEnvID>";
							echo "<name>".$row['name']."</name>";
							echo "<count>".$row['count']."</count>";
						echo "</OSEnvID>";
					}
				
				$sql = "SELECT COUNT(ID) as total_count,SUM(CASE WHEN GsAll_2 = GsAll_1 THEN 1 ELSE 0 END) as pass_count FROM gcb_client_list";
				$result 	= mysqli_query($conn,$sql);
				$rowcount	= mysqli_num_rows($result);
					while($row = mysqli_fetch_assoc($result)) {
						echo "<gcb_pass>";
							echo "<total_count>".$row['total_count']."</total_count>";
							echo "<pass_count>".$row['pass_count']."</pass_count>";
						echo "</gcb_pass>";
					}
				$sql = "SELECT DetectorName as name,COUNT(DetectorName) as count FROM drip_client_list GROUP BY DetectorName ORDER by count desc";
				$result 	= mysqli_query($conn,$sql);
				$rowcount	= mysqli_num_rows($result);
					while($row = mysqli_fetch_assoc($result)) {
						echo "<drip_vlan>";
							echo "<name>".$row['name']."</name>";
							echo "<count>".$row['count']."</count>";
						echo "</drip_vlan>";
					}
				echo "</data>";
				break;
		}
		$conn->close();
	}
?>
