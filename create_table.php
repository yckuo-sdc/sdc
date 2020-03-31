<?php
require("mysql_connect.inc.php");

$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
 }

$conn->query('NAMES UTF8');
$sql = "CREATE TABLE wsus_computer_updatestatus_kbid
		(
		TargetID 	int(11),
        KBArticleID	int(11),
		UpdateState	varchar(30),
		PRIMARY KEY (TargetID)
		)";
if($result = mysqli_query($conn,$sql)){
}else{
	echo("Error description: " . mysqli_error($conn));
}

$conn->close();	

?>
