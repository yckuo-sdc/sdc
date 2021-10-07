<?php
require_once __DIR__ . '/../../vendor/autoload.php';
$inputFileName =  __DIR__ . '/../../upload/corecloud/endpoints.csv';

$db = Database::get();

$inputFileType = 'Csv';
//  $inputFileType = 'Xls';
//  $inputFileType = 'Xlsx';
//  $inputFileType = 'Xml';
//  $inputFileType = 'Ods';
//  $inputFileType = 'Slk';
//  $inputFileType = 'Gnumeric';

//  Create a new Reader of the type defined in $inputFileType 
$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);

//  this line would internally convert chinese big5 to utf-8 encoding
$reader->setInputEncoding('big5'); 

//  Load $inputFileName to a Spreadsheet Object
$spreadsheet = $reader->load($inputFileName);

if(empty($spreadsheet)) {
    echo "The inputFile : " . $inputFileName . " can't been loaded \n\r<br>";
    return;
}

$worksheet = $spreadsheet->getActiveSheet();
$Rows = [];

foreach ($worksheet->getRowIterator() AS $index => $row) {
    if($index == 1){  // skip first row
        continue;
    }
    $cellIterator = $row->getCellIterator();
    $cellIterator->setIterateOnlyExistingCells(FALSE); // This loops through all cells,
    $cells = [];
    foreach ($cellIterator as $cell) {
        $cells[] = $cell->getValue();
    }
    $Rows[] = $cells;
}

$table = "edr_coreclouds";
$key_column = "1";
$id = "1"; 
$db->delete($table, $key_column, $id); 

$table = "edr_corecloud_ips";
$key_column = "1";
$id = "1"; 
$db->delete($table, $key_column, $id); 

$count = 0;
foreach($Rows as $data) {
    $edr = array();
    $edr['id'] = $count + 1;
    $edr['host_name'] = $data[0];
    $edr['ip'] = $data[1];
    $edr['state'] = $data[2];
    $edr['last_online_at'] = $data[3];
    $edr['os'] = $data[4];
    $edr['finished_number'] = $data[5];
    $edr['unfinished_number'] = $data[6];
    $edr['total_number'] = $data[7];
    $edr['hidden_state'] = $data[8];

    $ip = $edr['ip'];
    $table = "edr_coreclouds";
    $condition = "ip LIKE :ip";
    $existings = $db->query($table, $condition, $order_by = "1", $fields = "*", $limit = "", [':ip' => $ip]);
    if (empty($existings)) {
        $ip_array = explode(", ", $edr['ip']);
        foreach($ip_array as $single_ip) {
            $data_array = array();
            $data_array['edr_corecloud_id'] = $edr['id'];
            $data_array['ip'] = $single_ip;
            $table = "edr_corecloud_ips";
            $db->insert($table, $data_array);
        }
        $table = "edr_coreclouds";
        $db->insert($table, $edr);
        $count = $count + 1;						
    } else {
        unset($edr['id']);
        unset($edr['ip']);
        $table = "edr_coreclouds";
        $db->update($table, $edr, $key_column = "ip", $ip);
    }

}

$nowTime = date("Y-m-d H:i:s", filemtime($inputFileName));
echo "The " . $count . " records have been inserted or updated into the edr_coreclouds on " . $nowTime . PHP_EOL;
$status = 200;

$error = $db->getErrorMessageArray();
if(!empty($error)) {
	return;
}

$table = "apis"; // 設定你想查詢資料的資料表
$condition = "class LIKE :class and name LIKE :name";
$apis = $db->query($table, $condition, $order_by = "1", $fields = "*", $limit = "", [':class'=>'edr', ':name'=>'corecloud 用戶端清單']);
$table = "api_status"; // 設定你想新增資料的資料表
$data_array = array();
$data_array['api_id'] = $apis[0]['id'];
$data_array['url'] = "";
$data_array['status'] = $status;
$data_array['data_number'] = $count;
$data_array['updated_at'] = $nowTime;
$db->insert($table, $data_array);
