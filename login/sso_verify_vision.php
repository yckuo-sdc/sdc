<?php
	if(!empty($_GET)){ 
		require_once("function.php");
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
				
				require_once("../mysql_connect.inc.php");	
				 //特殊字元跳脫(NUL (ASCII 0), \n, \r, \, ', ", and Control-Z)
				$account  = mysqli_real_escape_string($conn,$account);
				$sql = "SELECT * FROM users where SSOID = '$account'";
				$result = mysqli_query($conn,$sql);
				$row = @mysqli_fetch_assoc($result);
					
				if($row['SSOID'] == $account){
					session_start();
					# fetch the present Session ID
					$sid = session_id();
					$_SESSION['account']	= $account;
					$_SESSION['UserName']   = $row['UserName'];
					$_SESSION['Level'] = $row['Level'];
					storeUserLogs($conn,'ssoLogin',$_SERVER['REMOTE_ADDR'],$account,$_SERVER['REQUEST_URI'],date('Y-m-d h:i:s'));
					$conn->close();
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
					$conn->close();
					header("refresh:0;url=error.html"); 
				}		
				break;
		}
	}
?>
