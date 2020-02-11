<?php

require_once("function.php");
require_once("../mysql_connect.inc.php");
	
$account  = $_POST['account'];
$password = $_POST['password'];
 //特殊字元跳脫(NUL (ASCII 0), \n, \r, \, ', ", and Control-Z)
$account  = mysqli_real_escape_string($conn,$account);
$password = mysqli_real_escape_string($conn,$password);
//搜尋資料庫資料
$sql = "SELECT * FROM users where SSOID = '$account'";
$result = mysqli_query($conn,$sql);
$row = @mysqli_fetch_assoc($result);

if(checkAccountByLDAP($account, $password) && $row['SSOID'] == $account){
	session_start();
	$_SESSION['account']	= $account;
	$_SESSION['UserName']   = $row['UserName'];
	//echo $row['Level']; 
	if($row['Level'] == 2){
		$_SESSION['Level'] = $row['Level'];
	}
	header("refresh:0;url=../"); 
}else{
	session_start();
	$_SESSION["error"] = "invalid account or password";
	header("refresh:0;url=login.php"); 
}

?>
