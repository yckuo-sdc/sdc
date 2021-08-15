<?php 
if(!$userValidator->isLogin()){
    header("Location: ./", TRUE, 401);
	echo "401 Unauthorized";
    return;
}	

$account = $_SESSION['account'];
$userAction->logger('pageSwitch', $_SERVER['REQUEST_URI']); 

$subpage = strtolower($route->getParameter(2));
$controller_array = scandir('controller/ajax');
$controller_array = array_change_key_case($controller_array, CASE_LOWER);

if (in_array($subpage.'.php', $controller_array)) {
	  require 'controller/ajax/'.$subpage.'.php';
}else{
	  require 'controller/404.php';
}
