<?php
	require("../login/function.php");
	header('Content-type: text/html; charset=utf-8');

	if(empty($_GET['target']) || empty($_GET['account']) || empty($_GET['protocol']) ){
		phpAlert("沒有輸入");
		return 0;	
	}	
	
	$target	= $_GET['target'];
	$account = $_GET['account'];
	$protocol = $_GET['protocol'];
	$one_pwd_mode = $_GET['one_pwd_mode'];
	$thread = 4;
	$pwd_dictionary = "../upload/hydra/100-most-common-passwords.txt";
	if($one_pwd_mode == "yes"){
		$self_password = $_GET['self_password'];
		$cmd = "hydra -l ".$account." -p ".$self_password." ".$target." -V -t ".$thread." ".$protocol;
	}
	else{
		$cmd = "hydra -l ".$account." -P ".$pwd_dictionary." ".$target." -V -t ".$thread." ".$protocol;
	}	
	system("killall -q hydra");
	echo $cmd;
	$output = shell_exec("/usr/bin/".$cmd);
	echo "<pre>$output</pre>";
?>
