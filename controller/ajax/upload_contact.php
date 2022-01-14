<?php
require_once __DIR__ .'/../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;

if($_SESSION['level'] != 2){
	return;
}

//PHP File Upload
$target_dir = "upload/contact/";
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
				echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
			} else {
				echo "Sorry, there was an error uploading your file.";
			}
		}
	}
}

$inputFileName = $target_file;
$count = 0;

if ($uploadOk == 1){

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

	$table = "security_contact";
	$key_column = "1";
	$id = "1"; 
	$db->delete($table, $key_column, $id); 

    foreach($Rows as $row) {
        $data_array = array();
        $data_array['oid']= trim($row[0]);
        $data_array['organization']= trim($row[1]);
        $data_array['person_name']= trim($row[2]);
        $data_array['unit']= trim($row[3]);
        $data_array['position']= trim($row[4]);
        $data_array['person_type']= trim($row[5]);
        $data_array['address']= trim($row[6]);
        $data_array['tel']= trim($row[7]);
        $data_array['ext']= trim($row[8]);
        $data_array['fax']= trim($row[9]);
        $data_array['email']= trim($row[10]);

        $db->insert($table, $data_array);
        $count = $count + 1;							
    }		

    $error = $db->getErrorMessageArray();
    if(!empty($error)) {
        return;
    }

	$nowTime = date("Y-m-d H:i:s");
	$status = 200;	
    echo "<p>";
    echo "The " . $count . " records have been updated on " . $nowTime . "<br>";

	$table = "apis"; // 設定你想查詢資料的資料表
	$condition = "class LIKE :class and name LIKE :name";
	$apis = $db->query($table, $condition, $order_by = "1", $fields = "*", $limit = "", [':class'=>'資安事件', ':name'=>'資安聯絡人']);
	$table = "api_status"; // 設定你想新增資料的資料表
    $data_array = array();
	$data_array['api_id'] = $apis[0]['id'];
	$data_array['url'] = $apis[0]['url'];
	$data_array['status'] = $status;
	$data_array['data_number'] = $count;
	$data_array['updated_at'] = $nowTime;
	$db->insert($table, $data_array);
}
