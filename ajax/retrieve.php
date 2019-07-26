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
$spreadsheetId = '1bb9zyNHfuwQanSdutcyh2fj1JsddsXmG9JCUC5NwPNE';
///測試用的範圍，可以填入試算表名字和 column、row
// The range of A2:H will get columns A through H and all rows starting from row 2

$range = '資安事件IP列表(請統一填寫於此)!A2:Q';
$rows = $sheets->spreadsheets_values->get($spreadsheetId, $range, ['majorDimension' => 'ROWS']);


//mysql
require("../mysql_connect.inc.php");
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
 }
$conn->query('SET NAMES UTF8');

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

//echo "本周開始日：".date('Y-m-d',$date_from_week)."\n\r";
//echo "本周結束日：".date('Y-m-d',$date_to_week)."\n\r";
//echo "本月開始日：".date('Y-m-d',$date_from_month)."\n\r";
//echo "本月結束日：".date('Y-m-d',$date_to_month)."\n\r";

$count = 0;
$num_undone_entry = 0;
$num_exception_entry = 0;
$num_thisMonth_entry = 0;
$num_thisWeek_entry = 0;

$result = array();


$lookup_table = array(
	"0" => "A.序號",
	"1" => "B.結案狀態",
	"2" => "C.發現日期",
	"3" => "D.資安事件類型",
	"4" => "E.位置",
	"5" => "F.電腦IP",
	"6" => "G.封鎖原因",
	"7" => "H.設備類型",
	"8" => "I.電腦所有人姓名",
	"9" => "J.電腦所有人分機",
	"10" => "K.機關",
	"11" => "L.單位",
	"12" => "M.處理日期(國眾)",
	"13" => "N.處理日期(三佑科技)",
	"14" => "O.處理日期(京祺或中華SOC)",
	"15" => "P.未能處理之原因及因應方式",
	"16" => "Q.備註說明"					
);

if (isset($rows['values'])) {
	foreach ($rows['values'] as $row){
	// If first column is empty, consider it an empty row and skip (this is just for example)
		if (empty($row[0])){
			break;						
		}
		$count = $count + 1;

		//filter non-exist the data(ex: $row[15] is not exist)
		for($i=0;$i<17;$i++){
			$row[$i] = isset($row[$i]) ? $row[$i] : "";
			//過濾特殊字元(')
			$row[$i] = str_replace("'","\'",$row[$i]);
		}
		//change the format of date
		$row[2] = str_replace(".","-",$row[2]);

		// INSERT to table ON DUPLICATE KEY UPDATE data
		$sql = "insert into security_event(EventID,Status,OccurrenceTime,EventTypeName,Location,IP,BlockReason,DeviceTypeName,DeviceOwnerName,DeviceOwnerPhone,AgencyName,UnitName,NetworkProcessContent,MaintainProcessContent,AntivirusProcessContent,UnprocessedReason,Remarks) values('$row[0]','$row[1]','$row[2]','$row[3]','$row[4]','$row[5]','$row[6]','$row[7]','$row[8]','$row[9]','$row[10]','$row[11]','$row[12]','$row[13]','$row[14]','$row[15]','$row[16]')
		ON DUPLICATE KEY UPDATE Status = '$row[1]',OccurrenceTime = '$row[2]',EventTypeName = '$row[3]',Location = '$row[4]',IP = '$row[5]',BlockReason = '$row[6]',DeviceTypeName = '$row[7]',DeviceOwnerName = '$row[8]',DeviceOwnerPhone = '$row[9]',AgencyName = '$row[10]',UnitName = '$row[11]',NetworkProcessContent = '$row[12]',MaintainProcessContent = '$row[13]',AntivirusProcessContent = '$row[14]',UnprocessedReason = '$row[15]',Remarks = '$row[16]'	";

		if ($conn->query($sql) == TRUE) {
			//echo "此筆資料已被上傳成功\n\r";									
		} else {
			echo "Error: " . $sql . "<br>" . $conn->error."<p>\n\r";
		}

		$num_total_entry = $row[0];

		//count the undone list, undone and exception list
		if($row[1] == "未完成"){
			$num_undone_entry = $num_undone_entry + 1;
			if(!empty($row[15])){
					$num_exception_entry = $num_exception_entry + 1;
			}
			//Convert date to a UNXI timestamp
			$timestamp =strtotime($row[2]); 
			if($timestamp >= $date_from_week && $timestamp <= $date_to_week){
				$num_thisWeek_entry = $num_thisWeek_entry + 1;
				//echo $row[2]."w\n\r";
			}
			if($timestamp >= $date_from_month && $timestamp <= $date_to_month){
				$num_thisMonth_entry = $num_thisMonth_entry + 1;
				//echo $row[2]."m\n\r";
			}
		}
	}	
	/*
	//這邊是設定寫入試算表的範例，在Sheet8 填入日期資料
	$updateRange = 'Sheet8!I'.$currentRow;
	$updateBody = new \Google_Service_Sheets_ValueRange([	'range' => $updateRange,
		'majorDimension' => 'ROWS',
		'values' => ['values' => date('c')],
		]);
	$sheets->spreadsheets_values->update(
	$spreadsheetId,
	$updateRange,
	$updateBody,
	['valueInputOption' => 'USER_ENTERED']
	);
	$currentRow++;*/
	/*
	foreach($lookup_table as $key => $value) {
	    echo "Key=" . $key . ", Value=" . $value;
		    echo "<br>";
	}
	*/

	$num_done_entry =$num_total_entry-$num_undone_entry  ;
	$percent_done_entry = round($num_done_entry/$num_total_entry,4)*100;

	/*
	echo "已列管=".$num_total_entry."件<br>";
	echo "未完成=".$num_undone_entry."件<br>";
	echo "已完成=".$num_done_entry."件<br>";
	echo "完成率=".$percent_done_entry."<br>";
	echo "無法完成=".$num_exception_entry."件<br>";
	echo "本周已發生=".$num_thisWeek_entry."件<br>";
	echo "本月已發生=".$num_thisMonth_entry."件<br>";
	*/

	date_default_timezone_set("Asia/Taipei");


	echo "update ".$count." records on ".date("Y-m-d H:i:s")."<br>";

}
	$conn->close();	
	//print_r($data);
?>
	
