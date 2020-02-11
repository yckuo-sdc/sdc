<?php
	// connect to AD server
	require("../ldap_admin_config.inc.php");
	require("../login/function.php");
	$ldapconn = ldap_connect($host_ip) or die("Could not connect to LDAP server.");
	$set = ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
	$base_dn = "ou=TainanComputer,dc=tainan,dc=gov,dc=tw";
	if($ldapconn){
		//bind user
		$ldap_bd = ldap_bind($ldapconn,$account."@".$host_dn,$password);
		myRecursiveFunction($ldapconn,$base_dn,"TainanComputer","永華及民治公務個人電腦");
	}
?>
