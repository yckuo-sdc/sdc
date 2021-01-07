<?php
require_once __DIR__ .'/../../vendor/autoload.php';

$gcb = new gcb\api\RapixGCB();

if( isset($_GET['action']) && isset($_GET['id']) ){
	$action = $_GET["action"];
	$id = $_GET["id"];
	switch($action){
		case 'gscan':
			$res = $gcb->getGscanResult($id);
			//echo "<pre>".json_encode(json_decode($res),JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)."</pre>";
            $res = json_encode(json_decode($res),JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
			echo "<pre>".str_replace(array('"IsPass"', '"Enabled"'), array('<font color="#e03997"><b>"IsPass"</b></font>', '<font color="#2185d0"><b>"Enabled"</b></font>'), $res)."<pre>";
			break;
		case 'detail':
			$res = $gcb->getClientDetail($id);
			echo "<pre>".json_encode(json_decode($res),JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)."</pre>";
			break;
	}

}
