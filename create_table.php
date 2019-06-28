<?php
require("mysql_connect.inc.php");



$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
 }

$conn->query('SET NAMES UTF8');

$sql = "CREATE TABLE Customer
		(SN int(11),
		 state varchar(30),
		date datetime,
		class varchar(50),
		area varchar(50),
		IP varchar(50),
		block_reason varchar(50),
		device_class varchar(50),

			Address char(50),
			City char(50),
			Country char(25),
			Birth_Date datetime);
		)		"

$result = mysqli_query($conn,$sql);


$conn->close();	

?>
