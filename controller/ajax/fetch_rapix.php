<?php
use gcb\api\RapixWebAPIAdapter;
require_once __DIR__ .'/../../vendor/autoload.php';

$db = Database::get();
$rapix = new RapixWebAPIAdapter();

$cpes = $rapix->fetchCPEAssets();
$key_array = array(
    array('input' => 'ID', 'output' => 'id', 'default_value' => 0),
    array('input' => 'Name', 'output' => 'name', 'default_value' => NULL),
    array('input' => 'Title', 'output' => 'title', 'default_value' => NULL),
    array('input' => 'UpdatedAt', 'output' => 'updated_at', 'default_value' => 0),
    array('input' => 'Part', 'output' => 'part', 'default_value' => NULL),
    array('input' => 'Vendor', 'output' => 'vendor', 'default_value' => NULL),
    array('input' => 'Product', 'output' => 'product', 'default_value' => NULL),
    array('input' => 'Version', 'output' => 'version', 'default_value' => NULL),
    array('input' => 'NumberOfCVEs', 'output' => 'number_of_cves', 'default_value' => 0),
    array('input' => 'CVSS_V3_Severity', 'output' => 'cvss_v3_severity', 'default_value' => NULL),
    array('input' => 'CVSS_V3_Score', 'output' => 'cvss_v3_score', 'default_value' => 0),
    array('input' => 'CVSS_V2_Severity', 'output' => 'cvss_v2_severity', 'default_value' => NULL),
    array('input' => 'CVSS_V2_Score', 'output' => 'cvss_v2_score', 'default_value' => 0),
);

$cpe_count = 0;
if (empty($cpes)) {
	echo "No target-data" . PHP_EOL;
	$cpe_nowTime = date("Y-m-d H:i:s");
	$cpe_status = 400;
} else {
    $table = "rapix_cpes";
    $key_column = "1";
    $id = "1"; 
    $db->delete($table, $key_column, $id); 

	foreach($cpes as $cpe) {
        $entry = array();

        foreach($key_array as $key) {
            $entry[$key['output']] = empty($cpe[$key['input']]) ? $key['default_value'] : $cpe[$key['input']];
        }

        $db->insert($table, $entry);
        $cpe_count = $cpe_count + 1;
    }

    $cpe_nowTime = date("Y-m-d H:i:s");
    echo "The ". $cpe_count . " records have been inserted or updated into the rapix_cpes on " . $cpe_nowTime . PHP_EOL;
	$cpe_status = 200;
}

$clients = $rapix->fetchClientList(); 
$key_array = array(
    array('input' => 'ID', 'output' => 'id', 'default_value' => 0),
    array('input' => 'ExternalIP', 'output' => 'external_ip', 'default_value' => 0),
    array('input' => 'IEEnvID', 'output' => 'ie_env_id', 'default_value' => 0),
    array('input' => 'InternalIP', 'output' => 'internal_ip', 'default_value' => 0),
    array('input' => 'IsOnline', 'output' => 'is_online', 'default_value' => 0),
    array('input' => 'LastActiveAt', 'output' => 'last_active_at', 'default_value' => NULL),
    array('input' => 'Name', 'output' => 'name', 'default_value' => NULL),
    array('input' => 'OSArch', 'output' => 'os_arch', 'default_value' => 0),
    array('input' => 'OSEnvID', 'output' => 'os_env_id', 'default_value' => 0),
    array('input' => 'OrgID', 'output' => 'org_id', 'default_value' => 0),
    array('input' => 'OrgName', 'output' => 'org_name', 'default_value' => NULL),
    array('input' => 'OwnerAssoc', 'output' => 'owner_assoc', 'default_value' => NULL),
    array('input' => 'UpdatedAt', 'output' => 'updated_at', 'default_value' => NULL),
    array('input' => 'UserName', 'output' => 'user_name', 'default_value' => NULL),
);

$count = 0;
if (empty($clients)) {
	echo "No target-data" . PHP_EOL;
	$nowTime = date("Y-m-d H:i:s");
	$status = 400;
} else {
    $table = "rapix_clients";
    $key_column = "1";
    $id = "1"; 
    $db->delete($table, $key_column, $id); 

	foreach($clients as $client) {
        $entry = array();

        foreach($key_array as $key) {
            $entry[$key['output']] = empty($client[$key['input']]) ? $key['default_value'] : $client[$key['input']];
        }

        $db->insert($table, $entry);
        $count = $count + 1;
    }

    $nowTime = date("Y-m-d H:i:s");
    echo "The ". $count . " records have been inserted or updated into the rapix_clients on " . $nowTime . PHP_EOL;
	$status = 200;
}

$maps = $rapix->fetchCPEIDAndClientIDMap();
$key_array = array(
    array('input' => 'cpe_id', 'output' => 'CPEID', 'default_value' => 0),
    array('input' => 'client_id', 'output' => 'ClientID', 'default_value' => 0),
);

$count = 0;
if (empty($maps)) {
	echo "No target-data" . PHP_EOL;
	$nowTime = date("Y-m-d H:i:s");
	$status = 400;
} else {
    $table = "rapix_cpe_client_map";
    $key_column = "1";
    $id = "1"; 
    $db->delete($table, $key_column, $id); 

	foreach($maps as $index => $map) {
        $entry = array();

        $entry['rapix_cpe_id'] = $map['CPEID'];
        foreach ($map['ClientID'] as $client_id) {
            $entry['rapix_client_id'] = $client_id;
            $entry['id'] = $count + 1;
            $db->insert($table, $entry);
            $count = $count + 1;
        }

    }

    $nowTime = date("Y-m-d H:i:s");
    echo "The ". $count . " records have been inserted or updated into the rapix_cpe_client_map on " . $nowTime . PHP_EOL;
	$status = 200;
}

$error = $db->getErrorMessageArray();
if(!empty($error)) {
	return;
}

$table = "apis"; // 設定你想查詢資料的資料表
$condition = "class LIKE :class and name LIKE :name";
$apis = $db->query($table, $condition, $order_by = "1", $fields = "*", $limit = "", [':class'=>'vans', ':name'=>'資訊資產']);
$table = "api_status"; // 設定你想新增資料的資料表
$data_array = array();
$data_array['api_id'] = $apis[0]['id'];
$data_array['url'] = "";
$data_array['status'] = $cpe_status;
$data_array['data_number'] = $cpe_count;
$data_array['updated_at'] = $cpe_nowTime;
$db->insert($table, $data_array);
