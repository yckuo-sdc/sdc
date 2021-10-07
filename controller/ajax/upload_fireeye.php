<?php
require_once __DIR__ .'/../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;

if($_SESSION['level'] != 2){
	return;
}

//PHP File Upload
$target_dir = "upload/fireeye/";
if(is_array($_FILES)) {
	if(is_uploaded_file($_FILES["fileToUpload"]["tmp_name"])) {
		$source_file = $_FILES["fileToUpload"]["tmp_name"];
		$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
		$FileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
		$uploadOk = 1;
		// Check if file already exists
		// Check file size
		if ($_FILES["fileToUpload"]["size"] > 500000) {
			echo "Sorry, your file is too large.";
			$uploadOk = 0;
		}
		// Allow certain file formats
		if($FileType != "xls" && $FileType != "xlsx" && $FileType != "csv") {
			echo "Sorry, only xls, xlsx and csv files are allowed.";
			$uploadOk = 0;
		}
		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 0) {
			echo "Sorry, your file was not uploaded.";
		// if everything is ok, try to upload file
		} else {
			if (move_uploaded_file($source_file, $target_file)) {
				echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded<br>";
			} else {
				echo "Sorry, there was an error uploading your file.";
			}
		}
	}
}

$inputFileName = $target_file;

if ($uploadOk == 0){
    return;
}

/** Load $inputFileName to a Spreadsheet Object  **/
$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);

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
$status = 200;

// update the column 'fireeye' of 'tndevs' 
$sql = "UPDATE tndevs AS A
JOIN tndev_ips AS B 
ON A.id = B.tndev_id
JOIN edr_fireeyes AS C 
ON B.ip = C.ip
SET A.edr_fireeye = 1";
$db->execute($sql);

$nowTime = date("Y-m-d H:i:s", filemtime($inputFileName));
echo "The " . $count . " records have been inserted or updated into the edr_fireeyes on " . $nowTime . "<br>";

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
