<?php
$path = "/var/www/html/utility/google-api-php-client-2.2.2/";
require $path.'vendor/autoload.php';  // include your composer dependencies(google api library) 
require '../libraries/Database.php';

$db = Database::get();
$client = new Google_Client(); 
$client->setApplicationName('mytest');
$client->useApplicationDefaultCredentials();

$client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
$client->setAccessType('offline');
$client->setAuthConfig($path.'My Project for google sheet-3d2d6667b843.json');  //這邊要設定的是你下載下來的金鑰檔

$sheets = new \Google_Service_Sheets($client);
$data = [];
$currentRow = 2;
								
$spreadsheetId = '1bb9zyNHfuwQanSdutcyh2fj1JsddsXmG9JCUC5NwPNE';
///測試用的範圍，可以填入試算表名字和 column、row
// The range of A2:H will get columns A through H and all rows starting from row 2
$range = '資安事件IP列表(請統一填寫於此)!A2:Q';
$rows = $sheets->spreadsheets_values->get($spreadsheetId, $range, ['majorDimension' => 'ROWS']);
$count = 0;

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
	$table = "security_event";
	$key_column = "1";
	$id = "1"; 
	$db->delete($table, $key_column, $id); 
	foreach ($rows['values'] as $row){
	// If first column is empty, consider it an empty row and skip (this is just for example)
		if (empty($row[0])){
			break;						
		}
		
		//filter non-exist the data(ex: $row[15] is not exist)
		for($i=0;$i<17;$i++){
			$row[$i] = isset($row[$i]) ? $row[$i] : "";
			$row[$i]= $db->getEscapedString($row[$i]);
		}
		//change the format of date
		$row[2] = str_replace(".","-",$row[2]);

		$event['EventID'] = $row[0];
		$event['Status'] = $row[1];
		$event['OccurrenceTime'] = $row[2];
		$event['EventTypeName'] = $row[3];
		$event['Location'] = $row[4];
		$event['IP'] = $row[5];
		$event['BlockReason'] = $row[6];
		$event['DeviceTypeName'] = $row[7];
		$event['DeviceOwnerName'] = $row[8];
		$event['DeviceOwnerPhone'] = $row[9];
		$event['AgencyName'] = $row[10];
		$event['UnitName'] = $row[11];
		$event['NetworkProcessContent'] = $row[12];
		$event['MaintainProcessContent'] = $row[13];
		$event['AntivirusProcessContent'] = $row[14];
		$event['UnprocessedReason'] = $row[15];
		$event['Remarks'] = $row[16];
		
		$db->insert($table, $event);
		$count = $count + 1;							
	}	

	$nowTime = date("Y-m-d H:i:s");
	$status = 200;	
	$url ="https://docs.google.com/spreadsheets/d/1bb9zyNHfuwQanSdutcyh2fj1JsddsXmG9JCUC5NwPNE/edit#gid=451325607";
	echo "update ".$count." records on ".$nowTime."<br>";

	$table = "api_list"; // 設定你想查詢資料的資料表
	$condition = "class LIKE '資安事件' and name LIKE '本府資安事件' ";
	$api_list = $db->query($table, $condition, $order_by = "1", $fields = "*", $limit = "");
	$table = "api_status"; // 設定你想新增資料的資料表
	$data_array['api_id'] = $api_list[0]['id'];
	$data_array['url'] = $url;
	$data_array['status'] = $status;
	$data_array['data_number'] = $count;
	$data_array['last_update'] = $nowTime;
	$db->insert($table, $data_array);
}
	
