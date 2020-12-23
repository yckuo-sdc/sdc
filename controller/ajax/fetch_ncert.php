<?php
require_once 'vendor/autoload.php';

$db = Database::get();
$client = new Google_Client();  // 載入 google api library
$client->setApplicationName('mytest');
$client->useApplicationDefaultCredentials();

//使用 google sheets apii
$client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
$client->setAccessType('offline');
//這邊要設定的是你下載下來的金鑰檔
$client->setAuthConfig('config/My Project for google sheet-3d2d6667b843.json');  //這邊要設定的是你下載下來的金鑰檔

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

$count = 0;
if (isset($rows['values'])) {
	$table = "security_ncert";
	$key_column = "1";
	$id = "1"; 
	$db->delete($table, $key_column, $id); 
	foreach ($rows['values'] as $row){
	// If first column is empty, consider it an empty row and skip (this is just for example)
		if (empty($row[0])){
			break;						
		}

		//filter non-exist the data(ex: $row[15] is not exist)
		for($i=0;$i<30;$i++){
			$row[$i] = isset($row[$i]) ? $row[$i] : "";
            //filter empty values of datatime field
			if( $i >= 20 && $i <= 25){ 
				if(empty($row[$i])){
					$row[$i] = "1000-01-01 00:00:00";
				}
			}
		}

		$event['IncidentID'] = $row[0];
		$event['Status'] = $row[1];
		$event['NccstID'] = $row[2];
		$event['NccstPT'] = $row[3];
		$event['NccstPTImpact'] = $row[4];
		$event['OrganizationName'] = $row[5];
		$event['ContactPerson'] = $row[6];
		$event['Tel'] = $row[7];
		$event['Email'] = $row[8];
		$event['SponsorName'] = $row[9];
		$event['PublicIP'] = $row[10];
		$event['DeviceUsage'] = $row[11];
		$event['OperatingSystem'] = $row[12];
		$event['IntrusionURL'] = $row[13];
		$event['ImpactLevel'] = $row[14];
		$event['Classification'] = $row[15];
		$event['Explaination'] = $row[16];
		$event['Evaluation'] = $row[17];
		$event['Response'] = $row[18];
		$event['Solution'] = $row[19];
		$event['DiscoveryTime'] = $row[20];
		$event['InformTime'] = $row[21];
		$event['RepairTime'] = $row[22];
		$event['TainanGovVerificationTime'] = $row[23];
		$event['NccstVerificationTime'] = $row[24];
		$event['FinishTime'] = $row[25];
		$event['InformExecutionTime'] = $row[26];
		$event['FinishExecutionTime'] = $row[27];
		$event['SOCConfirmation'] = $row[28];
		$event['ImprovementPlanTime'] = $row[29];
		
		$db->insert($table, $event);
		$count = $count + 1;
	}	

	$error = $db->getErrorMessageArray();
	if(@count($error) > 0) {
		return;
	}
	
	$nowTime = date("Y-m-d H:i:s");
	$status = 200;	
	echo "update ".$count." records on ".$nowTime."<br>";
	
	$table = "apis"; // 設定你想查詢資料的資料表
	$condition = "class LIKE :class and name LIKE :name";
	$apis = $db->query($table, $condition, $order_by = "1", $fields = "*", $limit = "", [':class'=>'資安事件', ':name'=>'技服資安通報']);
	$table = "api_status"; // 設定你想新增資料的資料表
	$data_array['api_id'] = $apis[0]['id'];
	$data_array['url'] = $apis[0]['url'];
	$data_array['status'] = $status;
	$data_array['data_number'] = $count;
	$data_array['last_update'] = $nowTime;
	$db->insert($table, $data_array);
}
	
