<!--network-->
<?php 
if(!isLogin()){
	header("Location: /logout"); 
	return;
}	

saveAction($db, 'pageSwitch', $_SERVER['REMOTE_ADDR'], $_SESSION['account'], $_SERVER['REQUEST_URI']);

$subpage = strtolower($route->getParameter(2));
$controller_array = scandir('controller/network');
$controller_array = array_change_key_case($controller_array, CASE_LOWER);

if (in_array($subpage.'.php', $controller_array)) {
	  require 'controller/network/'.$subpage.'.php';
}else{
	  require 'controller/network/search.php';
}
