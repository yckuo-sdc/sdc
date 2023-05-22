<?php
require_once __DIR__ .'/../../vendor/autoload.php';

$db = Database::get();

$sql = "UPDATE tndevs AS A
JOIN tndev_ips AS B 
ON A.id = B.tndev_id
JOIN antivirus_client_list AS C 
ON B.ip = C.IP
SET A.antivirus = 1";
$db->execute($sql);

$sql = "UPDATE tndevs AS A
JOIN tndev_ips AS B 
ON A.id = B.tndev_id
JOIN edr_corecloud_ips AS C 
ON B.ip = C.ip
SET A.edr_corecloud = 1";
$db->execute($sql);

$sql = "UPDATE tndevs AS A
JOIN tndev_ips AS B 
ON A.id = B.tndev_id
JOIN edr_fireeyes AS C 
ON B.ip = C.ip
SET A.edr_fireeye = 1";
$db->execute($sql);

$sql = "UPDATE tndevs AS A
JOIN tndev_ips AS B 
ON A.id = B.tndev_id
JOIN edr_crowdstrikes AS C 
ON B.ip = C.internal_ip OR B.ip = C.external_ip
SET A.edr_crowdstrike = 1";
$db->execute($sql);

$sql = "UPDATE tndevs AS A
JOIN tndev_ips AS B 
ON A.id = B.tndev_id
JOIN gcb_client_list AS C 
ON B.ip = INET_NTOA(C.InternalIP) AND C.InternalIP != 0
SET A.gcb = 1";
$db->execute($sql);
