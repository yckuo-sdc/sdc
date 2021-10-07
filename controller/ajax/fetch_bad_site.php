<?php
require_once __DIR__ .'/../../vendor/autoload.php';

$db = Database::get();
$pa = new PaloAltoAPI();

// Retrieve paloAlto's data
$type = "ip";
$name = "ISAC_BlackList_IP";
$num_records = 1;
$xml_type = "op";
$cmd = "<request><system><external-list><show><type><".$type."><name>".$name."</name><num-records>".$num_records."</num-records></".$type."></type></show></external-list></system></request>";
$record = $pa->getXmlCmdResponse($xml_type, $cmd);
$xml = simplexml_load_string($record) or die("Error: Cannot create object");

if($xml['status'] != 'success'){
	echo "很抱歉，該分類分頁目前沒有資料！";
	return ;
}

$total_count = $xml->result['total-count'];
$xml_type = "op";
$cmd = "<request><system><external-list><show><type><".$type."><name>".$name."</name><num-records>".$total_count."</num-records></".$type."></type></show></external-list></system></request>";
$record = $pa->getXmlCmdResponse($xml_type, $cmd);
$xml = simplexml_load_string($record) or die("Error: Cannot create object");
$members = $xml->result->{'external-list'}->{'valid-members'}->member; 

$array_ip = array();
foreach($members as $member){
	$array_ip[] = $member;
}

// Validate protection of malicious domain
$table = "ncert_malicious_sites";
$condition = "type LIKE :type";
$DNs = $db->query($table, $condition, $order_by = "1", $fields = "*", $limit = "", [':type' => 'domain']);

$time = date("Y-m-d H:i:s");
echo "DNS query on $time \n";

$dn_count = 0;
	
foreach ($DNs as $dn){
	$action = "allow";
	echo $dn['id']." ".$dn['name']."\n"; 
	
    for($i=0; $i<3; $i++) {
        $output = shell_exec("/usr/bin/dig +short ".$dn['name']." @172.16.0.251");
        if (strpos($output, 'sinkhole') !== false || strpos($output, '72.5.65.111') !== false || strpos($output, '1.1.2.100') !== false) {
            $action = "deny";
            break;
        }
    }

	echo $action."\n";

    $scan = array();
	$scan['scan_action'] = $action;
	$scan['updated_at'] = date('Y-m-d H:i:s');	

	$db->update($table, $scan, $key_column='name' , $dn['name']);
	$dn_count = $dn_count + 1;
    usleep(250000);
}		

$error = $db->getErrorMessageArray();
if(!empty($error)) {
	return;
}

$status = 200;	
$nowTime = date('Y-m-d H:i:s'); 

$table = "apis";
$condition = "class LIKE :class and name LIKE :name";
$apis = $db->query($table, $condition, $order_by = "1", $fields = "*", $limit = "", [':class'=>'惡意中繼站', ':name'=>'domain']);
$table = "api_status";
$data_array['api_id'] = $apis[0]['id'];
$data_array['url'] = $apis[0]['url'];
$data_array['status'] = $status;
$data_array['data_number'] = $dn_count;
$data_array['updated_at'] = $nowTime;
$db->insert($table, $data_array);

// Validate protection of malicious ip 
$table = "ncert_malicious_sites";
$condition = "type LIKE :type";
$IPs = $db->query($table, $condition, $order_by = "1", $fields = "*", $limit = "", [':type' => 'ip']);

$time = date("Y-m-d H:i:s");
echo "IP test on $time\n";

$ip_count = 0;
	
foreach ($IPs as $ip){
	$action = "allow";
	echo $ip['id']." ".$ip['name']."\n"; 

	if(in_array($ip['name'], $array_ip)){
		$action = "deny";
	}
	
	echo $action."\n";
	
    $scan = array();
	$scan['scan_action'] = $action;
	$scan['updated_at'] = date('Y-m-d H:i:s');	

	$db->update($table, $scan, $key_column='name' , $ip['name']);
	$ip_count = $ip_count + 1;
}

$error = $db->getErrorMessageArray();
if(!empty($error)) {
	return;
}

$status = 200;	
$nowTime = date('Y-m-d H:i:s'); 

$table = "apis";
$condition = "class LIKE :class and name LIKE :name";
$apis = $db->query($table, $condition, $order_by = "1", $fields = "*", $limit = "", [':class'=>'惡意中繼站', ':name'=>'ip']);
$table = "api_status";
$data_array = array();
$data_array['api_id'] = $apis[0]['id'];
$data_array['url'] = $apis[0]['url'];
$data_array['status'] = $status;
$data_array['data_number'] = $ip_count;
$data_array['updated_at'] = $nowTime;
$db->insert($table, $data_array);
