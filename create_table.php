<?php
require("mysql_connect.inc.php");



$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
 }

$conn->query('NAMES UTF8');

$sql = "CREATE TABLE security_contact
		(
			CID				int(11) AUTO_INCREMENT PRIMARY KEY ,
    		OID				varchar(50),
			organization	varchar(30),
			person_name  	varchar(30),
			unit  			varchar(30),
			position 		varchar(30),
			person_type 	varchar(30),
			address 		varchar(50),
			tel 			varchar(30),
			ext 			varchar(30),
			fax 			varchar(30),
			email 			varchar(30)
		)";

if($result = mysqli_query($conn,$sql)){
}else{
	echo("Error description: " . mysqli_error($conn));
}

$conn->close();	

?>
