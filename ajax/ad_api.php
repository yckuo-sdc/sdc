<?php
namespace ad\api;
include("../login/function.php");

//edit user of AD
function edit_user($cn,$newpass,$confirmpass,$displayname,$title,$telephonenumber,$physicaldeliveryofficename,$mail){
	$host = "172.16.254.2";
	$url = "http://".$host."/api/EditUser";
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
	$host = "172.16.254.2";
	$url = "http://".$host."/api/NewUser";
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

// change user's or computer's state of AD
function change_user_state($cn,$PasswordChangeNextTime,$isActive,$isLocked){
	$host = "172.16.254.2";
	$url = "http://".$host."/api/ChangeUserState";
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
	  CURLOPT_POSTFIELDS =>"{\"Username\":\"$cn\",\"ComputerName\":\"$cn\",\"PasswordChangeNextTime\":$PasswordChangeNextTime,\"isActive\":$isActive,\"isLocked\":$isLocked}",
	  CURLOPT_HTTPHEADER => array("Content-Type: application/json")
	));
	$response = curl_exec($curl);
	curl_close($curl);
	return $response;
}

// change computer's OU
function change_computer_ou($cn,$ou){
	$host = "172.16.254.2";
	$url = "http://".$host."/api/ChangeComputerOU";
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
	  CURLOPT_POSTFIELDS =>"{\"Username\":\"$cn\",\"ComputerName\":\"$cn\",\"UpperOU\":\"$ou\"}",
	  CURLOPT_HTTPHEADER => array("Content-Type: application/json")
	));
	$response = curl_exec($curl);
	curl_close($curl);
	return $response;
}

function change_computer_ou2($cn,$ou,$upper_ou){
	$host = "172.16.254.2";
	$url = "http://".$host."/api/ChangeComputerOU";
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
	  CURLOPT_POSTFIELDS =>"{\"Username\":\"$cn\",\"ComputerName\":\"$cn\",\"OU\":\"$ou\",\"UpperOU\":\"$upper_ou\"}",
	  CURLOPT_HTTPHEADER => array("Content-Type: application/json")
	));
	$response = curl_exec($curl);
	curl_close($curl);
	return $response;
}

//change ou
function change_user_ou($cn,$ou){
	$host = "172.16.254.2";
	$url = "http://".$host."/api/ChangeOU";
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
	  CURLOPT_POSTFIELDS =>"{\"Username\":\"$cn\",\"OU\":\"$ou\"}",
	  CURLOPT_HTTPHEADER => array("Content-Type: application/json")
	));
	$response = curl_exec($curl);
	curl_close($curl);
	return $response;
}
	#$res = change_user_ou('yckuo','395000331-');
	#echo "執行結果：".$res;
