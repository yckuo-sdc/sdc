<?php
	// connect to AD server
	require("../ldap_admin_config.inc.php");
	require("../login/function.php");
	$ldapconn = ldap_connect($host_ip) or die("Could not connect to LDAP server.");
	$set = ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
	if($ldapconn){
		//bind user
		$ldap_bd = ldap_bind($ldapconn,$account."@".$host_dn,$password);
		$base_dn = "cn=Computers,dc=tainan,dc=gov,dc=tw";
		myRecursiveFunction($ldapconn,$base_dn,"Computers","這是在網域中建立之新電腦帳戶的預設位置");
		$base_dn = "ou=TainanComputer,dc=tainan,dc=gov,dc=tw";
		myRecursiveFunction($ldapconn,$base_dn,"TainanComputer","永華及民治公務個人電腦");
	}
?>
