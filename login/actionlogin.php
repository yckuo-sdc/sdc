<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\POP3;
use PHPMailer\PHPMailer\SMTP;
//Load composer's autoloader
$local_path = "/var/www/html/utility/PHPMailer-master/";
require_once $local_path.'vendor/autoload.php';
require_once("function.php");
require_once("../mysql_connect.inc.php");

$account  	  = $_POST['account'];
$password 	  = $_POST['password'];
$verification = $_POST['verification'];
$remember	  = isset($_POST['remember'])? $_POST['remember']:"";

//特殊字元跳脫(NUL (ASCII 0), \n, \r, \, ', ", and Control-Z)
$account		= mysqli_real_escape_string($conn,$account);
$password	  	= mysqli_real_escape_string($conn,$password);
$verification 	= mysqli_real_escape_string($conn,$verification);
//搜尋資料庫資料
$sql = "SELECT * FROM users where SSOID = '$account'";
$result = mysqli_query($conn,$sql);
$row = @mysqli_fetch_assoc($result);

$duration = 3600*24*30; //3600sec*24hour*30day
switch($verification){
	case "ad":
		if(checkAccountByLDAP($account, $password) && $row['SSOID'] == $account){
			session_start();
			$_SESSION['account']	= $account;
			$_SESSION['UserName']   = $row['UserName'];
			$_SESSION['Level'] 		= $row['Level'];
			storeUserLogs($conn,'login',$_SERVER['REMOTE_ADDR'],$account,$_SERVER['REQUEST_URI'],date('Y-m-d h:i:s'));
			if(!empty($remember)){
				$SECRET_KEY = "security";
				$token = GenerateRandomToken(); // generate a token, should be 128 - 256 bit
				#$cookie = $account . ':' . $token;
				$cookie = $account . ':' . $token. ':' .$row['UserName']. ':' .$row['Level'];
				$mac = hash_hmac('sha256', $cookie, $SECRET_KEY);
				$cookie .= ':' . $mac;
				setcookie('rememberme', $cookie,time() + $duration,'/');
			}else{
				setcookie('rememberme', "",time() - $duration,'/');
			}
			header("Location: /index.php"); 
		}else{
			session_start();
			$_SESSION["error"] = "invalid account or password";
			header("Location: /login/login.php"); 
		}
		break;
	case "mail":
		$pop = POP3::popBeforeSmtp('pop3.tainan.gov.tw', 110, 30, $account,$password, 1);
		if($pop && $row['SSOID'] == $account){
			session_start();
			$_SESSION['account']	= $account;
			$_SESSION['UserName']   = $row['UserName'];
			$_SESSION['Level'] 		= $row['Level'];
			storeUserLogs($conn,'login',$_SERVER['REMOTE_ADDR'],$account,$_SERVER['REQUEST_URI'],date('Y-m-d h:i:s'));
			if(!empty($remember)){
				$SECRET_KEY = "security";
				$token = GenerateRandomToken(); // generate a token, should be 128 - 256 bit
				$cookie = $account . ':' . $token;
				$mac = hash_hmac('sha256', $cookie, $SECRET_KEY);
				$cookie .= ':' . $mac;
				$cookie .= ':' . $row['UserName'];
				$cookie .= ':' . $row['Level'];
				setcookie('rememberme', $cookie,time() + $duration,'/');
			}else{
				setcookie('rememberme', "",time() - $duration,'/');
			}
			header("Location: /index.php"); 
		}else{
			session_start();
			$_SESSION["error"] = "invalid account or password";
			header("Location: /login/login.php"); 
		}
		break;
}

?>
