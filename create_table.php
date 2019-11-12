<?php
require("mysql_connect.inc.php");

$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
 }

$conn->query('NAMES UTF8');

$sql = "CREATE TABLE tainangov_security_Incident
		(
		IncidentID                  int(11) AUTO_INCREMENT PRIMARY KEY,      
		Status						varchar(10),    
		NccstID						varchar(30), 
		NccstPT						varchar(10),
		NccstPTImpact				varchar(10), 
		OrganizationName			varchar(30),             
		ContactPerson				varchar(30),               
		Tel							varchar(30),
		Email						varchar(50),
		SponsorName					varchar(30),
		PublicIP					varchar(30),
		DeviceUsage					varchar(30),
		OperatingSystem				varchar(30),
		IntrusionURL				varchar(30),
		ImpactLevel					varchar(50),
		Classification				varchar(255),
		Explaination				varchar(255),
		Evaluation					varchar(255),
		Response					varchar(255),
		Solution					varchar(255),
		OccurrenceTime				datetime,
		InformTime					datetime,
		RepairTime					datetime,
		TainanGovVerificationTime	datetime,
		NccstVerificationTime		datetime,
		FinishTime					datetime,
		InformExecutionTime			datetime,
		FinishExecutionTime			datetime,
		SOCConfirmation				varchar(50),
		ImprovementPlanTime			datetime
		)";

if($result = mysqli_query($conn,$sql)){
}else{
	echo("Error description: " . mysqli_error($conn));
}

$conn->close();	

?>
