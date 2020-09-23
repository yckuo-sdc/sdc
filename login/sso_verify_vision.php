<?php
if(!empty($_GET)){ 
	require_once("function.php");
	require '../libraries/DatabasePDO.php';
	$db = Database::get();
	$apcode = 'sdc-iss';
	if(!isset($_GET["action"]))	$action = "";
	else				$action = $_GET["action"];
	switch($action){
		case "logout":
			session_start();
			unset($_SESSION['account']);
			echo "logout";
			break;
		default:
			$srcToken = $_GET["token"];
			if(!isset($_GET["mainpage"]))	$mainpage 	= "";
			else							$mainpage 	= $_GET["mainpage"];
			if(!isset($_GET["subpage"]))	$subpage	= "";
			else							$subpage 	= $_GET["subpage"];
			$encryToken = hash_hmac('sha256', $srcToken, $apcode);
			$url = "http://vision.tainan.gov.tw/common/sso_verify.php";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array("token"=>$encryToken))); 
			$account = trim(curl_exec($ch)); 
			curl_close($ch);
			
			$table = "users"; // 設定你想查詢資料的資料表
			$condition = "SSOID = :SSOID";
			$user = $db->query($table, $condition, $order_by = 1, $fields = "*", $limit = "", [':SSOID'=>$account])[0];
				
			if($user['SSOID'] == $account){
				session_start();
				# fetch the present Session ID
				$sid = session_id();
				$_SESSION['account'] = $account;
				$_SESSION['UserName'] = $user['UserName'];
				$_SESSION['Level'] = $user['Level'];
				storeUserLogs2($db,'ssoLogin',$_SERVER['REMOTE_ADDR'],$account,$_SERVER['REQUEST_URI']);
				$args = array(
					'mainpage' => $mainpage,
					'subpage' => $subpage,
					'sid'	=> $sid
				);
				if( !empty($mainpage) && !empty($subpage) ){
					#header("Location: https://sdc-iss.tainan.gov.tw/index.php?".http_build_query($args)); 
					header("Location: /".$mainpage."/".$subpage."/?sid=".$sid); 
				}else{	
					header("Location: ../?sid=".$sid); 
				}	
			}else{
				header("refresh:0;url=error.html"); 
			}		
			break;
	}
}
