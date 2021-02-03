<?php
require_once __DIR__ .'/../../vendor/autoload.php';

$db = Database::get();

$url = "https://tndev.tainan.gov.tw/api/values/5";
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => $url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_SSL_VERIFYPEER => false,
  CURLOPT_SSL_VERIFYHOST => false,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_HTTPHEADER => array("Content-Type:: application/json")
));
$res = curl_exec($curl);
curl_close($curl);

$devices = json_decode($res, true);

//fetch tndev_devices
$table = "tndev_devices";
$key_column = "1";
$id = "1"; 
$db->delete($table, $key_column, $id); 

$id = 1;
foreach($devices as $device){
    $ipv4s = explode(",", $device['ipv4']);
    foreach($ipv4s as $ipv4){
        $data_array = array();
        $data_array['id'] = $id;
        $data_array['ip'] = $ipv4;
        $data_array['name'] = $device['svname'];
        $data_array['description'] = $device['usage']; 
        $data_array['owner'] = $device['DEPT_USER'];
        $data_array['department'] = $device['DEPT'];
        $data_array['section'] = $device['DEPT_SECTION'];
        $data_array['tel'] = $device['CONTACT_INFO'];
        $data_array['mail'] = $device['CONTACT_MAIL'];
        $db->insert($table, $data_array);
        $id = $id + 1;
    }
}

