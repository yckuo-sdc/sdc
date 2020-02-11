<?php
	header('Content-type: text/html; charset=utf-8');
	//alert message
	function phpAlert($msg) {
		echo '<script type="text/javascript">alert("' . $msg . '")</script>';
	}

	if(!empty($_GET['target']) && !empty($_GET['account']) && !empty($_GET['protocol']) ){
		$target			= $_GET['target'];
		$account 		= $_GET['account'];
		$protocol 		= $_GET['protocol'];
		$one_pwd_mode 	= $_GET['one_pwd_mode'];
		//Set multi-thread 
		$thread 		= 4;
		if($one_pwd_mode == "yes"){
			$self_password = $_GET['self_password'];
			$cmd = "hydra -l ".$account." -p ".$self_password." ".$target." -V -t ".$thread." ".$protocol;
		}
		else{
			$cmd = "hydra -l ".$account." -P 100-most-common-passwords.txt ".$target." -V -t ".$thread." ".$protocol;
		}	
		system("killall -q hydra");
		echo $cmd;
		$output = shell_exec("/usr/bin/".$cmd);
		echo "<pre>$output</pre>";
	}else{
		phpAlert("沒有輸入");
	}
	

?>
