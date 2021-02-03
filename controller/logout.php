<?php 
$db = Database::get();
$account = isset($_SESSION['account']) ? $_SESSION['account'] : '';
saveAction($db, 'logout', $_SERVER['REMOTE_ADDR'], $account, $_SERVER['REQUEST_URI']);
$duration = 3600*24*30; //3600sec*24hour*30day
setcookie('rememberme', '', time()-$duration, '/');
session_unset();  //It deletes only the variables from session and session still exists. Only data is truncated.
session_destroy();  //destroys all of the data associated with the current session

echo 'logout...';
header("Location: /login"); 
