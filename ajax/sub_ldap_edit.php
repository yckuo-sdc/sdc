<?php
	use ad\api as ad;
	require_once("ad_api.php");
	require_once("../mysql_connect.inc.php");
	require_once("../ldap_admin_config.inc.php");
	session_start(); 
	if(verifyBySession_Cookie("account")){
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
				if($new_password==$confirm_password){
					$res = ad\edit_user($cn,$new_password,$confirm_password,$displayname,$title,$telephonenumber,$physicaldeliveryofficename,$mail);
					echo "執行結果：".$res;
					storeUserLogs($conn,'callFunction',$_SERVER['REMOTE_ADDR'],$_SESSION['account'],'ad/edit_user(account='.$cn.')res='.$res,date('Y-m-d h:i:s'));
				}else{
					echo "failed:兩次輸入密碼不同!<br>";
				}
				break;
			case "newuser":
				if($new_password==$confirm_password){
					$ou = explode("(", $organizationalUnit);
					$ou = $ou[0];	
					$res = ad\new_user($cn,$new_password,$confirm_password,$displayname,$title,$telephonenumber,$physicaldeliveryofficename,$mail,$ou);
					echo "執行結果：".$res;
					storeUserLogs($conn,'callFunction',$_SERVER['REMOTE_ADDR'],$_SESSION['account'],'ad/new_user(account='.$cn.')res='.$res,date('Y-m-d h:i:s'));
				}else{
					echo "failed:兩次輸入密碼不同!<br>";
				}
				break;
			case "changestate":
				$res = ad\change_user_state($cn,'false',$isActive,'false');
				echo "執行結果：".$res;
				storeUserLogs($conn,'callFunction',$_SERVER['REMOTE_ADDR'],$_SESSION['account'],'ad/change_user_state(account='.$cn.')res='.$res,date('Y-m-d h:i:s'));
				break;
		}
		$conn->close();

		/*
		// connect to AD server
		$ldapconn = ldap_connect($host_ip) or die("Could not connect to LDAP server.");
		$set = ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
		if($ldapconn){
			//bind user
			$ldap_bd = ldap_bind($ldapconn,$account."@".$host_dn,$password);
			$newData = array();

			echo "<table><tbody>";
			foreach ($_GET as $key => $value) {
				//過濾特殊字元(')
				$$key=str_replace("'","\'",$value);
				if($key != "distinguishedname" && $key != "_" && $key !="pwd_changed" && $key !="new_password" && $key !="confirm_password"){
					echo "<tr>";
					echo "<td>".$key."</td>";
					echo "<td>".$value."</td>";
					echo "</tr>";
				}	
			}
			echo "</tbody></table>";
			if($new_password==$confirm_password){
				$res = ad\edit_user($cn,$new_password,$confirm_password,$displayname,$title,$telephonenumber,$physicaldeliveryofficename,$mail);
				echo "執行結果：".$res;
			}
		}	
		ldap_close($ldapconn);*/
	}
?>
