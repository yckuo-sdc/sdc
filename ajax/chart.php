<?php
if(empty($_GET['chartID'])){
	return 0;
}
$chartID = $_GET['chartID'];
require("../mysql_connect.inc.php");
switch($chartID){
	case "enews":
		$date_today = date('Y-m-d',strtotime('now'));
		$date_3month_ago = date('Y-m-d',strtotime('-3 month'));
		$sql =" SELECT OccurrenceTime,COUNT(OccurrenceTime) as count 
				FROM security_event 
				WHERE OccurrenceTime BETWEEN '".$date_3month_ago."' AND '".$date_today."'
				GROUP BY OccurrenceTime ORDER by OccurrenceTime asc";
		$result 	= mysqli_query($conn,$sql);
		$Occurrence = array();
		while($row = mysqli_fetch_assoc($result)) {
			$sql_done =" SELECT COUNT(OccurrenceTime) as count 
				FROM security_event 
				WHERE OccurrenceTime LIKE '".$row['OccurrenceTime']."' AND Status LIKE '已結案' ";
			$result_done= mysqli_query($conn,$sql_done);
			$row_done = mysqli_fetch_assoc($result_done);
			$time = date('Y-m-d',strtotime($row['OccurrenceTime']));
			$count = $row['count'];
			$donecount = $row_done['count'];
			$Occurrence[] = ['time' => $time, 'count' => $row['count'], 'donecount' => $donecount];
		}
		echo json_encode($Occurrence);
		break;
	case "ranking":
		$NowYear = date("Y");
		$LastYear = $NowYear-1;
		$sql2018 = "SELECT MONTH(OccurrenceTime) as month,COUNT(*) as count FROM security_event WHERE YEAR(OccurrenceTime) LIKE '".$LastYear."' GROUP BY MONTH(OccurrenceTime)";
		$sql2019 = "SELECT MONTH(OccurrenceTime) as month,COUNT(*) as count FROM security_event WHERE YEAR(OccurrenceTime) LIKE '".$NowYear."' GROUP BY MONTH(OccurrenceTime)";
		$result2018 = mysqli_query($conn,$sql2018);
		$result2019 = mysqli_query($conn,$sql2019);
		$LastYearEvent = array();
		$ThisYearEvent = array();
		while($row = mysqli_fetch_assoc($result2018)) {
			$LastYearEvent[] = $row;
		}
		while($row = mysqli_fetch_assoc($result2019)) {
			$ThisYearEvent[] = $row;
		}																							
		$sql = "SELECT EventTypeName as name,COUNT(EventTypeName) as count FROM security_event GROUP BY EventTypeName ORDER by count desc";
		$result = mysqli_query($conn,$sql);
		$EventType = array();
		while($row = mysqli_fetch_assoc($result)) {
			$EventType[] = $row;
		}
		$sql = "SELECT AgencyName,COUNT(AgencyName) as count FROM security_event WHERE NOT AgencyName LIKE '' GROUP BY AgencyName ORDER by count desc LIMIT 10";
		$result = mysqli_query($conn,$sql);
		$AgencyName = array();
		while($row = mysqli_fetch_assoc($result)) {
			$sql = "SELECT distinct IP FROM security_event WHERE AgencyName ='".$row['AgencyName']."'";
			$res = mysqli_query($conn,$sql);
			$IP_count	= mysqli_num_rows($res);
			$name = explode("_", $row['AgencyName']);
			$AgencyName[] = ['name' => $name[1], 'count' => $row['count'], 'IP_count' => $IP_count];
		}
		$sql = "SELECT IP as name,COUNT(IP) as count FROM security_event WHERE IP NOT LIKE '' GROUP BY IP ORDER by count desc LIMIT 10";
		$result = mysqli_query($conn,$sql);
		$DestIP = array();
		while($row = mysqli_fetch_assoc($result)) {
			$DestIP[] = $row;
		}
		$res = ['LastYearEvent' => $LastYearEvent, 'ThisYearEvent' => $ThisYearEvent, 'EventType' => $EventType, 'AgencyName' => $AgencyName, 'DestIP' => $DestIP ];
		echo json_encode($res);
		break;
	case "client":
		$sql = "SELECT DetectorName as name,COUNT(DetectorName) as count FROM drip_client_list GROUP BY DetectorName ORDER by count desc";
		$result = mysqli_query($conn,$sql);
		$DrIP = array();
		while($row = mysqli_fetch_assoc($result)) {
			$DrIP[] = $row;
		}
		$sql = "SELECT COUNT(ID) as total_count,SUM(CASE WHEN GsAll_2 = GsAll_1 THEN 1 ELSE 0 END) as pass_count FROM gcb_client_list";
		$result = mysqli_query($conn,$sql);
		$GCBPass = array();
		while($row = mysqli_fetch_assoc($result)) {
			$GCBPass[] = $row;
		}
		$sql = "SELECT b.name as name, COUNT(b.name) as count FROM gcb_client_list as a LEFT JOIN gcb_os as b ON a.OSEnvID = b.id GROUP BY b.name ORDER by count desc";
		$result = mysqli_query($conn,$sql);
		$OSEnv = array();
		while($row = mysqli_fetch_assoc($result)) {
			$OSEnv[] = $row;
		}
		$res = ['DrIP' => $DrIP, 'GCBPass' => $GCBPass, 'OSEnv' => $OSEnv];
		echo json_encode($res);
		break;
	case "network":
		require_once("paloalto_api.php");
		require_once("paloalto_config.inc.php");
		$pa = new paloalto\api\PaloaltoAPI($host, $username, $password);
		$report_map = ['top-applications', 'top-attacks', 'top-denied-applications'];
		echo "<h3></h3>"; 
		echo "<?xml version=\"1.0\"?>";
		echo "<data>";
		foreach($report_map as $report_name){	
			$report_type = 'predefined';
			$res = $pa->GetReportList($report_type, $report_name);
			$xml = simplexml_load_string($res) or die("Error: Cannot create object");
			$max_count = 10;
			$count = 0;
			foreach($xml->result->entry as $log){
				if($count >= $max_count){
					break;
				}
				echo "<".$report_name.">";
					foreach($log as $key => $val){
						echo "<".$key.">".$val."</".$key.">";
					}
				echo "</".$report_name.">";
				$count = $count + 1;
			}
		}
		echo "</data>";
		break;
}
$conn->close();
