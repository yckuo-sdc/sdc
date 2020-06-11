<?php
	function GenerateRandomToken(){
		return md5(uniqid(rand(), true));
	}

	function verifyBySession_Cookie($var){
		//檢查session是不是為空 
		if(!isset($_SESSION[$var])){ 
			//使用者選擇了記住登入狀態
			//echo isset($_COOKIE['account']);
			//echo isset($_COOKIE['password']);
			//echo isset($_COOKIE['UserName']);
			
			if(isset($_COOKIE['rememberme'])){
			//if(isset($_COOKIE['account']) && isset($_COOKIE['UserName'])){
				$SECRET_KEY = "security";
				list ($user, $token, $mac, $UserName, $Level) = explode(':', $_COOKIE['rememberme']);
				//$account = $_COOKIE['account'];	
				//$UserName = $_COOKIE['UserName'];
				if (hash_equals(hash_hmac('sha256', $user . ':' . $token, $SECRET_KEY), $mac)) {
				//if(!empty($account)  && !empty($UserName)){			
					//使用者名稱和密碼對了，把使用者的個人資料放到session裡面 
					$_SESSION['account'] = $user;   
					$_SESSION['UserName'] = $UserName;
					$_SESSION['Level'] = $Level;
				    //echo "a";	
					return true;	
				}else{
				    echo "b";	
					echo 'You Do Not Have Permission To Access!';
					header("Location: https://sdc-iss.tainan.gov.tw/login/login.php"); 
				}
			}else{  //如果session為空，並且使用者沒有選擇記錄登入狀 
				echo "c";	
				echo 'You Do Not Have Permission To Access!';
				header("Location: https://sdc-iss.tainan.gov.tw/login/login.php"); 
			} 
		}else{
			//echo "d";	
			return true;
		}	
	}	

	function verifyBySession($var){
		if(isset($_SESSION[$var])){
			return true;
		}
		else{
			echo 'You Do Not Have Permission To Access!';
			header("Location:login/login.php"); 
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
	
	//store user's action to DB's log table	
	function storeUserLogs($conn,$type,$ip,$user,$msg,$time){
		//特殊字元跳脫(NUL (ASCII 0), \n, \r, \, ', ", and Control-Z)
		$type= mysqli_real_escape_string($conn,$type);
		$ip= mysqli_real_escape_string($conn,$ip);
		$user= mysqli_real_escape_string($conn,$user);
		$msg= mysqli_real_escape_string($conn,$msg);
		$time= mysqli_real_escape_string($conn,$time);
		$sql = "INSERT INTO logs(type,ip,user,msg,time) VALUES('".$type."','".$ip."','".$user."','".$msg."','".$time."')";
		mysqli_query($conn,$sql);
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

	function pagination($prev_page,$next_page,$lower_bound,$upper_bound,$Totalpages,$mainpage,$subpage,$tab,$pages,$sort) {
		$result ="";
		$href = ($tab==0)?"mainpage=".$mainpage."&subpage=".$subpage."&page=":"mainpage=".$mainpage."&subpage=".$subpage."&tab=".$tab."&page=";
		$href = ($sort=="")?"?".$href:"?sort=".$sort."&".$href;

		//The href-link of bottom pages
		$result .="<div class='ui pagination menu'>";	
		$result .="<a class='item test' href='".$href."1'>首頁</a>";
		$result .="<a class='item test' href='".$href.$prev_page."'> ← </a>";
		for ($j = $lower_bound; $j <= $upper_bound ;$j++){
			if($j == $pages){
				$result .="<a class='active item bold' href='".$href.$j."'>".$j."</a>";
			}else{
				$result .="<a class='item test' href='".$href.$j."'>".$j."</a>";
			}
		}
		$result .="<a class='item test' href='".$href.$next_page."'> → </a>";		
		//last page
		$result .="<a class='item test' href='".$href.$Totalpages."'>末頁 </a>";		
		$result .="</div>";
	   
		//The mobile href-link of bottom pages
		$result .="<div class='ui pagination menu mobile'>";	
			$result .="<a class='item test' href='".$href.$prev_page."'> ← </a>";
			$result .="<a class='active item bold' href='".$href.$pages."'>(".$pages."/".$Totalpages.")</a>";
			$result .="<a class='item test' href='".$href.$next_page."'> → </a>";		
		$result .="</div>";
		return $result;
	}

	// For Windows NT Time convert to UnixTimestamp
	function WindowsTime2UnixTime($WindowsTime){
		$UnixTime = $WindowsTime/10000000-11644473600;
		return $UnxiTime; 
	}
	// For Windows NT Time convert to UnixTimestamp
	function WindowsTime2DateTime($WindowsTime){
		$UnixTime = $WindowsTime/10000000-11644473600;
		return date('Y-m-d H:i:s',$UnixTime); 
	}
	
	// check the disabled ad account
	function dateConvert($str){
		if($str=='NULL' || $str ==''){
			return $str;
		}else{
			return date_format(date_create($str),'Y-m-d H:i:s');
		}
	}
	
	//處理 Nmap 掃描原始結果，將使用的Port、State、Service取出紀錄
	function NmapParser($input){
		$rows = explode("\n", $input);
		$rows = array_slice($rows, 6);
		$stack = array();
		foreach($rows as $key => $data){
			//get row data
			$row_data = explode(" ", preg_replace('/\s+/', ' ', $data));
			if($row_data[0]){
				$port_data = explode("/", $row_data[0]);
				$portStatus = strtolower(trim($row_data[1]));
				$portDesc = $row_data[2];
				if($portStatus == 'open' || $portStatus == 'filtered' || $portStatus == 'closed'){
					$portNum = $port_data[0];
					$TcpOrUdp = $port_data[1];
					array_push($stack, array($portNum,$TcpOrUdp,$portStatus,$portDesc));
				}
			}	
		}
		return $stack;
	} 
	// convert array to csv file ant download automatically
	function array_to_csv_download($array, $filename, $delimiter) {
		header('Content-Encoding: UTF-8');
		header('Content-type: text/csv; charset=UTF-8');
		header('Content-Disposition: attachment; filename="'.$filename.'";');
		//Clean the output buffer
		ob_clean();
		// open the "output" stream
		$f = fopen('php://output', 'w');
		fputs($f, "\xEF\xBB\xBF" ); // UTF-8 BOM !!!!!
		foreach ($array as $line) {
			fputcsv($f, $line);
		}
		fclose($f);
	}

	// check the disabled ad account
	function isAccountDisable($useraccountcontrol){
		$hexValue = dechex($useraccountcontrol);
		$len = strlen($hexValue);
		$accountdisable = $hexValue[$len-1];

		if($accountdisable == 2){
			return true;
		}else{
			return false;
		}
	}

?>
