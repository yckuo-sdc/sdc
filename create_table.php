<?php
require("mysql_connect.inc.php");

$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
 }

$conn->query('NAMES UTF8');

$sql = "CREATE TABLE scanTarget
		(
		oid			varchar(50),      
		ou			varchar(30),    
		system_name	varchar(100), 
		ip			varchar(30),
		domain		varchar(500), 
		manager		varchar(30),             
		email		varchar(100),
		PRIMARY KEY (ip,domain)
		)";

if($result = mysqli_query($conn,$sql)){
}else{
	echo("Error description: " . mysqli_error($conn));
}

$conn->close();	

?>
