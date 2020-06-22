<?php
use gcb\api as gcb;
include("gcb_api.php");
session_start(); 
if(verifyBySession_Cookie("account")){
	if( isset($_GET['action']) && isset($_GET['id']) ){
		$action = $_GET["action"];
		$id = $_GET["id"];
		$api_key = "u3mOZuf8lvZYps210BD5vA";
		$token = gcb\get_access_token($api_key);
		//判斷aciton
		switch($action){
			case 'gscan':
				//$url = "https://gcb.tainan.gov.tw/api/v1/gscan/result/".$id;
				$res = gcb\get_gscan_result($token,$id);
				echo "<pre>".json_encode(json_decode($res),JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)."</pre>";
				break;
			case 'detail':
				//$url = "https://gcb.tainan.gov.tw/api/v1/client/detail/".$id;
				$res = gcb\get_client_detail($token,$id);
				echo "<pre>".json_encode(json_decode($res),JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)."</pre>";
				break;
		}
	}
}
?>
