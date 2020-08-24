<?php 
require_once("function.php");
require_once("../mysql_connect.inc.php");
session_start();
storeUserLogs($conn, 'logout', $_SERVER['REMOTE_ADDR'], $_SESSION['account'], $_SERVER['REQUEST_URI'], date('Y-m-d h:i:s'));
$conn->close();
$duration = 3600*24*30; //3600sec*24hour*30day
//將session清空
unset($_SESSION['account']);
unset($_SESSION['UserName']);
unset($_SESSION['Level']);
setcookie('rememberme', '', time()-$duration, '/');
echo 'logout...';
header("refresh:0;url=/"); 
