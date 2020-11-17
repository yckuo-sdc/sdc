<?php 
$limit = 10;
$links = 4;
$page = isset( $_GET['page']) ? $_GET['page'] : 1;
$sort = isset($_GET['sort'])?$_GET['sort']:'TargetID';	

//drip
$query = "SELECT * FROM drip_client_list ORDER BY DetectorName,IP";
$drip_paginator = new Paginator($query);
$drip = $drip_paginator->getData($limit, $page);
$drip_num_rows = $drip_paginator->getTotal();

//gcb
$query = "SELECT a.*,b.name as os_name,c.name as ie_name FROM gcb_client_list as a LEFT JOIN gcb_os as b ON a.OSEnvID = b.id LEFT JOIN gcb_ie as c ON a.IEEnvID = c.id ORDER by a.ID asc,a.InternalIP asc";
$gcb_paginator = new Paginator($query);
$gcb = $gcb_paginator->getData($limit, $page);
$gcb_num_rows = $gcb_paginator->getTotal();

//wsus
$query = "SELECT * FROM wsus_computer_status ORDER BY ".$sort;
$wsus_paginator = new Paginator($query);
$wsus = $wsus_paginator->getData($limit, $page);
$wsus_num_rows = $wsus_paginator->getTotal();

$notinstalled_kb = array(); 
$failed_kb = array();
foreach($wsus->data as $client) { 
    $table = "wsus_computer_updatestatus_kbid";
    $order_by = "ID";
    $condition = "TargetID = :TargetID AND UpdateState = :UpdateState";
    $data_array = [':TargetID'=>$client['TargetID'],':UpdateState'=>'NotInstalled'];
    $notinstalled_kb[] = $db->query($table, $condition, $order_by, $fields = "*", $kb_limit = "",$data_array);
    $data_array = [':TargetID'=>$client['TargetID'],':UpdateState'=>'Failed'];
    $failed_kb[] = $db->query($table, $condition, $order_by, $fields = "*", $kb_limit = "", $data_array);
}

//antivirus
$query = "SELECT * FROM antivirus_client_list ORDER BY DomainLevel, ClientName";
$antivirus_paginator = new Paginator($query);
$antivirus = $antivirus_paginator->getData($limit, $page);
$antivirus_num_rows = $antivirus_paginator->getTotal();

require 'view/header/default.php'; 
require 'view/body/query/client.php';
require 'view/footer/default.php'; 
