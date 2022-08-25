<?php
require_once __DIR__ .'/../../vendor/autoload.php';

$db = Database::get();

//truncate the table 'drip_client_list'
$table = "drip_client_list";
$key_column = "1";
$id = "1"; 
$db->delete($table, $key_column, $id); 

//insert the table 'drip_client_list from filtered table 'dr_ip_mac_used_list' 
$sql = "INSERT INTO drip_client_list(DetectorName, DetectorIP, DetectorGroup, IP, MAC, GroupName, ClientName, SwitchIP, SwitchName, PortName, NICProductor, LastOnlineTime, LastOfflineTime, IP_BlockReason, MAC_BlockReason, MemoByMAC, MemoByIP)
SELECT DetectorName,DetectorIP,DetectorGroup,IP,MAC,GroupName,ClientName,SwitchIP,SwitchName,PortName,NICProductor,LastOnlineTime,LastOfflineTime,IP_BlockReason,MAC_BlockReason,MemoByMAC,MemoByIP 
FROM drip_ip_mac_used_list
WHERE
IP_Grant LIKE '已授權IP' AND MAC_Grant LIKE '已授權MAC' ";
$db->execute($sql);

//update the column 'type' with 'ClientName', 'GroupName'
$sql = "UPDATE drip_client_list
SET type = 'computer'
WHERE
ClientName LIKE '%PC%' OR 
ClientName LIKE '%DESKTOP%' OR 
ClientName LIKE '%LAPTOP%' OR 
ClientName LIKE '%NOTEBOOK%' OR 
ClientName REGEXP '[^ -~]' OR 
GroupName LIKE 'TAINAN' ";
$db->execute($sql);

//update the column 'ad' from table 'ad_computer_list'
$sql = "UPDATE drip_client_list AS A
JOIN ad_computers AS B 
ON A.IP = B.ip
SET A.ad = 1";
$db->execute($sql);

//update the column 'ad' from table 'ad_computer_list'
$sql = "UPDATE drip_client_list AS A
JOIN ad_computers AS B
ON A.ClientName = B.cn AND B.ip LIKE '' AND A.GroupName LIKE 'TAINAN'
SET A.ad = 1"; 
$db->execute($sql);

//update the column 'gcb','OrgName','Owner','UserName' from table 'gcb_client_list'
$sql = "
UPDATE 
    drip_client_list AS a
JOIN 
    gcb_client_list AS b 
ON 
    a.IP = INET_NTOA(b.InternalIP)
    AND 
    b.ID IN(
        SELECT MAX(ID) FROM gcb_client_list GROUP BY InternalIP
    )
SET 
    a.gcb = 1, a.OrgName=b.OrgName, a.Owner=b.Owner, a.UserName=b.UserName";
$db->execute($sql);

//update the column 'wsus' from table 'wsus_computer_status'
$sql = "UPDATE drip_client_list AS A
JOIN wsus_computer_status AS B 
ON A.IP = B.IPAddress
SET A.wsus = 1";
$db->execute($sql);

//update the column 'antivirus' from table 'antivirus_client_list'
$sql = "UPDATE drip_client_list AS A
JOIN antivirus_client_list AS B 
ON A.IP = B.IP
SET A.antivirus = 1;";
$db->execute($sql);

//update the column 'edr' from table 'edr_corecloud_ips'
$sql = "UPDATE drip_client_list AS A
JOIN edr_corecloud_ips AS B
ON A.IP = B.ip
SET A.edr = 1";
$db->execute($sql);

//update the column 'UserName' from table 'antivirus_client_list'
$sql = "UPDATE drip_client_list AS A
JOIN antivirus_client_list AS B 
ON A.IP = B.IP AND A.UserName IS NULL
SET A.UserName = CONCAT('[防毒]', B.LogonUser)";
$db->execute($sql);

//update the column 'OrgName' from table 'ad_computer_list'
$sql = "UPDATE drip_client_list AS A
JOIN ad_computers AS B 
ON A.IP = B.ip AND A.OrgName IS NULL
SET A.OrgName = CONCAT('(AD)', B.orgname)";
$db->execute($sql);

//update the column 'OrgName' from table 'ad_computer_list'
$sql = "UPDATE drip_client_list AS A
JOIN ad_computers AS B
ON A.ClientName = B.cn AND A.OrgName IS NULL AND B.ip LIKE '' 
SET A.OrgName = CONCAT('(AD)', B.orgname)";
$db->execute($sql);
