<?php
use PHPMailer\PHPMailer\POP3;

if(!isset($_POST['submit'])) {
	header("Location: /logout"); 
	exit();
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

	$table = "users";	
	$condition = "SSOID = :SSOID";
	$user = $db->query($table, $condition, $order_by = 1, $fields = "*", $limit = "", [':SSOID'=>$account]);
	
	$expire_time = 3600 * 24 * 30; //3600sec * 24hour * 30day

	switch($authentication){
		case "ad":
            $ld = new MyLDAP();
            //Bind Smart Developement Center OU
            $data_array = array();
            $data_array['base'] = "ou=395000300A, ou=395002900-, ou=395000000A, ou=TainanLocalUser, dc=tainan, dc=gov, dc=tw";
            $data_array['account'] = $account;
            $data_array['password'] = $password;
            $result = $ld->loginVerification($data_array, $user_attributes);

			if($result && !empty($user[0]['SSOID'])){
                session_regenerate_id(); //Prevent Session Fixation with changing session id
				$_SESSION['account'] = $account;
				$_SESSION['username'] = empty($user_attributes) ? $user[0]['UserName'] : $user_attributes['username'];
				$_SESSION['level'] = $user[0]['Level'];
				saveAction($db,'login', $_SERVER['REMOTE_ADDR'], $account, $_SERVER['REQUEST_URI']);
				if(isset($remember) && !empty($remember)){
					$SECRET_KEY = "security";
					$token = GenerateRandomToken(); // generate a token, should be 128 - 256 bit
					$cookie = $account . ':' . $token. ':' .$user[0]['UserName']. ':' .$user[0]['Level'];
					$mac = hash_hmac('sha256', $cookie, $SECRET_KEY);
					$cookie .= ':' . $mac;
					setcookie('rememberme', $cookie, time() + $expire_time, '/');
				}else{
					setcookie('rememberme', "", time() - $expire_time, '/');
				}
				header("Location: /");
				exit();	
			}
            break;
		case "mail":
			$pop = POP3::popBeforeSmtp('pop3.tainan.gov.tw', 110, 30, $account,$password, 1);
			if($pop && isset($user[0]['SSOID']) && !empty($user[0]['SSOID'])){
                session_regenerate_id(); //Prevent Session Fixation with changing session id
				$_SESSION['account'] = $account;
				$_SESSION['username'] = $user[0]['UserName'];
				$_SESSION['level'] = $user[0]['Level'];
				saveAction($db,'login', $_SERVER['REMOTE_ADDR'], $account, $_SERVER['REQUEST_URI']);
				if(!empty($remember)){
					$SECRET_KEY = "security";
					$token = GenerateRandomToken(); // generate a token, should be 128 - 256 bit
					$cookie = $account . ':' . $token;
					$mac = hash_hmac('sha256', $cookie, $SECRET_KEY);
					$cookie .= ':' . $mac;
					$cookie .= ':' . $user[0]['UserName'];
					$cookie .= ':' . $user[0]['Level'];
					setcookie('rememberme', $cookie, time() + $expire_time, '/');
				}else{
					setcookie('rememberme', "", time() - $expire_time, '/');
				}
				header("Location: /");
				exit();	
			}
            break;
	}

    $error[] = "invalid account or password";

}

if(!empty($error)) {
	foreach($error as $e){
		$flash->error($e);
	}
}

header("Location: ".$_SERVER['HTTP_REFERER']); 
