<?php
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
//mysql
require("../mysql_connect.inc.php");
$row = 1;
$count = 0;
if ($uploadOk == 1){
	//讀取csv檔
	if (($handle = fopen($filename, "r")) !== FALSE) {
		$sql = "TRUNCATE TABLE security_contact";
		$conn->query($sql); 
		while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
			//ignore the first row(table head)
			if($row != 1){
				$num = count($data);
				//echo "<p> $num fields in line $row: <br /></p>\n";
				$sql = "insert into security_contact(OID,organization,person_name,unit,position,person_type,address,tel,ext,fax,email) values('".$data[0]."','".$data[1]."','".$data[2]."','".$data[3]."','".$data[4]."','".$data[5]."','".$data[6]."','".$data[7]."','".$data[8]."','".$data[9]."','".$data[10]."')"; 
				if ($conn->query($sql) == TRUE) {
					//echo "此筆資料已被上傳成功\n\r";		
					$count = $count + 1;							
				} else {
					echo "Error: " . $sql . "<br>" . $conn->error."<p>\n\r";
				}
			}
			$row++;
		}
		echo "<p>";
		echo "The ".$count." records have been inserted or updated into the security_contact \n\r<br>";
		fclose($handle);
	}
}
$conn->close();	

?>
