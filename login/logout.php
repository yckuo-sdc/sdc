<?php 
	session_start();
	//將session清空
	unset($_SESSION['account']);
	echo 'logout...';
	header("refresh:0;url=login.php"); 
?>
