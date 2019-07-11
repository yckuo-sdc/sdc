<?php
	header('Content-type: text/html; charset=utf-8');
	//alert message
	function phpAlert($msg) {
		echo '<script type="text/javascript">alert("' . $msg . '")</script>';
	}

	if(!empty($_GET['target'])){
		$target = $_GET['target'];
		//echo $target."<br>";
	
		$output = shell_exec("/usr/bin/nmap $target");
		echo "<pre>$output</pre>";
	
		//kill the task
		system("killall -q nmap");
	}else{
		phpAlert("沒有輸入");
	}
	

?>
