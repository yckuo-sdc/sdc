<?php
use PHPMailer\PHPMailer\POP3;

if(!isset($_POST['submit'])) {
	header("Location: /logout"); 
	return;
}

$error = array(); 
$gump = new GUMP();
$_POST = $gump->sanitize($_POST); 
$validation_rules_array = array(
	'account'    => 'required|alpha_numeric_dash',
	'password'   => 'required|min_len,8'
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
	//var_dump($validated_data);	

	$table = "users";	
	$condition = "SSOID = :SSOID";
	$user = $db->query($table, $condition, $order_by = 1, $fields = "*", $limit = "", [':SSOID'=>$account]);
	
	$duration = 3600*24*30; //3600sec*24hour*30day

	switch($authentication){
		case "ad":
			if(checkAccountByLDAP($account, $password) && isset($user[0]['SSOID']) && !empty($user[0]['SSOID'])){
				$_SESSION['account'] = $account;
				$_SESSION['UserName'] = $user[0]['UserName'];
				$_SESSION['Level'] = $user[0]['Level'];
				saveAction($db,'login', $_SERVER['REMOTE_ADDR'], $account, $_SERVER['REQUEST_URI']);
				if(isset($remember) && !empty($remember)){
					$SECRET_KEY = "security";
					$token = GenerateRandomToken(); // generate a token, should be 128 - 256 bit
					$cookie = $account . ':' . $token. ':' .$user[0]['UserName']. ':' .$user[0]['Level'];
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
			if($pop && isset($user[0]['SSOID']) && !empty($user[0]['SSOID'])){
				$_SESSION['account'] = $account;
				$_SESSION['UserName'] = $user[0]['UserName'];
				$_SESSION['Level'] = $user[0]['Level'];
				saveAction($db,'login', $_SERVER['REMOTE_ADDR'], $account, $_SERVER['REQUEST_URI']);
				if(!empty($remember)){
					$SECRET_KEY = "security";
					$token = GenerateRandomToken(); // generate a token, should be 128 - 256 bit
					$cookie = $account . ':' . $token;
					$mac = hash_hmac('sha256', $cookie, $SECRET_KEY);
					$cookie .= ':' . $mac;
					$cookie .= ':' . $user[0]['UserName'];
					$cookie .= ':' . $user[0]['Level'];
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

if(isset($error) AND count($error) > 0){
	foreach($error as $e){
		$flash->error($e);
	}
}

header("Location: ".$_SERVER['HTTP_REFERER']); 
