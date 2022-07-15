<?php
require_once __DIR__ . '/../../vendor/autoload.php';

$db = Database::get();


//fetch traffic report of yonghua Paloalto
$pa = new PaloAltoAPI('yonghua');
$data = $pa->getAsyncReport($report_type = 'custom', $report_name = 'Traffic_Top100_last_24hour');

$count1 = 0;
if (!empty($data['logs'])) {
    //drop table
    $table = "top_source_traffic";
    $key_column = "location";
    $location = "yonghua"; 
    $db->delete($table, $key_column, $location); 

    //insert table
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
}

//fetch traffic report of minjhih Paloalto
$pa = new PaloAltoAPI('minjhih');
$data = $pa->getAsyncReport($report_type = 'custom', $report_name = 'Traffic_Top100_last_24hour');

$count2 = 0;
if (!empty($data['logs'])) {
    //drop table
    $table = "top_source_traffic";
    $key_column = "location";
    $location = "minjhih"; 
    $db->delete($table, $key_column, $location); 

    //insert table
    foreach($data['logs'] as $entry) {
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
}

//update the column 'ou','name','type' from table 'client_server_list'
$sql = "UPDATE top_source_traffic AS A
JOIN client_server_list AS B 
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
echo "The ".$count." records have been inserted or updated into the top_source_traffic on " . $nowTime . PHP_EOL;

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
