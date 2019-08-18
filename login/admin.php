<?php session_start(); ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
	include("function.php");
	if(verifyBySession("account")){
		//include("mysql_connect.inc.php");
		echo '<a href="logout.php">登出</a>  <br><br>';
		echo $_SESSION['UserName'];
	}
?>
