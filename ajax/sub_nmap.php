<?php
require("../login/function.php");
header('Content-type: text/html; charset=utf-8');

if(empty($_GET['target'])){
	phpAlert("沒有輸入");
	return 0;	
}

$target = $_GET['target'];
$output = shell_exec("/usr/bin/nmap $target");
echo "<pre>$output</pre>";
system("killall -q nmap");	//kill the task
