<?php
require_once __DIR__ .'/../../vendor/autoload.php';

$db = Database::get();
$http = new HttpHelper();

$table = "scan_targets";
$condition = "oid LIKE :oid AND domain != ''";
$targets = $db->query($table, $condition, $order_by = "1", $fields = "*", $limit = "", [':oid'=>'2.16.886.101.90028.20002%']);

$table = "scan_target_web_certificates";
$key_column = "1";
$id = "1"; 
$db->delete($table, $key_column, $id); 

$count = 0;
$table = "scan_target_web_certificates";
foreach ($targets as $target) {
    $urls = $target['domain'];
    $url_array = explode(";", $urls);
    foreach ($url_array as $url) {
        //echo "url: " . $url . PHP_EOL;
        $ssl_date = $http->getSSLDate($url);
        $ssl_date['scan_target_id'] = $target['id'];
        $ssl_date['url'] = $url;
        $db->insert($table, $ssl_date);
        $count = $count + 1;							
    }
}

$nowTime = date("Y-m-d H:i:s");
echo "The " . $count . " records have been inserted or updated on " . $nowTime . PHP_EOL;

