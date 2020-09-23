<?php
require '../login/function.php';
require '../libraries/DatabasePDO.php';
 
session_start(); 
if( !issetBySession("Level") || $_SESSION['Level'] != 2){
	return;
}

$db = Database::get();

//PHP File Upload
$target_dir = "../upload/contact/";
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
		if($FileType != "csv") {
			echo "Sorry, only csv files are allowed.";
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
$filename = $target_file;
$row = 1;
$count = 0;
if ($uploadOk == 1){
	//讀取csv檔
	if (($handle = fopen($filename, "r")) !== FALSE) {
		$table = "security_contact";
		$key_column = "1";
		$id = "1"; 
		$db->delete($table, $key_column, $id); 
		while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
			//ignore the first row(table head)
			if($row != 1){
				$num = count($data);

				/*$status['OID'] = mb_convert_encoding(trim($data[0]), "UTF-8", mb_detect_encoding($data[0]));
				$status['organization'] = mb_convert_encoding(trim($data[1]), "UTF-8", mb_detect_encoding($data[1]));
				$status['person_name'] = mb_convert_encoding(trim($data[2]), "UTF-8", mb_detect_encoding($data[2]));
				$status['unit'] = mb_convert_encoding(trim($data[3]), "UTF-8", mb_detect_encoding($data[3]));
				$status['position'] = mb_convert_encoding(trim($data[4]), "UTF-8", mb_detect_encoding($data[4]));
				$status['person_type'] = mb_convert_encoding(trim($data[5]), "UTF-8", mb_detect_encoding($data[5]));
				$status['address'] = mb_convert_encoding(trim($data[6]), "UTF-8", mb_detect_encoding($data[6]));
				$status['tel'] = mb_convert_encoding(trim($data[7]), "UTF-8", mb_detect_encoding($data[7]));
				$status['ext'] = mb_convert_encoding(trim($data[8]), "UTF-8", mb_detect_encoding($data[8]));
				$status['fax'] = mb_convert_encoding(trim($data[9]), "UTF-8", mb_detect_encoding($data[9]));
				$status['email'] = mb_convert_encoding(trim($data[10]), "UTF-8", mb_detect_encoding($data[10]));
				*/
				
				$status['OID']= trim($data[0]);
				$status['organization']= trim($data[1]);
				$status['person_name']= trim($data[2]);
				$status['unit']= trim($data[3]);
				$status['position']= trim($data[4]);
				$status['person_type']= trim($data[5]);
				$status['address']= trim($data[6]);
				$status['tel']= trim($data[7]);
				$status['ext']= trim($data[8]);
				$status['fax']= trim($data[9]);
				$status['email']= trim($data[10]);
				 
				$db->insert($table, $status);
				$count = $count + 1;							
			}
			$row++;
		}
		echo "<p>";
		echo "The ".$count." records have been inserted or updated into the security_contact \n\r<br>";
		fclose($handle);
	}
}
