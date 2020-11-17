<?php
use gcb\api as gcb;
require 'gcb_api.php';
require '../vendor/autoload.php';

$api_key = "u3mOZuf8lvZYps210BD5vA";  // the api key of sdc-iss
$token = gcb\get_access_token($api_key);
$response = gcb\get_client_list($token,$limit = 0);  // '0' represent no limit 
$db = Database::get();

$count = 0;
if(($data = json_decode($response,true)) == true){
	foreach($data as $key1 => $value1) {
		if(is_array($value1)){
			$table = "gcb_client_list";
			$key_column = "1";
			$id = "1"; 
			$db->delete($table, $key_column, $id); 
			foreach ($value1 as $key2 => $value2) {
				$client['ExternalIP']= $value2['ExternalIP'];
				$client['GsAll_0']= isset($value2['GsAll'][0]) ? $value2['GsAll'][0] : "null";
				$client['GsAll_1']= isset($value2['GsAll'][1]) ? $value2['GsAll'][1] : "null";
				$client['GsAll_2']= isset($value2['GsAll'][2]) ? $value2['GsAll'][2] : "null";
				$client['GsExcTot']= $value2['GsExcTot'];
				$client['GsID']= $value2['GsID'];
				$client['GsSetDeployID']= $value2['GsSetDeployID'];
				$client['GsStat']= $value2['GsStat'];
				$client['GsUpdatedAt']= $value2['GsUpdatedAt'];
				$client['ID']= $value2['ID'];
				$client['IEEnvID']= $value2['IEEnvID'];
				$client['InternalIP']= $value2['InternalIP'];
				$client['IsOnline']= $value2['IsOnline'];
				$client['Name']= $value2['Name'];
				$client['OSEnvID']= $value2['OSEnvID'];
				$client['OrgName']= $value2['OrgName'];
				$client['Owner']= $value2['AssocOwner'];
				$client['UserName']= $value2['UserName'];

				$db->insert($table, $client);
				$count = $count + 1;
			}
			$nowTime = date("Y-m-d H:i:s");
			echo "The ".$count." records have been inserted or updated into the gcb_clinet_list on ".$nowTime."\n\r<br>";
		}else{
			echo "=>".$value1."\n";
		}
	}
	$status = 200;
}else{
	echo "No target-data \n\r<br>";
	$nowTime = date("Y-m-d H:i:s");
	$status = 400;
}

$error = $db->getErrorMessageArray();
if(@count($error) > 0) {
	return;
}

$table = "api_list"; // 設定你想查詢資料的資料表
$condition = "class LIKE :class and name LIKE :name";
$api_list = $db->query($table, $condition, $order_by = "1", $fields = "*", $limit = "", [':class'=>'GCB', ':name'=>'用戶端清單']);
$table = "api_status"; // 設定你想新增資料的資料表
$data_array['api_id'] = $api_list[0]['id'];
$data_array['url'] = "";
$data_array['status'] = $status;
$data_array['data_number'] = $count;
$data_array['last_update'] = $nowTime;
$db->insert($table, $data_array);
