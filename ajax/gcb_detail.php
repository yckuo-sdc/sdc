<?php
use gcb\api as gcb;
require 'gcb_api.php';
require '../vendor/autoload.php';
session_start(); 
if(!isLogin()) return;

if( isset($_GET['action']) && isset($_GET['id']) ){
	$action = $_GET["action"];
	$id = $_GET["id"];
	$api_key = "u3mOZuf8lvZYps210BD5vA";
	$token = gcb\get_access_token($api_key);
	//判斷aciton
	switch($action){
		case 'gscan':
			$res = gcb\get_gscan_result($token,$id);
			echo "<pre>".json_encode(json_decode($res),JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)."</pre>";
			break;
		case 'detail':
			$res = gcb\get_client_detail($token,$id);
			echo "<pre>".json_encode(json_decode($res),JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)."</pre>";
			break;
	}
}
