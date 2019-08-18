<?php

include("function.php");
include("../mysql_connect.inc.php");
	
$account = $_POST['account'];
$password = $_POST['password'];
//搜尋資料庫資料
$sql = "SELECT * FROM users where SSOID = '$account'";
$result = mysqli_query($conn,$sql);
$row = @mysqli_fetch_assoc($result);
if(checkAccountByLDAP($account, $password) && $row['SSOID'] == $account){
	session_start();
	$_SESSION['account']	= $account;
	$_SESSION['UserName']   = $row['UserName'];
	//header("refresh:0;url=admin.php"); 
	header("refresh:0;url=../"); 
}else{
	//echo "invalid account or password";
	session_start();
	$_SESSION["error"] = "invalid account or password";
	header("refresh:0;url=login.php"); 
}

?>
