<?php
require_once __DIR__ .'/../../vendor/autoload.php';

$db = Database::get();
$inputFileName =  __DIR__ .'/../../upload/wsus/GetComputerStatus.csv';

/** Load $inputFileName to a Spreadsheet Object  **/
ini_set('auto_detect_line_endings',TRUE);
$reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
$reader->setDelimiter(',');
$reader->setEnclosure('');
$reader->setSheetIndex(0);
$spreadsheet = $reader->load($inputFileName);

if (empty($spreadsheet)) {
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

$table = "wsus_computer_status";
$key_column = "1";
$id = "1"; 
$db->delete($table, $key_column, $id); 

$count = 0;
foreach ($Rows as $data) {
    if (!empty(trim($data[0])) && !empty(trim($data[4]))) {
        $status_array = array();
        $status_array['TargetID']= intval(trim($data[0]));
        $status_array['LastSyncTime']= trim($data[1]);
        $status_array['LastReportedStatusTime']= trim($data[2]);
        $status_array['LastReportedRebootTime']= trim($data[3]);
        $status_array['IPAddress']= trim($data[4]);
        $status_array['FullDomainName']= trim(mb_convert_encoding($data[5],"utf-8","big5"));
        $status_array['EffectiveLastDetectionTime']= trim($data[6]);
        $status_array['LastSyncResult']= intval(trim($data[7]));
        $status_array['Unknown']= intval(trim($data[8]));
        $status_array['NotInstalled']= intval(trim($data[9]));
        $status_array['Downloaded']= intval(trim($data[10]));
        $status_array['Installed']= intval(trim($data[11]));
        $status_array['Failed']= intval(trim($data[12]));
        $status_array['InstalledPendingReboot']= trim($data[13]);
        $status_array['LastChangeTime']= trim($data[14]);
        $status_array['ComputerMake']= trim($data[15]);
        $status_array['ComputerModel']= trim($data[16]);
        $status_array['OSDescription']= trim($data[17]);
        //var_dump($status_array);
        
        $db->insert($table, $status_array);
        $count = $count + 1;							
    }
}

$nowTime = date("Y-m-d H:i:s", filemtime($inputFileName));
echo "The " . $count . " records have been inserted or updated into the wsus_computer_status on " . $nowTime . "\n\r<br>";
$status = 200;

$error = $db->getErrorMessageArray();
if (empty($error)) {
    $table = "apis"; // 設定你想查詢資料的資料表
    $condition = "class LIKE :class and name LIKE :name";
    $apis = $db->query($table, $condition, $order_by = "1", $fields = "*", $limit = "", [':class'=>'wsus', ':name'=>'用戶端清單']);
    $table = "api_status"; // 設定你想新增資料的資料表
    $data_array = array();
    $data_array['api_id'] = $apis[0]['id'];
    $data_array['url'] = "";
    $data_array['status'] = $status;
    $data_array['data_number'] = $count;
    $data_array['updated_at'] = $nowTime;
    $db->insert($table, $data_array);
} else {
    var_dump($error);
}

$inputFileName =  __DIR__ .'/../../upload/wsus/GetUpdateStatusKBID.csv';

/** Load $inputFileName to a Spreadsheet Object  **/
ini_set('auto_detect_line_endings',TRUE);
$reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
$reader->setDelimiter(',');
$reader->setEnclosure('');
$reader->setSheetIndex(0);
$spreadsheet = $reader->load($inputFileName);

if (empty($spreadsheet)) {
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

$table = "wsus_computer_updatestatus_kbid";
$key_column = "1";
$id = "1"; 
$db->delete($table, $key_column, $id); 

$count = 0;
foreach ($Rows as $data) {
    if (!empty(trim($data[0])) && !empty(trim($data[1]))) {
        $status_array = array();
        $status_array['TargetID']= intval(trim($data[0]));
        $status_array['KBArticleID']= intval(trim($data[1]));
        $status_array['UpdateState']= trim($data[2]);
        
        $db->insert($table, $status_array);
        $count = $count + 1;							
    }
}

$nowTime = date("Y-m-d H:i:s", filemtime($inputFileName));
echo "The ".$count." records have been inserted or updated into the wsus_computer_status on ".$nowTime."\n\r<br>";
$status = 200;

$error = $db->getErrorMessageArray();
if (empty($error)) {
    $table = "apis"; // 設定你想查詢資料的資料表
    $condition = "class LIKE :class and name LIKE :name";
    $apis = $db->query($table, $condition, $order_by = "1", $fields = "*", $limit = "", [':class'=>'wsus', ':name'=>'更新資訊']);
    $table = "api_status"; // 設定你想新增資料的資料表
    $data_array = array();
    $data_array['api_id'] = $apis[0]['id'];
    $data_array['url'] = "";
    $data_array['status'] = $status;
    $data_array['data_number'] = $count;
    $data_array['updated_at'] = $nowTime;
    $db->insert($table, $data_array);
} else {
    var_dump($error);
}

