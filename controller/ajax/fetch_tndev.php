<?php
require_once __DIR__ .'/../../vendor/autoload.php';

$url = "https://tndev.tainan.gov.tw/api/values/4";
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

$servers = json_decode($res, true);
$count = count($servers);

$data = array();
//$data['draw'] = 1;
//$data['recordsTotal'] = $count;
//$data['recordsFiltered'] = 10;
$data['data'] = $servers;

echo json_encode($data, JSON_UNESCAPED_UNICODE);
