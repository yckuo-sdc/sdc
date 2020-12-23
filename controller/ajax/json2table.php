<?php
use gcb\api as gcb;
require 'gcb_api.php';

if( isset($_GET['action']) && isset($_GET['id']) ){
	$action = $_GET["action"];
	$id = $_GET["id"];
	$api_key = "u3mOZuf8lvZYps210BD5vA";
	$token = gcb\get_access_token($api_key);
	switch($action){
		case 'gscan':
			$res = gcb\get_gscan_result($token,$id);
            //$res = json_encode(json_decode($res),JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
			//echo "<pre>".str_replace(array('"IsPass"', '"Enabled"'), array('<font color="#e03997">"IsPass"</font>', '<font color="#2185d0">"Enabled"</font>'), $res)."<pre>";
			echo json_encode(json_decode($res),JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
			break;
		case 'detail':
			$res = gcb\get_client_detail($token,$id);
			echo "<pre>".json_encode(json_decode($res),JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)."</pre>";
			break;
	}
}
