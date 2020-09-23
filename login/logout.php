<?php 
require_once("function.php");
require '../libraries/DatabasePDO.php';

$db = Database::get();
session_start();
storeUserLogs2($db, 'logout', $_SERVER['REMOTE_ADDR'], $_SESSION['account'], $_SERVER['REQUEST_URI']);
$duration = 3600*24*30; //3600sec*24hour*30day
//將session清空
unset($_SESSION['account']);
unset($_SESSION['UserName']);
unset($_SESSION['Level']);
setcookie('rememberme', '', time()-$duration, '/');
echo 'logout...';
header("refresh:0;url=/"); 
