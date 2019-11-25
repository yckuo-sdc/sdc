<?php
	header('Content-type: text/html; charset=utf-8');
	// connect to AD server
	require("../ldap_admin_config.inc.php");
	$ldapconn = ldap_connect($host_ip) or die("Could not connect to LDAP server.");
	$set = ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
	if($ldapconn){
		//bind user
		$ldap_bd = ldap_bind($ldapconn,$account."@".$host_dn,$password);
		$newData = array();

		echo "<table border='1'><tbody>";
		foreach ($_GET as $key => $value) {
			//過濾特殊字元(')
			$$key=str_replace("'","\'",$value);
			if($key != "distinguishedname" && $key != "_" && $key !="pwd_changed"){
				echo "<tr>";
				echo "<td>".$key."</td>";
				echo "<td>".$value."</td>";
				echo "</tr>";
				$newData[$key] = $value;
			}	
		}
		echo "</tbody></table>";
		//var_dump($newData);
		$accountDN = $distinguishedname;
		if(ldap_mod_replace($ldapconn, $accountDN, $newData)){
			echo "The object is revised as the above!<br>";
		}else{
			echo "Error in modification!<br>";
		}
	}	
	ldap_close($ldapconn);
?>
