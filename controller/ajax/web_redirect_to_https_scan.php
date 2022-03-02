<?php
require_once __DIR__ .'/../../vendor/autoload.php';

$db = Database::get();
$http = new HttpHelper();

$table = "scan_targets";
$condition = "oid LIKE :oid AND domain != ''";
$targets = $db->query($table, $condition, $order_by = "1", $fields = "*", $limit = "", [':oid'=>'2.16.886.101.90028.20002%']);

$table = "scan_target_redirect_to_https";
$key_column = "1";
$id = "1"; 
$db->delete($table, $key_column, $id); 

$count = 0;
foreach ($targets as $target) {
    $urls = $target['domain'];
    $url_array = explode(";", $urls);
    foreach ($url_array as $url) {
        //echo "url: " . $url . PHP_EOL;
        $data_array = array();
        $data_array['scan_target_id'] = $target['id'];
        $data_array['url'] = $url;
        $data_array['valid'] = (int) $http->isOnlyOrRedirectHttps($url, $status);
        $data_array['status'] = $status;
        $db->insert($table, $data_array);
        $count = $count + 1;							
    }
}

$nowTime = date("Y-m-d H:i:s");
echo "The " . $count . " records have been inserted or updated on " . $nowTime . PHP_EOL;

