<?php
namespace officescan\api;
require("../mysql_connect.inc.php");
$sql = "TRUNCATE TABLE drip_client_list";
$conn->query($sql); 
$sql = "INSERT INTO drip_client_list(DetectorName,DetectorIP,DetectorGroup,IP,MAC,GroupName,ClientName,SwitchIP,SwitchName,PortName,NICProductor,LastOnlineTime,LastOfflineTime,IP_BlockReason,MAC_BlockReason,MemoByMAC,MemoByIP)
SELECT DetectorName,DetectorIP,DetectorGroup,IP,MAC,GroupName,ClientName,SwitchIP,SwitchName,PortName,NICProductor,LastOnlineTime,LastOfflineTime,IP_BlockReason,MAC_BlockReason,MemoByMAC,MemoByIP 
FROM drip_ip_mac_used_list
WHERE
(ClientName LIKE '%PC%' OR ClientName LIKE '%DESKTOP%' OR ClientName LIKE '%LAPTOP%' OR ClientName REGEXP '[^ -~]' OR GroupName LIKE 'TAINAN') AND IP_Grant LIKE '已授權IP' AND MAC_Grant LIKE '已授權MAC' ";
if ($conn->query($sql) == TRUE) {
} else {
	echo "Error: " . $sql . "<br>" . $conn->error."<p>\n\r";
}
$sql = "UPDATE drip_client_list AS A
JOIN ad_comupter_list AS B 
ON A.IP = B.IP
SET A.ad = 1";
if ($conn->query($sql) == TRUE) {
} else {
	echo "Error: " . $sql . "<br>" . $conn->error."<p>\n\r";
}
$sql = "UPDATE drip_client_list AS A
JOIN ad_comupter_list AS B
ON A.ClientName = B.CommonName AND B.IP LIKE ''
SET A.ad = 1"; 
if ($conn->query($sql) == TRUE) {
} else {
	echo "Error: " . $sql . "<br>" . $conn->error."<p>\n\r";
}
$sql = "UPDATE drip_client_list AS A
JOIN gcb_client_list AS B 
ON A.IP = INET_NTOA(B.InternalIP)
SET A.gcb = 1,A.OrgName=B.OrgName,A.Owner=B.Owner,A.UserName=B.UserName";
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
