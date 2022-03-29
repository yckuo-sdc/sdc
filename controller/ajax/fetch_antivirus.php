<?php
require_once __DIR__ . '/../../vendor/autoload.php';

$db = Database::get();

$file = __DIR__ . '/../../upload/antivirus/antivirus_agent_list.csv';
$count = 0;
if (($handle = fopen($file, "r")) !== FALSE) {
	$table = "antivirus_client_list";
	$key_column = "1";
	$id = "1"; 
	$db->delete($table, $key_column, $id); 
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
			$num = count($data);
			if ($num >= 70) {
                $data = mb_convert_encoding($data, "UTF-8", "BIG-5");
				$status['ClientName']= $data[1];
				$status['IP']= $data[2];
				$status['DomainLevel']= $data[4];
				$status['ConnectionState']= $data[5];
				$status['GUID']= $data[6];
				$status['ScanMethod']= $data[7];
				$status['DLPState']= $data[8];
				$status['VirusNum']= $data[13];
				$status['SpywareNum']= $data[14];
				$status['OS']= $data[19];
				$status['BitVersion']= $data[20];
				$status['MAC']= $data[21];
				$status['ClientVersion']= $data[22];
				$status['VirusPatternVersion']= $data[24];
				$status['LogonUser']= $data[62];
				
				$db->insert($table, $status);
				$count = $count + 1;							
			}
    }
    fclose($handle);
	$nowTime = date("Y-m-d H:i:s", filemtime($file));
	echo "The ".$count." records have been inserted or updated into the antivirus_clinet_list on ".$nowTime."\n\r<br>";
	$status = 200;
} else {
	echo "No target-data \n\r<br>";
	$status = 400;
}

$error = $db->getErrorMessageArray();
if(!empty($error)) {
	return;
}

$table = "apis"; // 設定你想查詢資料的資料表
$condition = "class LIKE :class and name LIKE :name";
$apis = $db->query($table, $condition, $order_by = "1", $fields = "*", $limit = "", [':class'=>'防毒軟體', ':name'=>'用戶端清單']);
$table = "api_status"; // 設定你想新增資料的資料表
$data_array = array();
$data_array['api_id'] = $apis[0]['id'];
$data_array['url'] = "";
$data_array['status'] = $status;
$data_array['data_number'] = $count;
$data_array['updated_at'] = $nowTime;
$db->insert($table, $data_array);
