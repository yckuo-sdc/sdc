<?php
require_once __DIR__ . '/../../vendor/autoload.php';

$ad = new ad\api\WebadAPI();
$isActive = "false";

?>
<table>
    <tbody>
        <?php foreach($_GET as $key => $value): ?>
            <?php $$key = str_replace("'", "\'", $value);  //過濾特殊字元(') ?>
            <?php if( $key != "_"  && $key !="new_password" && $key !="confirm_password" && $key !="isActive"): ?>
                <tr>
                    <td><?=$key?></td>
                    <td><?=$value?></td>
                </tr>
            <?php endif ?>	
        <?php endforeach ?>
        <tr>
            <td>isActive</td>
            <td><?=$isActive?></td>
        </tr>
    </tbody>
</table>

<?php
$error = array();

switch($type){
	case "edituser":
		if($new_password!=$confirm_password){
			echo "failed:兩次輸入密碼不同!<br>";
			return ;
		}

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

		$res = $ad->editUser($cn,$new_password,$confirm_password,$displayname,$title,$telephonenumber,$physicaldeliveryofficename,$mail,$isActive);
		echo "edituser 執行結果：".$res."<br>";
		saveAction($db,'callFunction',$_SERVER['REMOTE_ADDR'],$_SESSION['account'],'ad/edit_user(account='.$cn.')res='.$res);
		break;
	case "newuser":
		if($new_password!=$confirm_password){
			echo "failed:兩次輸入密碼不同!<br>";
			return ;
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
            if($res != '"1."'){
                $error[] = $res;
            }
		}
		if(!empty($organizationalUnit)){
			$ou = explode("(", $organizationalUnit);
			$ou = $ou[0];	
			$res = $ad->changeComputerOU($cn,$ou,$isYonghua);
			echo "changecomputer 執行結果：".$res."<br>";
			saveAction($db,'callFunction',$_SERVER['REMOTE_ADDR'],$_SESSION['account'],'ad/change_computer_ou(account='.$cn.')res='.$res);
            if($res != '"1."'){
                $error[] = $res;
            }
		}
		break;
}

if(!empty($ajax)){
    return;
}

if(empty($error)) {
    $flash->success("編輯成功");
}else {
	foreach($error as $e){
		$flash->error($e);
	}
}

header("Location: " . $_SERVER['HTTP_REFERER']); 
