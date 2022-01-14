<?php 
$sql = "SELECT a.*, b.name FROM api_status AS a INNER JOIN apis AS b ON a.api_id = b.id 
    WHERE a.id IN(SELECT max(id) FROM api_status WHERE api_id IN (SELECT id FROM apis WHERE class='弱掃平台') 
    GROUP BY api_id)";
$vul_api = $db->execute($sql);

$types = array("ipscanResult", "urlscanResult", "scanTarget");
$urls = array();
$nowTime = date("Y-m-d H:i:s");
$ou = "2.16.886.101.90028.20002";

foreach ($types as $type) {
    $auth = hash("sha256", $type . ChtSecurity::APIKEY . $nowTime);
    $query = array(
        'type' => $type,
        'nowTime' => $nowTime,
        'auth' => $auth,
        'ou' => $ou,
    );
    $urls[$type] = ChtSecurity::API_URL . "?" . http_build_query($query);
}

require 'view/header/default.php'; 
require 'view/body/vul/fetch.php';
require 'view/footer/default.php'; 
