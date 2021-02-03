<?php
require_once __DIR__ .'/../../vendor/autoload.php';

$db = Database::get();
$ld = new MyLDAP();

$base = "ou=TainanComputer, dc=tainan, dc=gov, dc=tw";
$database_attributes = array("CommonName", "DnsHostname", "OperatingSystem", "OperatingSystemVersion", "DistinguishedName", "LastLogonTime", "PwdLastSetTime");
$ldap_attributes =  array("cn", "dnshostname", "operatingsystem", "operatingsystemversion", "distinguishedname", "lastlogon", "pwdlastset");

$data_array = array();
$data_array['base'] = $base;
$data_array['filter'] = "(objectClass=computer)";
$data_array['attributes'] = $ldap_attributes; 
$data = $ld->getData($data_array);

$count = 0;
echo count($data) . " entries returned\n";
if(empty($data)) {
    echo "No target-data \n\r<br>";
    $status = 400;
}else {
    $table = "ad_comupter_list";
    $key_column = "1";
    $id = "1"; 
    $db->delete($table, $key_column, $id); 

    foreach($data as $entry) {
        $status = array();
        foreach($database_attributes as $index => $attribute){
            $status[$attribute]= empty($entry[$ldap_attributes[$index]]) ? "" : $entry[$ldap_attributes[$index]];
        }

        $status['OrgName'] = $ld->getSingleOUDescription($base, $entry['distinguishedname']);

        if(!empty($entry['dnshostname'])) {
            $output = shell_exec("/usr/bin/dig +short " . $entry['dnshostname']);
            $IPs = explode(PHP_EOL,$output);
            $size = sizeof($IPs);
        }

        if(!isset($size) || $size == 1) {
            $status['IP']= "";
            $db->insert($table, $status);
            $count = $count + 1;
        }else{
            for($j=0; $j<$size-1; $j++) {
                $status['IP']= $IPs[$j];
                $db->insert($table, $status);
                $count = $count + 1;
            }
        }
    }

    $nowTime = date("Y-m-d H:i:s");
    echo "The ".$count." records have been inserted or updated into the ad_computer_list on ".$nowTime."\n\r<br>";
    $status = 200;
}

$error = $db->getErrorMessageArray();
if(!empty($error)) {
	return;
}

$table = "apis"; // 設定你想查詢資料的資料表
$condition = "class LIKE :class and name LIKE :name";
$apis = $db->query($table, $condition, $order_by = "1", $fields = "*", $limit = "", [':class'=>'ad', ':name'=>'個人電腦清單']);
$table = "api_status"; // 設定你想新增資料的資料表
$data_array = array();
$data_array['api_id'] = $apis[0]['id'];
$data_array['url'] = "";
$data_array['status'] = $status;
$data_array['data_number'] = $count;
$data_array['last_update'] = $nowTime;
$db->insert($table, $data_array);
