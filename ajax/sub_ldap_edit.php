<?php
	use ad\api as ad;
	require_once("ad_api.php");
	require_once("../mysql_connect.inc.php");
	require_once("../ldap_admin_config.inc.php");
	session_start(); 
	if(!verifyBySession_Cookie("account")){
		return 0;
	}
	header('Content-type: text/html; charset=utf-8');
	echo "<table><tbody>";
	foreach ($_GET as $key => $value) {
		//過濾特殊字元(')
		$$key=str_replace("'","\'",$value);
		if( $key != "_" && $key !="pwd_changed" && $key !="new_password" && $key !="confirm_password"){
			echo "<tr>";
			echo "<td>".$key."</td>";
			echo "<td>".$value."</td>";
			echo "</tr>";
		}	
	}
	echo "</tbody></table>";
	//check if active is exist 
	if($isActive !== "undefined") $type ="changestate";
	switch($type){
		case "edituser":
			if($new_password!=$confirm_password){
				echo "failed:兩次輸入密碼不同!<br>";
				return 0;
			}
			$res = ad\edit_user($cn,$new_password,$confirm_password,$displayname,$title,$telephonenumber,$physicaldeliveryofficename,$mail);
			echo "edituser 執行結果：".$res;
			storeUserLogs($conn,'callFunction',$_SERVER['REMOTE_ADDR'],$_SESSION['account'],'ad/edit_user(account='.$cn.')res='.$res,date('Y-m-d h:i:s'));
			if(!empty($organizationalUnit)){
				$ou = explode("(", $organizationalUnit);
				$ou = $ou[0];	
				$res = ad\change_user_ou($cn,$ou);
				echo "change_user_ou 執行結果：".$res;
				storeUserLogs($conn,'callFunction',$_SERVER['REMOTE_ADDR'],$_SESSION['account'],'ad/change_user_ou(account='.$cn.')res='.$res,date('Y-m-d h:i:s'));
			}
			break;
		case "newuser":
			if($new_password!=$confirm_password){
				echo "failed:兩次輸入密碼不同!<br>";
				return 0;
			}
			$ou = explode("(", $organizationalUnit);
			$ou = $ou[0];	
			$res = ad\new_user($cn,$new_password,$confirm_password,$displayname,$title,$telephonenumber,$physicaldeliveryofficename,$mail,$ou);
			echo "newuser 執行結果：".$res;
			storeUserLogs($conn,'callFunction',$_SERVER['REMOTE_ADDR'],$_SESSION['account'],'ad/new_user(account='.$cn.')res='.$res,date('Y-m-d h:i:s'));
			break;
		case "changestate":
			$res = ad\change_user_state($cn,'false',$isActive,'false');
			echo "changestate 執行結果：".$res;
			storeUserLogs($conn,'callFunction',$_SERVER['REMOTE_ADDR'],$_SESSION['account'],'ad/change_user_state(account='.$cn.')res='.$res,date('Y-m-d h:i:s'));
			break;
		case "changecomputer":
			$ou = explode("(", $organizationalUnit);
			$ou = $ou[0];	
			$res = ad\change_computer_ou($cn,$ou);
			echo "changecomputer 執行結果：".$res;
			storeUserLogs($conn,'callFunction',$_SERVER['REMOTE_ADDR'],$_SESSION['account'],'ad/change_computer_ou(account='.$cn.')res='.$res,date('Y-m-d h:i:s'));
			break;
	}
	$conn->close();
?>
