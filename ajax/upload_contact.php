<?php
require '../libraries/Database.php';
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
				$status['OID']= $db->getEscapedString(trim($data[0]));
				$status['organization']= $db->getEscapedString(trim($data[1]));
				$status['person_name']= $db->getEscapedString(trim($data[2]));
				$status['unit']= $db->getEscapedString(trim($data[3]));
				$status['position']= $db->getEscapedString(trim($data[4]));
				$status['person_type']= $db->getEscapedString(trim($data[5]));
				$status['address']= $db->getEscapedString(trim($data[6]));
				$status['tel']= $db->getEscapedString(trim($data[7]));
				$status['ext']= $db->getEscapedString(trim($data[8]));
				$status['fax']= $db->getEscapedString(trim($data[9]));
				$status['email']= $db->getEscapedString(trim($data[10]));
				
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
