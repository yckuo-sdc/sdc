<?php
require_once __DIR__ .'/../../vendor/autoload.php';
$ad = new ad\api\WebadAPI();

echo "<table><tbody>";
foreach ($_GET as $key => $value) {
	$$key = str_replace("'","\'",$value);  //過濾特殊字元(')
	if( $key != "_"  && $key !="new_password" && $key !="confirm_password"){
		echo "<tr>";
		echo "<td>".$key."</td>";
		echo "<td>".$value."</td>";
		echo "</tr>";
	}	
}
echo "</tbody></table>";

switch($type){
	case "edituser":
		if(!empty($isActive)){
			$res = $ad->changeState($cn,'false',$isActive,'false');
			echo "changestate 執行結果：".$res."<br>";
			saveAction($db,'callFunction',$_SERVER['REMOTE_ADDR'],$_SESSION['account'],'ad/change_user_state(account='.$cn.')res='.$res);
		}
		if(!empty($organizationalUnit)){
			$ou = explode("(", $organizationalUnit);
			$ou = $ou[0];	
			$res = $ad->changeUserOU($cn,$ou);
			echo "change_user_ou 執行結果：".$res."<br>";
			saveAction($db,'callFunction',$_SERVER['REMOTE_ADDR'],$_SESSION['account'],'ad/change_user_ou(account='.$cn.')res='.$res);
		}
		if($new_password!=$confirm_password){
			echo "failed:兩次輸入密碼不同!<br>";
			return ;
		}
		$res = $ad->editUser($cn,$new_password,$confirm_password,$displayname,$title,$telephonenumber,$physicaldeliveryofficename,$mail,$isActive);
		echo "edituser 執行結果：".$res."<br>";
		saveAction($db,'callFunction',$_SERVER['REMOTE_ADDR'],$_SESSION['account'],'ad/edit_user(account='.$cn.')res='.$res);
		break;
	case "newuser":
		if($new_password!=$confirm_password){
			echo "failed:兩次輸入密碼不同!<br>";
			return 0;
		}
		$ou = explode("(", $organizationalUnit);
		$ou = $ou[0];	
		$res = $ad->insertUser($cn,$new_password,$confirm_password,$displayname,$title,$telephonenumber,$physicaldeliveryofficename,$mail,$ou);
		echo "newuser 執行結果：".$res."<br>";
		saveAction($db,'callFunction',$_SERVER['REMOTE_ADDR'],$_SESSION['account'],'ad/new_user(account='.$cn.')res='.$res);
		break;
	case "changecomputer":
		if(!empty($isActive)){
			$res = $ad->changeState($cn,'false',$isActive,'false');
			echo "changestate 執行結果：".$res."<br>";
			saveAction($db,'callFunction',$_SERVER['REMOTE_ADDR'],$_SESSION['account'],'ad/change_user_state(account='.$cn.')res='.$res);
		}
		if(!empty($organizationalUnit)){
			$ou = explode("(", $organizationalUnit);
			$ou = $ou[0];	
			$res = $ad->changeComputerOU($cn,$ou,$isYonghua);
			echo "changecomputer 執行結果：".$res."<br>";
			saveAction($db,'callFunction',$_SERVER['REMOTE_ADDR'],$_SESSION['account'],'ad/change_computer_ou(account='.$cn.')res='.$res);
		}
		break;
}
