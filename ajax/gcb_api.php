<?php
namespace gcb\api;
include("../login/function.php");

//get token from API
function get_access_token($key){
	$url = "https://gcb.tainan.gov.tw/api/v1/token";
	$header_request = array("Content-Type"=>"application/json");
	$body_request = json_encode(array("key"=>$key));
	$ch = curl_init();
    $timeout = 5;
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_REFERER, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $header_request);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $body_request); 
	$response = curl_exec($ch);
	curl_close($ch);
	//echo $response;
	if(($data = json_decode($response,true)) == true){
		$token = $data['token']; 
	}
	if(isset($token)) return $token;
	else			  return false;
}

//get client_list from API
function get_client_list($token,$limit){
	$url = "https://gcb.tainan.gov.tw/api/v1/client/list";
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
	  CURLOPT_CUSTOMREQUEST => "POST",
	  CURLOPT_POSTFIELDS =>"{\r\n\"kind\": \"list&count\",\r\n\"page\": 1,\r\n\"limit\": ".$limit.",\r\n\"incl_exc\": true,\r\n\"sorts\": [\r\n{\r\n\"field\": \"ID\",\r\n\"type\": \"ASC\"\r\n}\r\n],\r\n\"select\": [\r\n\"ID\",\r\n\"Name\",\r\n\"UserName\",\r\n\"AssocOwner\",\r\n\"OrgName\",\r\n\"OSEnvID\",\r\n\"IEEnvID\",\r\n\"InternalIP\",\r\n\"ExternalIP\",\r\n\"IsOnline\",\r\n\"GsID\",\r\n\"GsSetDeployID\",\r\n\"GsStat\",\r\n\"GsExcTot\",\r\n\"GsAll\",\r\n\"GsUpdatedAt\"\r\n],\r\n\"filter\": [\r\n]\r\n}",
	  CURLOPT_HTTPHEADER => array(
		"Content-Type: application/json",
		"Authorization: Bearer ".$token
	  ),
	));
	$response = curl_exec($curl);
	curl_close($curl);
	return $response;
}

//get client_detail from API
function get_client_detail($token,$client_id){
	$url = "https://gcb.tainan.gov.tw/api/v1/client/detail/".$client_id;
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
	  CURLOPT_CUSTOMREQUEST => "POST",
	  CURLOPT_POSTFIELDS =>"{\"gs_id\":".$client_id."}",
	  CURLOPT_HTTPHEADER => array(
		"Content-Type: application/json",
		"Authorization: Bearer ".$token
	  ),
	));
	$response = curl_exec($curl);
	curl_close($curl);
	return $response;
}

//get gcb_scan result from API
function get_gscan_result($token,$gs_id){
	$url = "https://gcb.tainan.gov.tw/api/v1/gscan/result/".$gs_id;
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
	  CURLOPT_CUSTOMREQUEST => "POST",
	  CURLOPT_POSTFIELDS =>"{\"gs_id\":".$gs_id."}",
	  CURLOPT_HTTPHEADER => array(
		"Content-Type: application/json",
		"Authorization: Bearer ".$token
	  ),
	));
	$response = curl_exec($curl);
	curl_close($curl);
	return $response;
}
