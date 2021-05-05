<?php 
$sql = "SELECT COUNT(*) AS total_num, SUM(ad) AS ad_num, SUM(gcb) AS gcb_num, SUM(wsus) AS wsus_num, SUM(antivirus) AS antivirus_num, SUM(edr) AS edr_num FROM drip_client_list
WHERE type LIKE 'computer' ";
$device_num = $db->execute($sql, [])[0];

$total_num = $device_num['total_num'];
$ad_num = $device_num['ad_num'];
$gcb_num = $device_num['gcb_num'];
$wsus_num = $device_num['wsus_num'];
$antivirus_num = $device_num['antivirus_num'];
$edr_num = $device_num['edr_num'];

$total_rate = round($total_num / $total_num*100, 2) . "%"; 
$ad_rate = round($ad_num / $total_num*100, 2) . "%"; 
$gcb_rate = round($gcb_num / $total_num*100, 2) . "%"; 
$wsus_rate = round($wsus_num / $total_num*100, 2) . "%"; 
$antivirus_rate = round($antivirus_num / $total_num*100, 2) . "%"; 
$edr_rate = round($edr_num / $total_num*100, 2) . "%"; 

$table = "ad_comupter_list";
$db->query($table, $condition = "1 = ?", $order_by = "1", $fields = "DISTINCT(CommonName)", $limit = "", [1]);
$ad_computer_num = $db->getLastNumRows();

$sql = "SELECT COUNT(ID) as total_count, SUM(CASE WHEN GsAll_2 = GsAll_1 THEN 1 ELSE 0 END) as pass_count FROM gcb_client_list";
$gcb = $db->execute($sql, [])[0];
$total_count = $gcb['total_count'];
$pass_count = $gcb['pass_count'];
$total_rate = ($total_count != 0) ? round($total_count/$total_count*100,2)."%" : 0; 
$pass_rate = ($total_count != 0) ? round($pass_count/$total_count*100,2)."%" : 0; 

$sql = "SELECT COUNT(TargetID) as total_count,SUM(CASE WHEN Failed LIKE '0' THEN 1 ELSE 0 END) as pass_count FROM wsus_computer_status";
$wsus = $db->execute($sql, [])[0];
$total_wsus_num = $wsus['total_count'];
$pass_wsus_num = $wsus['pass_count'];
$table = "wsus_computer_status"; // 設定你想查詢資料的資料表
$db->query($table, $condition = "LastSyncTime > ADDDATE(NOW(), INTERVAL -1 WEEK) AND 1 = ?", $order_by = "1", $fields = "*", $limit = "", [1]);
$sync_wsus_num = $db->getLastNumRows();
$total_wsus_rate = round($total_wsus_num/$total_wsus_num*100,2)."%"; 
$pass_wsus_rate = round($pass_wsus_num/$total_wsus_num*100,2)."%"; 
$sync_wsus_rate = round($sync_wsus_num/$total_wsus_num*100,2)."%"; 

$table = "antivirus_client_list"; 
$db->query($table, $condition = "1 = ?", $order_by = "1", $fields = "*", $limit = "", [1]);
$total_antivirus_num = $db->getLastNumRows();
$db->query($table, $condition = "DLPState IN (?,?,?)", $order_by = "1", $fields = "*", $limit = "", ['已停止','需要重新啟動','執行']);
$dlp_num = $db->getLastNumRows();
$total_antivirus_rate = round($total_antivirus_num/$total_antivirus_num*100,2)."%"; 
$dlp_rate = round($dlp_num/$total_antivirus_num*100,2)."%"; 
$client_antivirus_num = $antivirus_num;
$server_antivirus_num = $total_antivirus_num - $client_antivirus_num;
$client_antivirus_rate = round($client_antivirus_num/$total_antivirus_num*100,2)."%"; 
$server_antivirus_rate = round($server_antivirus_num/$total_antivirus_num*100,2)."%"; 

$table = "edr_endpoints"; 
$db->query($table, $condition = "1 = ?", $order_by = "1", $fields = "*", $limit = "", [1]);
$total_edr_num = $db->getLastNumRows();
$db->query($table, $condition = "state LIKE :state", $order_by = "1", $fields = "*", $limit = "", [':state' => '暫停監控']);
$stopping_monitor_num = $db->getLastNumRows();
$db->query($table, $condition = "total_number > :number", $order_by = "1", $fields = "*", $limit = "", [':number' => '0']);
$suspicious_host_num = $db->getLastNumRows();
$total_edr_rate = round($total_edr_num / $total_edr_num * 100, 2) . "%"; 
$stopping_monitor_rate = round($stopping_monitor_num / $total_edr_num * 100 ,2) . "%"; 
$suspicious_host_rate = round($suspicious_host_num / $total_edr_num * 100, 2) . "%"; 

require 'view/header/default.php'; 
require 'view/body/info/client.php';
require 'view/footer/default.php'; 
