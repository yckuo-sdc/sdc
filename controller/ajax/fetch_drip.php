<?php
require_once __DIR__ .'/../../vendor/autoload.php';

$db = Database::get();
$inputFileName =  __DIR__ .'/../../upload/drip/yckuo_DrIP_IP_MAC_USED_IP_List.xls';
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

$table = "drip_ip_mac_used_list";
$key_column = "1";
$id = "1"; 
$db->delete($table, $key_column, $id); 

$count = 0;
foreach($Rows as $data) {
    if(!empty($data[4]) && $data[4] != 'IP') {
        $status['isOnline']= trim($data[0]);
        $status['DetectorName']= trim($data[2]);
        $status['DetectorIP']= trim($data[29]);
        $status['DetectorGroup']= trim($data[3]);
        $status['IP']= trim($data[4]);
        $status['MAC']= trim($data[5]);
        $status['IP_Block']= trim($data[6]);
        $status['MAC_Block']= trim($data[7]);
        $status['GroupName']= trim($data[8]);
        $status['ClientName']= trim($data[9]);
        $status['SwitchIP']= trim($data[12]);
        $status['SwitchName']= trim($data[13]);
        $status['PortName']= trim($data[14]);
        $status['NICProductor']= trim($data[11]);
        $status['LastOnlineTime']= trim($data[23]);
        $status['LastOfflineTime']= trim($data[24]);
        $status['IPMAC_Bind']= trim($data[25]);
        $status['IP_Grant']= trim($data[31]);
        $status['MAC_Grant']= trim($data[32]);
        $status['IP_BlockReason']= trim($data[41]);
        $status['MAC_BlockReason']= trim($data[42]);
        $status['MemoByMAC']= trim($data[44]);
        $status['MemoByIP']= trim($data[45]);
        
        $db->insert($table, $status);
        $count = $count + 1;							
    }
}

$nowTime = date("Y-m-d H:i:s", filemtime($inputFileName));
echo "The ".$count." records have been inserted or updated into the drip_ip_mac_used_list on ".$nowTime."\n\r<br>";
$status = 200;

$error = $db->getErrorMessageArray();
if(!empty($error)) {
	return;
}

$table = "apis"; // 設定你想查詢資料的資料表
$condition = "class LIKE :class and name LIKE :name";
$apis = $db->query($table, $condition, $order_by = "1", $fields = "*", $limit = "", [':class'=>'ip管理', ':name'=>'使用ip清單']);
$table = "api_status"; // 設定你想新增資料的資料表
$data_array = array();
$data_array['api_id'] = $apis[0]['id'];
$data_array['url'] = "";
$data_array['status'] = $status;
$data_array['data_number'] = $count;
$data_array['updated_at'] = $nowTime;
$db->insert($table, $data_array);
