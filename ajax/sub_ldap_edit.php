<?php
	header('Content-type: text/html; charset=utf-8');
	echo "The object is revised as the following:<br>";
	// connect to AD server
	require("../ldap_admin_config.inc.php");
	$host = "tainan.gov.tw";
	$ldapconn = ldap_connect($host) or die("Could not connect to LDAP server.");
	$set = ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
	$ldap_bd = ldap_bind($ldapconn,$account."@".$host,$password);

	$newData = array();
	foreach ($_GET as $key => $value) {
		//過濾特殊字元(')
		$$key=str_replace("'","\'",$value);
		if($key != "distinguishedname" && $key != "_" ){
			echo $key.": ";
			echo $value."<br>";
			$newData[$key] = $value;
		}	
	}
	var_dump($newData);
	$accountDN = $distinguishedname;
	ldap_mod_replace($ldapconn, $accountDN, $newData);
	ldap_close($ldapconn);
?>
