<?php 
$sql = "SELECT api_status.*,api_list.name,api_list.data_type FROM api_status,api_list WHERE api_status.id IN(SELECT MAX(id) FROM api_status WHERE api_id IN(SELECT id FROM api_list WHERE class ='弱掃平台') AND api_status.status=200 AND api_status.api_id = api_list.id GROUP BY api_id)";
$vul_api = $db->execute($sql, []);

$key = ChtSecurityAPI::KEY;
$nowTime = date("Y-m-d H:i:s");
$host_type = "ipscanResult";
$web_type = "urlscanResult";
$target_type = "scanTarget";
$host_auth = hash("sha256",$host_type.$key.$nowTime);
$web_auth = hash("sha256",$web_type.$key.$nowTime);
$target_auth = hash("sha256",$target_type.$key.$nowTime);
$host_url = "https://tainan-vsms.chtsecurity.com/cgi-bin/api/portal.pl?type=".$host_type."&nowTime=".$nowTime."&auth=".$host_auth;
$web_url = "https://tainan-vsms.chtsecurity.com/cgi-bin/api/portal.pl?type=".$web_type."&nowTime=".$nowTime."&auth=".$web_auth;
$target_url	= "https://tainan-vsms.chtsecurity.com/cgi-bin/api/portal.pl?type=".$target_type."&nowTime=".$nowTime."&auth=".$target_auth;

require 'view/header/default.php'; 
require 'view/body/vul/fetch.php';
require 'view/footer/default.php'; 
