<?php
$ad = new ad\api\WebadAPI();

if (!isset($_GET['isActive'])) {
    $_GET['isActive'] = "false";
}
echo createQueryStringTable($_GET);

foreach ($_GET as $key => $value) {
    ${$key} = str_replace("'", "\'", $value);  // transfer to local parameters & filtered char(')
}

$error = array();

switch ($type) {
	case "edituser":
		if ($new_password !== $confirm_password) {
            echo createWebadMessageBox($res = "兩次輸入密碼不同", "editUser");
			return ;
		}

		if (!empty($isActive)) {
			$res = $ad->changeState($cn, 'false', $isActive, 'false');
            echo createWebadMessageBox($res, "changeState");
			$userAction->logger('callFunction', 'ad/change_user_state(account=' . $cn . ')res=' . $res);
		}

		if (!empty($moveOU)) {
			$ou = explode("(", $moveOU);
			$ou = $ou[0];	
			$res = $ad->changeUserOU($cn,$ou);
            echo createWebadMessageBox($res, "changeUserOU");
			$userAction->logger('callFunction', 'ad/change_user_ou(account=' . $cn . ')res=' . $res);
		}

		$res = $ad->editUser($cn, $new_password, $displayname, $title, $telephonenumber, $physicaldeliveryofficename, $mail, $isActive);
        echo createWebadMessageBox($res, "editUser");
		$userAction->logger('callFunction', 'ad/edit_user(account=' . $cn . ')res=' . $res);

		break;
	case "newuser":
		if ($new_password !== $confirm_password) {
            echo createWebadMessageBox($res = "兩次輸入密碼不同", "insertUser");
			return ;
		}

		$ou = explode("(", $moveOU);
		$ou = $ou[0];	
		$res = $ad->insertUser($cn, $new_password, $displayname, $title, $telephonenumber, $physicaldeliveryofficename, $mail, $ou);
        echo createWebadMessageBox($res, "insertUser");
		$userAction->logger('callFunction', 'ad/new_user(account=' . $cn . ')res=' . $res);
		break;
	case "changecomputer":
		if (!empty($isActive)) {
			$res = $ad->changeState($cn, 'false', $isActive, 'false');
            echo createWebadMessageBox($res, "changeState");
			$userAction->logger('callFunction', 'ad/change_user_state(account=' . $cn . ')res=' . $res);
            if($res != '"1."'){
                $error[] = $res;
            }
		}
		if (!empty($moveOU)) {
			$ou = explode("(", $moveOU);
			$ou = $ou[0];	
			$res = $ad->changeComputerOU($cn, $ou, $isYonghua);
            echo createWebadMessageBox($res, "changeComputerOU");
			$userAction->logger('callFunction', 'ad/change_computer_ou(account=' . $cn . ')res=' . $res);
            if($res != '"1."'){
                $error[] = $res;
            }
		}
		break;
	case "editou":
        $upperou = explode("(", $upperou);
        $upperou = $upperou[0];	
		$res = $ad->editOU($upperou, $name, $description);
        echo createWebadMessageBox($res, "editOU");
		$userAction->logger('callFunction', 'ad/edit_ou(name=' . $name . ')res=' . $res);
		break;
	case "newou":
        $upperou = explode("(", $upperou);
        $upperou = $upperou[0];	
		$res = $ad->insertOU($upperou, $name, $description);
        echo createWebadMessageBox($res, "insertOU");
		$userAction->logger('callFunction', 'ad/insert_ou(name=' . $name . ')res=' . $res);
		break;
    default:
}

// ajax
if (!empty($ajax)) {
    return;
}

// non-ajax
if (empty($error)) {
    $flash->success("編輯成功");
} else {
	foreach($error as $e){
		$flash->error($e);
	}
}

header("Location: " . $_SERVER['HTTP_REFERER']); 

function createQueryStringTable($query_string) {
    $hidden_keys = array("_", "new_password", "confirm_password");
    $html = "";
    $html .= "<table>";
    $html .= "<tbody>";

    foreach($query_string as $key => $value) {
        if(!in_array($key, $hidden_keys)) {
            $html .= "<tr>";
                $html .= "<td>" . $key . "</td>";
                $html .= "<td>" . $value . "</td>";
            $html .= "</tr>";
        }
    }

    $html .= "</tbody>";
    $html .= "<table>";

    return $html;
}
