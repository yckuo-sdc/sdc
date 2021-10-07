<?php
require_once __DIR__ . '/../../vendor/autoload.php';

$db = Database::get();
$inputFileName =  __DIR__ . '/../../upload/fireeye/MDR Dashboard.xlsx';
/** Load $inputFileName to a Spreadsheet Object  **/
$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);

if(empty($spreadsheet)) {
    echo "The inputFile : " . $inputFileName . " can't been loaded \n\r<br>";
    return;
}

$worksheet = $spreadsheet->getActiveSheet();
$Rows = [];

foreach ($worksheet->getRowIterator() AS $index => $row) {
    if($index == 1){
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

$table = "edr_fireeyes";
$key_column = "1";
$id = "1"; 
$db->delete($table, $key_column, $id); 

$count = 0;
foreach($Rows as $data) {
    $dateFormat = (array) \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($data[4]);

    $data_array = array();
    $data_array['id'] = $count + 1;
    $data_array['host_name'] = $data[0];
    $data_array['ip'] = $data[1];
    $data_array['os'] = $data[2];
    $data_array['agent_version'] = $data[3];
    $data_array['last_reported_at'] = $dateFormat['date'];
    $data_array['unreported_day'] = $data[5];

    $table = "edr_fireeyes";
    $db->insert($table, $data_array);
    $count = $count + 1;						
}

$nowTime = date("Y-m-d H:i:s", filemtime($inputFileName));
echo "The " . $count . " records have been inserted or updated into the edr_fireeyes on " . $nowTime . PHP_EOL;
$status = 200;

$error = $db->getErrorMessageArray();
if(!empty($error)) {
	return;
}

$table = "apis"; // 設定你想查詢資料的資料表
$condition = "class LIKE :class and name LIKE :name";
$apis = $db->query($table, $condition, $order_by = "1", $fields = "*", $limit = "", [':class'=>'edr', ':name'=>'fireeye 用戶端清單']);
$table = "api_status"; // 設定你想新增資料的資料表
$data_array = array();
$data_array['api_id'] = $apis[0]['id'];
$data_array['url'] = "";
$data_array['status'] = $status;
$data_array['data_number'] = $count;
$data_array['updated_at'] = $nowTime;
$db->insert($table, $data_array);
