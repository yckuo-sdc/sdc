<?php
// connect to AD server
$ldapconn = ldap_connect(LDAP::HOST) or die("Could not connect to LDAP server.");
$set = ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
if($ldapconn){
	//bind user
	$ldap_bd = ldap_bind($ldapconn, LDAP::USERNAME . "@" . LDAP::DOMAIN, LDAP::PASSWORD);
	$base_dn = "cn=Computers,dc=tainan,dc=gov,dc=tw";
	traverseOU($ldapconn,$base_dn,"Computers","網域中建立新電腦帳戶的預設位置");
	$base_dn = "ou=TainanComputer,dc=tainan,dc=gov,dc=tw";
	traverseOU($ldapconn,$base_dn,"TainanComputer","永華及民治公務個人電腦");
}
