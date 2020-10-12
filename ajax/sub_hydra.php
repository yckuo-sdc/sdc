<?php
// input validation
foreach($_GET as $getkey => $val){
	if(empty($val)){
		echo "沒有輸入";
		return 0;
	}
	$$getkey = escapeshellcmd($val);
}

$thread = 4;
$pwd_dictionary = "../upload/hydra/100-most-common-passwords.txt";
if($one_pwd_mode == "yes"){
	$cmd = "hydra -l ".$account." -p ".$self_password." ".$target." -V -t ".$thread." ".$protocol;
}
else{
	$cmd = "hydra -l ".$account." -P ".$pwd_dictionary." ".$target." -V -t ".$thread." ".$protocol;
}	
system("killall -q hydra");
$output = shell_exec("/usr/bin/".$cmd);
echo "<pre>$output</pre>";
