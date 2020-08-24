<?php
// include your composer dependencies
$local_path = "/var/www/html/utility/google-api-php-client-2.2.2/";
// 載入 google api library
require_once $local_path.'vendor/autoload.php';
$client = new Google_Client();
$client->setApplicationName('mytest');
$client->useApplicationDefaultCredentials();


//使用 google sheets apii
$client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
$client->setAccessType('offline');
//這邊要設定的是你下載下來的金鑰檔
$client->setAuthConfig($local_path.'My Project for google sheet-3d2d6667b843.json');

//以下是建立存取 google sheets 的範

$sheets = new \Google_Service_Sheets($client);
$data = [];
$currentRow = 2;
								
// 填入要操作的試算表的 id (當然我為了保密刪掉了一小段)
$spreadsheetId = '1lr_EHFxJp0KGErFt7L1oh7n7HIIh_YZtVWH4QBZhhME';
///測試用的範圍，可以填入試算表名字和 column、row
// The range of A2:H will get columns A through H and all rows starting from row 2

$range = '臺南市政府資安攻擊成功事件清單!A2:AD';
$rows = $sheets->spreadsheets_values->get($spreadsheetId, $range, ['majorDimension' => 'ROWS']);


//mysql
require("../mysql_connect.inc.php");

// Specify the start date. This date can be any English textual format  
//Convert date to a UNXI timestamp
$date_from_week = strtotime('monday this week');  
$date_to_week = strtotime('sunday this week');
$date_from_month = strtotime('first day of this month');
$date_to_month = strtotime('last day of this month');  

$date_from_week =  strtotime(date('Y-m-d',$date_from_week));
$date_to_week = strtotime( date('Y-m-d',$date_to_week));
$date_from_month =  strtotime(date('Y-m-d',$date_from_month));
$date_to_month = strtotime(date('Y-m-d',$date_to_month));


$count = 0;
$num_undone_entry = 0;
$num_exception_entry = 0;
$num_thisMonth_entry = 0;
$num_thisWeek_entry = 0;

$result = array();


if (isset($rows['values'])) {
	foreach ($rows['values'] as $row){
	// If first column is empty, consider it an empty row and skip (this is just for example)
		if (empty($row[0])){
			break;						
		}
	

		//filter non-exist the data(ex: $row[15] is not exist)
		for($i=0;$i<30;$i++){
			$row[$i] = isset($row[$i]) ? $row[$i] : "";
			//過濾特殊字元(')
			$row[$i] = str_replace("'","\'",$row[$i]);
			$row[$i] = mysqli_real_escape_string($conn,$row[$i]);
            //filter empty values of datatime field
			if( $i >= 20 && $i <= 25){ 
				if(empty($row[$i])){
					$row[$i] = "1000-01-01 00:00:00";
				}
			}
		}
		// INSERT to table ON DUPLICATE KEY UPDATE data
		$sql = "insert into tainangov_security_Incident(IncidentID,Status,NccstID,NccstPT,NccstPTImpact,OrganizationName,ContactPerson,Tel,Email,SponsorName,PublicIP,DeviceUsage,OperatingSystem,IntrusionURL,ImpactLevel,Classification,Explaination,Evaluation,Response,Solution,OccurrenceTime,InformTime,RepairTime,TainanGovVerificationTime,NccstVerificationTime,FinishTime,InformExecutionTime,FinishExecutionTime,SOCConfirmation,ImprovementPlanTime) values('$row[0]','$row[1]','$row[2]','$row[3]','$row[4]','$row[5]','$row[6]','$row[7]','$row[8]','$row[9]','$row[10]','$row[11]','$row[12]','$row[13]','$row[14]','$row[15]','$row[16]','$row[17]','$row[18]','$row[19]','$row[20]','$row[21]','$row[22]','$row[23]','$row[24]','$row[25]','$row[26]','$row[27]','$row[28]','$row[29]')
			   ON DUPLICATE KEY UPDATE Status = '$row[1]',NccstID = '$row[2]',NccstPT = '$row[3]',NccstPTImpact = '$row[4]',OrganizationName = '$row[5]',ContactPerson = '$row[6]',Tel = '$row[7]',Email = '$row[8]',SponsorName = '$row[9]',PublicIP = '$row[10]',DeviceUsage = '$row[11]',OperatingSystem = '$row[12]',IntrusionURL = '$row[13]',ImpactLevel = '$row[14]',Classification = '$row[15]',Explaination = '$row[16]',Evaluation = '$row[17]',Response = '$row[18]',Solution = '$row[19]',OccurrenceTime = '$row[20]',InformTime = '$row[21]',RepairTime = '$row[22]',TainanGovVerificationTime = '$row[23]',NccstVerificationTime = '$row[24]',FinishTime = '$row[25]',InformExecutionTime = '$row[26]',FinishExecutionTime = '$row[27]',SOCConfirmation = '$row[28]',ImprovementPlanTime = '$row[29]' ";

		if ($conn->query($sql) == TRUE) {
			$count = $count + 1;
			//echo "此筆資料已被上傳成功\n\r";									
		} else {
			echo "Error: " . $sql . "<br>" . $conn->error."<p>\n\r";
		}

	}	

	date_default_timezone_set("Asia/Taipei");
	$nowTime= date("Y-m-d H:i:s");
	$status = 200;	
	$url 	= "https://docs.google.com/spreadsheets/d/1lr_EHFxJp0KGErFt7L1oh7n7HIIh_YZtVWH4QBZhhME/edit#gid=1460646588";
	echo "update ".$count." records on ".$nowTime."<br>";
	$sql = "SELECT * FROM api_list WHERE class LIKE '資安事件' and name LIKE '技服資安通報' ";
	$result = mysqli_query($conn,$sql);
	$row = mysqli_fetch_assoc($result);
	$sql = "INSERT INTO api_status(api_id,url,status,data_number,last_update) VALUES(".$row['id'].",'".$url."',".$status.",".$count.",'".$nowTime."')";
	if ($conn->query($sql) == TRUE){
	}else {
		echo "Error: " . $sql . "<br>" . $conn->error."<p>\n\r";
	}
}
$conn->close();	
	//print_r($data);
	
