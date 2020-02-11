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

	function issetBySession($var){
		if(isset($_SESSION[$var])){
			return true;
		}
		else{
			return false;
		}
	}	
		
	function checkAccountByLDAP($user, $ldappass){
		$ldaphost = "tainan.gov.tw";
		$ldapconn = ldap_connect($ldaphost);
		//$ldapconn = ldap_connect("10.6.2.1");
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
	//LDAP recursive search and print
	function myRecursiveFunction($ldapconn,$base_dn,$ou_name,$ou_des) {
		$filter ="(objectClass=*)";
		$result = @ldap_list($ldapconn,$base_dn,$filter) or die ("Error in query");
		$num 	= @ldap_count_entries($ldapconn,$result);
		if($num==0){
			echo "<li><i class='folder icon'></i>".$ou_name."(".$ou_des.")</li>";	
			return;
		}else{
			echo "<li><i class='minus square outline icon'></i><i class='folder open icon'></i>".$ou_name."(".$ou_des.")";	
			// list all computers of base_dn
			$filter ="(objectClass=computer)";
			$result = @ldap_list($ldapconn,$base_dn,$filter) or die ("Error in query");
			$data 	= @ldap_get_entries($ldapconn,$result);
			$num 	= $data["count"];
			if($num!=0){
				echo "<ol>";
				for($i=0; $i<$num;$i++){
					echo "<li><i class='desktop icon'></i>".$data[$i]['cn'][0]."</li>";
				}
				echo "</ol>";
			}
			// list all sub_ou of base_dn
			$filter ="(objectClass=organizationalUnit)";
			$result = @ldap_list($ldapconn,$base_dn,$filter) or die ("Error in query");
			$data 	= @ldap_get_entries($ldapconn,$result);
			$num 	= $data["count"];
			if($num!=0){
				echo "<ol>";
				for($i=0; $i<$num;$i++){
					$sub_ou = $data[$i]["ou"][0];
					$sub_dn = $data[$i]["distinguishedname"][0];
					$sub_des = "";
					if(isset($data[$i]["description"][0])) $sub_des = $data[$i]["description"][0];
					// continue the recursion
					myRecursiveFunction($ldapconn,$sub_dn,$sub_ou,$sub_des);
				}
				echo "</ol>";
			}
			echo "</li>";
		}

	}
	
	
	//Alert message
	function phpAlert($msg) {
		echo '<script type="text/javascript">alert("' . $msg . '")</script>';
	}

	function getFullTextSearchSQL($conn,$table,$key) {
		$sql = "SELECT column_name FROM information_schema.columns WHERE table_name = '".$table."'";
		$result = mysqli_query($conn,$sql);
		$rowcount = mysqli_num_rows($result);
		$result_sql = "";
		$count = 0;

		while($row = mysqli_fetch_assoc($result)){
			if (++$count == $rowcount) {
				//last row
				$result_sql = $result_sql." ".$row['column_name']." LIKE '%".$key."%'";
			} else {
				//not last row
				$result_sql = $result_sql." ".$row['column_name']." LIKE '%".$key."%' OR";
			}
		}
		return $result_sql;
	}	

	function getPaginationSQL($sql,$per,$max_pages,$rowcount,$pages) {
		$Totalpages = ceil($rowcount / $per); 
		$lower_bound = ($pages <= $max_pages) ? 1 : $pages - $max_pages + 1;
		$upper_bound = ($pages <= $max_pages) ? min($max_pages,$Totalpages) : $pages;					
		$start = ($pages -1)*$per; //計算資料庫取資料範圍的開始值。
		if($pages == 1)					$offset = ($rowcount < $per) ? $rowcount : $per;
		elseif($pages == $Totalpages)	$offset = $rowcount - $start;
		else							$offset = $per;
					
		$prev_page  = ($pages > 1) ? $pages -1 : 1;
		$next_page  = ($pages < $Totalpages) ? $pages +1 : $Totalpages;	
		$result_sql = $sql." limit ".$start.",".$offset;

		return array($result_sql, $prev_page, $next_page, $lower_bound, $upper_bound, $Totalpages);
	}
?>
