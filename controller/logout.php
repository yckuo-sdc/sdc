<?php 
$db = Database::get();
$account = isset($_SESSION['account']) ? $_SESSION['account'] : '';
storeUserLogs2($db, 'logout', $_SERVER['REMOTE_ADDR'], $account, $_SERVER['REQUEST_URI']);
$duration = 3600*24*30; //3600sec*24hour*30day
setcookie('rememberme', '', time()-$duration, '/');
session_unset();		//將session清空
echo 'logout...';
header("Location: /login"); 
