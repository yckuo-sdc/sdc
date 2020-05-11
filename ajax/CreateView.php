<?php
	require("../mysql_connect.inc.php");

	$sql = "SELECT b.name,COUNT(b.name) as count FROM gcb_client_list as a LEFT JOIN gcb_os as b ON a.OSEnvID = b.id GROUP BY b.name ORDER by count desc";

	$sql ="CREATE VIEW V_all_client_list_by_name AS
SELECT A.gcb AS gcb,A.wsus AS wsus,antivirus_client_list.ClientName AS antivirus  
FROM (
SELECT gcb_client_list.Name AS gcb,UPPER(Replace (wsus_computer_status.FullDomainName, '.tainan.gov.tw',  ''))  AS wsus 
FROM gcb_client_list LEFT JOIN wsus_computer_status 
ON gcb_client_list.Name = UPPER(Replace (wsus_computer_status.FullDomainName, '.tainan.gov.tw',  ''))
) A
LEFT JOIN 
antivirus_client_list ON A.gcb = antivirus_client_list.ClientName
UNION
SELECT A.gcb AS gcb,A.wsus AS wsus,antivirus_client_list.ClientName AS antivirus
FROM  (
SELECT gcb_client_list.Name AS gcb,UPPER(Replace (wsus_computer_status.FullDomainName, '.tainan.gov.tw',  ''))  AS wsus 
FROM gcb_client_list LEFT JOIN wsus_computer_status 
ON gcb_client_list.Name = UPPER(Replace (wsus_computer_status.FullDomainName, '.tainan.gov.tw',  ''))
) A
RIGHT JOIN 
antivirus_client_list 
ON A.gcb = antivirus_client_list.ClientName";


	$sql = "
CREATE VIEW V_client_list_match_by_IP AS
SELECT A.gcb AS gcb,A.wsus AS wsus,antivirus_client_list.IP AS antivirus  
FROM (
SELECT INET_NTOA(gcb_client_list.InternalIP) AS gcb,wsus_computer_status.IPAddress AS wsus 
FROM gcb_client_list LEFT JOIN wsus_computer_status 
ON INET_NTOA(gcb_client_list.InternalIP) = wsus_computer_status.IPAddress
COLLATE utf8_unicode_ci
) A
LEFT JOIN 
antivirus_client_list 
ON A.gcb = antivirus_client_list.IP
COLLATE utf8_unicode_ci
UNION
SELECT A.gcb AS gcb,A.wsus AS wsus,antivirus_client_list.IP AS antivirus
FROM  (
SELECT INET_NTOA(gcb_client_list.InternalIP) AS gcb,wsus_computer_status.IPAddress AS wsus 
FROM gcb_client_list LEFT JOIN wsus_computer_status 
ON INET_NTOA(gcb_client_list.InternalIP) = wsus_computer_status.IPAddress
COLLATE utf8_unicode_ci
) A
RIGHT JOIN 
antivirus_client_list 
ON A.gcb = antivirus_client_list.IP
COLLATE utf8_unicode_ci";



	$result 	= mysqli_query($conn,$sql);
	$rowcount	= mysqli_num_rows($result);
	while($row = mysqli_fetch_assoc($result)) {
	}

	$conn->close();


?>
