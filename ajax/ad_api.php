<?php
namespace ad\api;
include("../login/function.php");
//edit user of AD
function edit_user($cn,$newpass,$confirmpass,$displayname,$title,$telephonenumber,$physicaldeliveryofficename,$mail){
	$url = "http://172.16.254.2/api/EditUser";
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
	  CURLOPT_POSTFIELDS =>"{\"Username\":\"$cn\",\"Password\":\"$newpass\",\"NewPassword\":\"$confirmpass\",\"Name\":\"$displayname\",\"JobTitle\":\"$title\",\"Tel\":\"$telephonenumber\",\"Tel_Extension\":\"$physicaldeliveryofficename\",\"Email\":\"$mail\"}",
	  CURLOPT_HTTPHEADER => array("Content-Type: application/json")
	));
	$response = curl_exec($curl);
	curl_close($curl);
	return $response;
}
// insert user of AD
function new_user($cn,$newpass,$confirmpass,$displayname,$title,$telephonenumber,$physicaldeliveryofficename,$mail,$ou){
	$url = "http://172.16.254.2/api/NewUser";
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
	  CURLOPT_POSTFIELDS =>"{\"Username\":\"$cn\",\"Password\":\"$newpass\",\"NewPassword\":\"$confirmpass\",\"Name\":\"$displayname\",\"JobTitle\":\"$title\",\"Tel\":\"$telephonenumber\",\"Tel_Extension\":\"$physicaldeliveryofficename\",\"Email\":\"$mail\",\"OU\":\"$ou\"}",
	  CURLOPT_HTTPHEADER => array("Content-Type: application/json")
	));
	$response = curl_exec($curl);
	curl_close($curl);
	return $response;
}

?>
