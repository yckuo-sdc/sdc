<?php
require("mysql_connect.inc.php");



$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
 }

$conn->query('NAMES UTF8');

$sql = "CREATE TABLE urlscanResult
		(
    		vitem_id	int(11),
			ou			varchar(30),
			status  	varchar(30),
			ip 			varchar(30),
			system_name varchar(100),
			flow_id 	int(11),
			scan_no 	varchar(30),
			affect_url	varchar(200),
			manager 	varchar(30),
			email 		varchar(100),
			vitem_name 	varchar(30),
			url 		varchar(200),
			category 	varchar(30),
			severity 	varchar(30),
			scan_date 	DATETIME 
		)";

if($result = mysqli_query($conn,$sql)){
}else{
	echo("Error description: " . mysqli_error($conn));
}

$conn->close();	

?>
