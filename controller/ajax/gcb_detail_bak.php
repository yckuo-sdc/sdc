<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="/js/json2table.js"></script>
	 <style>
			th, td, p, input, h3 {
				font:15px 'Segoe UI';
			}
			table, th, td {
				border: solid 1px #ddd;
				border-collapse: collapse;
				padding: 2px 3px;
				text-align: center;
			}
			th {
				font-weight:bold;
			}
		</style>
</head>

<body>
    <div id="showData"></div>
</body>
<?php
//use gcb\api as gcb;
//require 'gcb_api.php';
/*
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
*/
