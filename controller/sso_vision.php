<?php
if(empty($_GET)){
	return;
}	

$apcode = 'sdc-iss';

if (!isset($_GET['action'])) {
	$action = "";
} else {				
	$action = $_GET['action'];
}

switch($action){
	case "logout":
		session_start();
		unset($_SESSION['username']);
		echo "logout";
		break;
	default:
		$srcToken = $_GET['token'];
		
		$mainpage = strtolower($route->getParameter(2));
		$subpage = strtolower($route->getParameter(3));

		$encryToken = hash_hmac('sha256', $srcToken, $apcode);
		$url = "https://vision.tainan.gov.tw/common/sso_verify.php";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array("token" => $encryToken))); 
		$username = trim(curl_exec($ch)); 
		curl_close($ch);
		
		if(empty($username)) {
			header("Location: error"); 
		    return ;	
		}

		$table = "users";
		$condition = "SSOID = :SSOID";
		$user = $db->query($table, $condition, $order_by = 1, $fields = "*", $limit = "", [':SSOID' => $username])[0];
			
		if ($user['SSOID'] != $username) {
			header("Location: error");
		    return ;	
		}

		# fetch the present Session ID
		$sid = session_id();
		$_SESSION['username'] = $username;
		$_SESSION['displayname'] = $user['DisplayName'];
		$_SESSION['level'] = $user['Level'];
        $userAction->logger('ssoLogin', $_SERVER['REQUEST_URI']); 
		
		if( !empty($mainpage) && !empty($subpage) ) {
			header("Location: /" . $mainpage . "/" . $subpage . "/?sid=" . $sid); 
		} else {	
			header("Location: /?sid=" . $sid); 
		}	
		
		break;
}
