<?php
	header('Content-type: text/html; charset=utf-8');
	include("../login/function.php");
	if(!empty($_GET['ip']) && !empty($_GET['type'])){
		$ip  		   = rawurldecode($_GET['ip']);
		$type  		   = $_GET['type'];
		echo $ip."<br>";
		echo $type."<br>";
		if($type=="block"){
			$command = escapeshellcmd("/usr/bin/python3 /home/yckuo/python/selenium/dr_ip_block_host.py ".$ip);
			$output = exec($command);
			//$output = shell_exec("/usr/bin/python3 /home/yckuo/python/selenium/dr_ip_block_host.py ".$ip);
			//$output = shell_exec("/usr/bin/python3 -V");
		}elseif($type=="unblock"){
			$command = escapeshellcmd("/usr/bin/python3 /home/yckuo/python/selenium/dr_ip_unblock_host.py ".$ip);
			$output = exec($command);
		}
		echo $command."<br>";
		echo $output."<br>";
	}else{
		phpAlert("沒有輸入");
	}

?>
