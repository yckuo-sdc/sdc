<?php
require_once __DIR__ . '/../../vendor/autoload.php';

$db = Database::get();

//fetch client and server lists
$table = "client_server_lists";
$key_column = "1";
$id = "1"; 
$db->delete($table, $key_column, $id); 

$sql = "INSERT INTO client_server_lists(type, ip, ou, name)
SELECT 'client' as type, IP, OrgName, Owner FROM drip_client_list";
$db->execute($sql);

$sql = "UPDATE client_server_lists AS A
JOIN drip_client_list AS B
ON A.ip = B.IP AND (A.name IN('', '-') OR A.name IS NULL) AND B.MemoByMAC NOT LIKE ''  
SET A.name = CONCAT('(ByMAC)', B.MemoByMAC)";
$db->execute($sql);

$url = "https://tndev.tainan.gov.tw/api/values/4";
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => $url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_SSL_VERIFYPEER => false,
  CURLOPT_SSL_VERIFYHOST => false,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_HTTPHEADER => array("Content-Type:: application/json")
));
$res = curl_exec($curl);
curl_close($curl);

$servers = json_decode($res, true);

foreach($servers as $server){
    $table = "client_server_lists";
    $data_array = array();
    $data_array['type'] = "server";
    $data_array['ip'] = $server['ipv4'];
    $data_array['ou'] = $server['ou'];
    $data_array['name'] = $server['name'];
    $db->insert($table, $data_array);
}

//drop table of top_source_traffic
$table = "top_source_traffic";
$key_column = "1";
$id = "1"; 
$db->delete($table, $key_column, $id); 

//fetch traffic report of yonghua Paloalto
$pa = new PaloAltoAPI('yonghua');
$data = $pa->getAsyncReport($report_type = 'custom', $report_name = 'Traffic_Top100_last_24hour');
//print_r($data);

$count1 = 0;
foreach($data['logs'] as $entry){
    $data_array = array();
    $data_array['id'] = ($count1 + 1);
    $data_array['location'] = 'yonghua';
    $data_array['src_ip'] = $entry['src'];
    $data_array['bytes'] = $entry['bytes'];
    $data_array['sessions'] = $entry['sessions'];
    $data_array['app'] = $entry['app'];
    $data_array['bytes_sent'] = $entry['bytes_sent'];
    $data_array['bytes_received'] = $entry['bytes_received'];
    $db->insert($table, $data_array);
    $count1 = $count1 + 1;
}

//fetch traffic report of minjhih Paloalto
$pa = new PaloAltoAPI('minjhih');
$data = $pa->getAsyncReport($report_type = 'custom', $report_name = 'Traffic_Top100_last_24hour');
//print_r($data);

$count2 = 0;
foreach($data['logs'] as $entry){
    $data_array = array();
    $data_array['id'] = ($count2 + 1);
    $data_array['location'] = 'minjhih';
    $data_array['src_ip'] = $entry['src'];
    $data_array['bytes'] = $entry['bytes'];
    $data_array['sessions'] = $entry['sessions'];
    $data_array['app'] = $entry['app'];
    $data_array['bytes_sent'] = $entry['bytes_sent'];
    $data_array['bytes_received'] = $entry['bytes_received'];
    $db->insert($table, $data_array);
    $count2 = $count2 + 1;
}

//update the column 'ou','name','type' from table 'client_server_lists'
$sql = "UPDATE top_source_traffic AS A
JOIN client_server_lists AS B 
ON A.src_ip = B.ip
SET A.ou = B.ou, A.name = B.name, A.type = B.type";
$db->execute($sql);

$error = $db->getErrorMessageArray();
if(!empty($error)) {
	return;
}

$nowTime = date("Y-m-d H:i:s");
$status = 200;
$count = $count1 + $count2;
echo "The ".$count." records have been inserted or updated into the top_source_traffic on ".$nowTime."\n\r<br>";

$table = "apis";
$condition = "class LIKE :class and name LIKE :name";
$apis = $db->query($table, $condition, $order_by = "1", $fields = "*", $limit = "", [':class'=>'網路流量', ':name'=>'流量來源排名']);
$table = "api_status";
$data_array = array();
$data_array['api_id'] = $apis[0]['id'];
$data_array['url'] = "";
$data_array['status'] = $status;
$data_array['data_number'] = $count;
$data_array['updated_at'] = $nowTime;
$db->insert($table, $data_array);
