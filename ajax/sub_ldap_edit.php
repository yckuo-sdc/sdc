<?php
use ad\api as ad;
require 'ad_api.php';
require '../vendor/autoload.php';

session_start(); 
if(!verifyBySession_Cookie("account")){
	return ;
}
$db = Database::get();

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
if($isActive !== "") $type ="changestate";
switch($type){
	case "edituser":
		if($new_password!=$confirm_password){
			echo "failed:兩次輸入密碼不同!<br>";
			return ;
		}
		$res = ad\edit_user($cn,$new_password,$confirm_password,$displayname,$title,$telephonenumber,$physicaldeliveryofficename,$mail);
		echo "edituser 執行結果：".$res;
		storeUserLogs2($db,'callFunction',$_SERVER['REMOTE_ADDR'],$_SESSION['account'],'ad/edit_user(account='.$cn.')res='.$res);
		if(!empty($organizationalUnit)){
			$ou = explode("(", $organizationalUnit);
			$ou = $ou[0];	
			$res = ad\change_user_ou($cn,$ou);
			echo "change_user_ou 執行結果：".$res;
			storeUserLogs2($db,'callFunction',$_SERVER['REMOTE_ADDR'],$_SESSION['account'],'ad/change_user_ou(account='.$cn.')res='.$res);
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
		storeUserLogs2($db,'callFunction',$_SERVER['REMOTE_ADDR'],$_SESSION['account'],'ad/new_user(account='.$cn.')res='.$res);
		break;
	case "changestate":
		$res = ad\change_user_state($cn,'false',$isActive,'false');
		echo "changestate 執行結果：".$res;
		storeUserLogs2($db,'callFunction',$_SERVER['REMOTE_ADDR'],$_SESSION['account'],'ad/change_user_state(account='.$cn.')res='.$res);
		break;
	case "changecomputer":
		if(empty($organizationalUnit)){
			echo "failed:ou未輸入!<br>";
			return ;
		}
		$ou = explode("(", $organizationalUnit);
		$ou = $ou[0];	
		$res = ad\change_computer_ou($cn,$ou,$isYonghua);
		echo "changecomputer 執行結果：".$res;
		storeUserLogs2($db,'callFunction',$_SERVER['REMOTE_ADDR'],$_SESSION['account'],'ad/change_computer_ou(account='.$cn.')res='.$res);
		break;
}
