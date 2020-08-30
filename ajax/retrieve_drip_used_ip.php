<?php
require '../libraries/Database.php';
$db = Database::get();

$file = "/var/www/html/sdc/upload/upload_drip/IP_MAC_USED_IP_List.csv";
$row = 1;
$count = 0;
if (($handle = fopen($file, "r")) !== FALSE) {
	$table = "drip_ip_mac_used_list";
	$key_column = "1";
	$id = "1"; 
	$db->delete($table, $key_column, $id); 
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
		$num = count($data);
		$row++;
		if($num>=44 && $data[3]!='' && $data[3]!=''){
			$status['isOnline']= $db->getEscapedString(trim($data[0]));
			$status['DetectorName']= $db->getEscapedString(trim($data[2]));
			$status['DetectorIP']= $db->getEscapedString(trim($data[29]));
			$status['DetectorGroup']= $db->getEscapedString(trim($data[3]));
			$status['IP']= $db->getEscapedString(trim($data[4]));
			$status['MAC']= $db->getEscapedString(trim($data[5]));
			$status['IP_Block']= $db->getEscapedString(trim($data[6]));
			$status['MAC_Block']= $db->getEscapedString(trim($data[7]));
			$status['GroupName']= $db->getEscapedString(trim($data[8]));
			$status['ClientName']= $db->getEscapedString(trim($data[9]));
			$status['SwitchIP']= $db->getEscapedString(trim($data[12]));
			$status['SwitchName']= $db->getEscapedString(trim($data[13]));
			$status['PortName']= $db->getEscapedString(trim($data[14]));
			$status['NICProductor']= $db->getEscapedString(trim($data[11]));
			$status['LastOnlineTime']= $db->getEscapedString(trim($data[23]));
			$status['LastOfflineTime']= $db->getEscapedString(trim($data[24]));
			$status['IPMAC_Bind']= $db->getEscapedString(trim($data[25]));
			$status['IP_Grant']= $db->getEscapedString(trim($data[31]));
			$status['MAC_Grant']= $db->getEscapedString(trim($data[32]));
			$status['IP_BlockReason']= $db->getEscapedString(trim($data[41]));
			$status['MAC_BlockReason']= $db->getEscapedString(trim($data[42]));
			$status['MemoByMAC']= $db->getEscapedString(trim($data[44]));
			$status['MemoByIP']= $db->getEscapedString(trim($data[45]));
			
			$db->insert($table, $status);
			$count = $count + 1;							
		}
    }
    fclose($handle);
	$nowTime = date("Y-m-d H:i:s");
	echo "The ".$count." records have been inserted or updated into the drip_clinet_list on ".$nowTime."\n\r<br>";
	$status = 200;
}else{
	echo "No target-data \n\r<br>";
	$status = 400;
}

$table = "api_list"; // 設定你想查詢資料的資料表
$condition = "class LIKE 'IP管理' and name LIKE '使用IP清單' ";
$api_list = $db->query($table, $condition, $order_by = "1", $fields = "*", $limit = "");
$table = "api_status"; // 設定你想新增資料的資料表
$data_array['api_id'] = $api_list[0]['id'];
$data_array['url'] = "";
$data_array['status'] = $status;
$data_array['data_number'] = $count;
$data_array['last_update'] = $nowTime;
$db->insert($table, $data_array);
