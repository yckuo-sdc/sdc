<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\POP3;
use PHPMailer\PHPMailer\SMTP;

if(isset($_POST['submit'])) {

	$db = Database::get();

	$error = array(); 
	$gump = new GUMP();
	$_POST = $gump->sanitize($_POST); 
	$validation_rules_array = array(
		'account'    => 'required|alpha_numeric_dash',
		'password'   => 'required'
  	);
	$gump->validation_rules($validation_rules_array);
  	$filter_rules_array = array(
     	'account' => 'trim|sanitize_string',
      	'password' => 'trim',
  	);
  	$gump->filter_rules($filter_rules_array);
  	$validated_data = $gump->run($_POST);

  	if($validated_data === false) {
    	$error = $gump->get_readable_errors(false);
  	} else {
		foreach($validated_data as $key => $val){
			${$key} = $val;
		}
		
		$table = "users"; // 設定你想查詢資料的資料表
		$condition = "SSOID = :SSOID";
		$user = $db->query($table, $condition, $order_by = 1, $fields = "*", $limit = "", [':SSOID'=>$account])[0];

		$duration = 3600*24*30; //3600sec*24hour*30day
		switch($verification){
			case "ad":
				if(checkAccountByLDAP($account, $password) && $user['SSOID'] == $account){
					$_SESSION['account'] = $account;
					$_SESSION['UserName'] = $user['UserName'];
					$_SESSION['Level'] = $user['Level'];
					storeUserLogs2($db,'login', $_SERVER['REMOTE_ADDR'], $account, $_SERVER['REQUEST_URI']);
					if(isset($remember) && !empty($remember)){
						$SECRET_KEY = "security";
						$token = GenerateRandomToken(); // generate a token, should be 128 - 256 bit
						$cookie = $account . ':' . $token. ':' .$user['UserName']. ':' .$user['Level'];
						$mac = hash_hmac('sha256', $cookie, $SECRET_KEY);
						$cookie .= ':' . $mac;
						setcookie('rememberme', $cookie,time() + $duration,'/');
					}else{
						setcookie('rememberme', "",time() - $duration,'/');
					}
					header("Location: /"); 
				   	exit();	
				}else{
					$error[] = "invalid account or password";
				}
				break;
			case "mail":
				$pop = POP3::popBeforeSmtp('pop3.tainan.gov.tw', 110, 30, $account,$password, 1);
				if($pop && $user['SSOID'] == $account){
					$_SESSION['account'] = $account;
					$_SESSION['UserName'] = $user['UserName'];
					$_SESSION['Level'] = $user['Level'];
					storeUserLogs2($db,'login', $_SERVER['REMOTE_ADDR'], $account, $_SERVER['REQUEST_URI']);
					if(!empty($remember)){
						$SECRET_KEY = "security";
						$token = GenerateRandomToken(); // generate a token, should be 128 - 256 bit
						$cookie = $account . ':' . $token;
						$mac = hash_hmac('sha256', $cookie, $SECRET_KEY);
						$cookie .= ':' . $mac;
						$cookie .= ':' . $user['UserName'];
						$cookie .= ':' . $user['Level'];
						setcookie('rememberme', $cookie,time() + $duration,'/');
					}else{
						setcookie('rememberme', "",time() - $duration,'/');
					}
					header("Location: /");
				   	exit();	
				}else{
					$error[] = "invalid account or password";
				}
				break;
		}
	}
	$_SESSION["error"] = $error;
	header("Location: /login"); 
}
