<?php
	function verifyBySession($var){
		if(isset($_SESSION[$var])){
			return true;
		}
		else{
			echo 'You Do Not Have Permission To Access!';
			header("Location:login/login.php"); 
			return false;
		}
	}	
		
	function checkAccountByLDAP($user, $ldappass){
		$ldaphost = "tainan.gov.tw";
		$ldapconn = ldap_connect($ldaphost);
		$ldaprdn = $user . "@" . $ldaphost;
		ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
		ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);
		if ($ldapconn){
			//binding to ldap server
			//'@' removes the warning
			@$ldapbind = ldap_bind($ldapconn, $ldaprdn, $ldappass);
			// verify binding
			if ($ldapbind) {
				//echo "LDAP bind successful...";
				ldap_close($ldapconn);
				return true;
			} else {
				//echo "LDAP bind failed...";														
				ldap_close($ldapconn);
				return false;	
			}
		}else{
			ldap_close($ldapconn);
			return false;	
		}	
	}
?>
