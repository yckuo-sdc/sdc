<?php
require_once 'vendor/autoload.php';

$db = Database::get();

//truncate the table 'drip_client_list'
$table = "drip_client_list";
$key_column = "1";
$id = "1"; 
$db->delete($table, $key_column, $id); 

//insert the table 'drip_client_list from filtered table 'dr_ip_mac_used_list' 
$sql = "INSERT INTO drip_client_list(DetectorName,DetectorIP,DetectorGroup,IP,MAC,GroupName,ClientName,SwitchIP,SwitchName,PortName,NICProductor,LastOnlineTime,LastOfflineTime,IP_BlockReason,MAC_BlockReason,MemoByMAC,MemoByIP)
SELECT DetectorName,DetectorIP,DetectorGroup,IP,MAC,GroupName,ClientName,SwitchIP,SwitchName,PortName,NICProductor,LastOnlineTime,LastOfflineTime,IP_BlockReason,MAC_BlockReason,MemoByMAC,MemoByIP 
FROM drip_ip_mac_used_list
WHERE
(ClientName LIKE '%PC%' OR ClientName LIKE '%DESKTOP%' OR ClientName LIKE '%LAPTOP%' OR ClientName REGEXP '[^ -~]' OR GroupName LIKE 'TAINAN') AND IP_Grant LIKE '已授權IP' AND MAC_Grant LIKE '已授權MAC' ";
$db->execute($sql, []);

//update the column 'ad' from table 'ad_computer_list'
$sql = "UPDATE drip_client_list AS A
JOIN ad_comupter_list AS B 
ON A.IP = B.IP
SET A.ad = 1";
$db->execute($sql, []);

//update the column 'ad' from table 'ad_computer_list'
$sql = "UPDATE drip_client_list AS A
JOIN ad_comupter_list AS B
ON A.ClientName = B.CommonName AND B.IP LIKE ''
SET A.ad = 1"; 
$db->execute($sql, []);

//update the column 'gcb','OrgName','Owner','UserName' from table 'gcb_client_list'
$sql = "UPDATE drip_client_list AS A
JOIN gcb_client_list AS B 
ON A.IP = INET_NTOA(B.InternalIP)
SET A.gcb = 1,A.OrgName=B.OrgName,A.Owner=B.Owner,A.UserName=B.UserName";
$db->execute($sql, []);

//update the column 'wsus' from table 'wsus_computer_status'
$sql = "UPDATE drip_client_list AS A
JOIN wsus_computer_status AS B 
ON A.IP = B.IPAddress
SET A.wsus = 1";
$db->execute($sql, []);

//update the column 'antivirus' from table 'antivirus_client_list'
$sql = "UPDATE drip_client_list AS A
JOIN antivirus_client_list AS B 
ON A.IP = B.IP
SET A.antivirus = 1;";
$db->execute($sql, []);

//update the column 'edr' from table 'edr_endpoints'
$sql = "UPDATE drip_client_list AS A
JOIN edr_endpoints AS B 
ON A.IP = SUBSTRING_INDEX(SUBSTRING_INDEX(B.ip, ', ', 1), ', ', -1) AND B.hidden_state LIKE '顯示中' 
SET A.edr = 1;";
$db->execute($sql, []);

//update the column 'OrgName' from table 'ad_computer_list'
$sql = "UPDATE drip_client_list AS A
JOIN ad_comupter_list AS B 
ON A.IP = B.IP AND A.OrgName IS NULL
SET A.OrgName = CONCAT('(AD)',B.OrgName)";
$db->execute($sql, []);

//update the column 'OrgName' from table 'ad_computer_list'
$sql = "UPDATE drip_client_list AS A
JOIN ad_comupter_list AS B
ON A.ClientName = B.CommonName AND A.OrgName IS NULL AND B.IP LIKE '' 
SET A.OrgName = CONCAT('(AD)',B.OrgName)";
$db->execute($sql, []);
