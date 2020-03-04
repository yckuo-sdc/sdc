<?php
require("mysql_connect.inc.php");

$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
 }

$conn->query('NAMES UTF8');
$sql = "CREATE TABLE gcb_client_list
		(
		ExternalIP		int(11),
		GsAll_0			int(11),
		GsAll_1			int(11),
		GsAll_2			int(11),
   		GsExcTot		int(11),
		GsID			int(11), 
		GsSetDeployID 	int(11),
		GsStat			int(3),
		GsUpdatedAt		varchar(30),
		ID				int(11),
		IEEnvID			varchar(30),
		InternalIP		int(11),
		IsOnline		boolean,
		Name			varchar(30),
		OSEnvID			varchar(30),
		OrgName			varchar(30),
		Owner			varchar(30),
		UserName		varchar(30)
		)";
if($result = mysqli_query($conn,$sql)){
}else{
	echo("Error description: " . mysqli_error($conn));
}

$conn->close();	

?>
