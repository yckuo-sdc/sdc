<?php
namespace officescan\api;
require("../mysql_connect.inc.php");
$sql = "TRUNCATE TABLE client_list_match";
$conn->query($sql); 
$sql = "UPDATE drip_client_list AS A
JOIN gcb_client_list AS B 
ON A.IP = INET_NTOA(B.InternalIP)
SET A.gcb = 1";
if ($conn->query($sql) == TRUE) {
} else {
	echo "Error: " . $sql . "<br>" . $conn->error."<p>\n\r";
}
$sql = "UPDATE drip_client_list AS A
JOIN wsus_computer_status AS B 
ON A.IP = B.IPAddress
SET A.wsus = 1";
if ($conn->query($sql) == TRUE) {
} else {
	echo "Error: " . $sql . "<br>" . $conn->error."<p>\n\r";
}

$sql = "UPDATE drip_client_list AS A
JOIN antivirus_client_list AS B 
ON A.IP = B.IP
SET A.antivirus = 1;";
if ($conn->query($sql) == TRUE) {
} else {
	echo "Error: " . $sql . "<br>" . $conn->error."<p>\n\r";
}


$conn->close();
?>
