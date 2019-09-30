<?php
	header('Content-type: text/html; charset=utf-8');
	//alert message
	function phpAlert($msg) {
		echo '<script type="text/javascript">alert("' . $msg . '")</script>';
	}

	if(!empty($_GET['target'])){
		$target = $_GET['target'];
		// connect to AD server
		require("../ldap_config.inc.php");
		$host = "tainan.gov.tw";
		$ldapconn = ldap_connect($host) or die("Could not connect to LDAP server.");
		$set = ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);

		$ldap_bd = ldap_bind($ldapconn,$account."@".$host,$password);
		$result = ldap_search($ldapconn,"ou=TainanLocalUser,dc=tainan,dc=gov,dc=tw","(CN=".$target.")") or die ("Error in query");

		$data = @ldap_get_entries($ldapconn,$result);

		echo $data["count"]. " entries returned\n";
		if($data["count"]!=0){
			for($i=0; $i<=$data["count"];$i++) {
				for ($j=0;$j<=$data[$i]["count"];$j++) {
					echo $data[$i][$j].": ".$data[$i][$data[$i][$j]][0]."\n<br>";
				}
			}
		}
		ldap_close($ldapconn);
	
	}else{
		phpAlert("沒有輸入");
	}
	

?>
