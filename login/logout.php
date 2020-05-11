<?php 
	$duration = 3600*24*30; //3600sec*24hour*30day
	session_start();
	//將session清空
	unset($_SESSION['account']);
	unset($_SESSION['UserName']);
	unset($_SESSION['Level']);
	setcookie('rememberme', '', time()-$duration,'/'); 
	echo 'logout...';
	header("refresh:0;url=login.php"); 
?>
