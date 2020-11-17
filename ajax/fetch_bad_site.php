<?php
require '../vendor/autoload.php';  // include your composer dependencies(google api library) 

$db = Database::get();
$pa = new PaloAltoAPI();

$client = new Google_Client();
$client->setApplicationName('mytest');
$client->useApplicationDefaultCredentials();

$client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);  //使用 google sheets api
$client->setAccessType('offline');
$client->setAuthConfig('../config/My Project for google sheet-3d2d6667b843.json');

$sheets = new \Google_Service_Sheets($client);
$spreadsheetId = '1bb9zyNHfuwQanSdutcyh2fj1JsddsXmG9JCUC5NwPNE';  // 填入要操作的試算表的 id

/** the google sheet of 
 * nccst bad domain **/

$range = 'nccst_bad_dn!A2:D';
$rows = $sheets->spreadsheets_values->get($spreadsheetId, $range, ['majorDimension' => 'ROWS']);

if (!isset($rows['values'])) {
	return;
}

$time = date("Y-m-d H:i:s");
echo "DNS query on $time \n";

$dn_count = 0;
	
$table = "maliciousSite";
$key_column = "Type";
$type = "domain"; 
$db->delete($table, $key_column, $type);

foreach ($rows['values'] as $row){
	
	if (empty($row[0])){
		continue;						
	}

	$action = "allow";
	echo $row[0]." ".$row[1]."\n"; 
	$dn = $row[1];
	$output = shell_exec("/usr/bin/dig +short $dn @10.7.199.15");

	if (strpos($output, 'sinkhole') !== false || strpos($output, '72.5.65.111') !== false || strpos($output, '1.1.2.100') !== false) {
		$action = "deny";
	}

	echo $action."\n";
	
	$scan['Type'] = "domain";
	$scan['Name'] = $dn;
	$scan['Action'] = $action;
	$scan['LastUpdate'] = date('Y-m-d H:i:s');	

	$table = "maliciousSite";
	$db->insert($table, $scan);
	$dn_count = $dn_count + 1;
}		

$error = $db->getErrorMessageArray();
if(@count($error) > 0) {
	return;
}

$status = 200;	
$nowTime = date('Y-m-d H:i:s'); 
$url ="https://docs.google.com/spreadsheets/d/1bb9zyNHfuwQanSdutcyh2fj1JsddsXmG9JCUC5NwPNE/edit#gid=2001135399";

$table = "api_list";
$condition = "class LIKE :class and name LIKE :name";
$api_list = $db->query($table, $condition, $order_by = "1", $fields = "*", $limit = "", [':class'=>'惡意中繼站', ':name'=>'Domain']);
$table = "api_status";
$data_array['api_id'] = $api_list[0]['id'];
$data_array['url'] = $url;
$data_array['status'] = $status;
$data_array['data_number'] = $dn_count;
$data_array['last_update'] = $nowTime;
$db->insert($table, $data_array);

/** the google sheet of 
 * nccst bad ip **/

$range = 'nccst_bad_ip!A2:D';
$rows = $sheets->spreadsheets_values->get($spreadsheetId, $range, ['majorDimension' => 'ROWS']);

$type = "ip";
$name = "ISAC_BlackList_IP";
$num_records = 1;
$xml_type = "op";
$cmd = "<request><system><external-list><show><type><".$type."><name>".$name."</name><num-records>".$num_records."</num-records></".$type."></type></show></external-list></system></request>";
$record = $pa->GetXmlCmdResponse($xml_type, $cmd);
$xml = simplexml_load_string($record) or die("Error: Cannot create object");

if($xml['status'] != 'success'){
	echo "很抱歉，該分類分頁目前沒有資料！";
	return ;
}

$total_count = $xml->result['total-count'];
$xml_type = "op";
$cmd = "<request><system><external-list><show><type><".$type."><name>".$name."</name><num-records>".$total_count."</num-records></".$type."></type></show></external-list></system></request>";
$record = $pa->GetXmlCmdResponse($xml_type, $cmd);
$xml = simplexml_load_string($record) or die("Error: Cannot create object");
$members = $xml->result->{'external-list'}->{'valid-members'}->member; 

foreach($members as $member){
	$array_ip[] = $member;
}

$time = date("Y-m-d H:i:s");
echo "IP test on $time\n";

$ip_count = 0;
	
$table = "maliciousSite";
$key_column = "Type";
$type = "ip"; 
$db->delete($table, $key_column, $type);

foreach ($rows['values'] as $row){
	
	if (empty($row[0])){
		continue;						
	}

	$action = "allow";
	echo $row[0]." ".$row[1]."\n"; 
	$ip = $row[1];
	$output = shell_exec("/usr/bin/nmap $ip");

	if(in_array($ip, $array_ip)){
		$action = "deny";
	}
	
	echo $action."\n";
	
	$scan['Type'] = "ip";
	$scan['Name'] = $ip;
	$scan['Action'] = $action;
	$scan['LastUpdate'] = date('Y-m-d H:i:s');	

	$table = "maliciousSite";
	$db->insert($table, $scan);
	$ip_count = $ip_count + 1;
}

$error = $db->getErrorMessageArray();
if(@count($error) > 0) {
	return;
}

$status = 200;	
$nowTime = date('Y-m-d H:i:s'); 
$url ="https://docs.google.com/spreadsheets/d/1bb9zyNHfuwQanSdutcyh2fj1JsddsXmG9JCUC5NwPNE/edit#gid=1810016953";

$table = "api_list";
$condition = "class LIKE :class and name LIKE :name";
$api_list = $db->query($table, $condition, $order_by = "1", $fields = "*", $limit = "", [':class'=>'惡意中繼站', ':name'=>'IP']);
$table = "api_status";
$data_array['api_id'] = $api_list[0]['id'];
$data_array['url'] = $url;
$data_array['status'] = $status;
$data_array['data_number'] = $ip_count;
$data_array['last_update'] = $nowTime;
$db->insert($table, $data_array);
