<?php
function GenerateRandomToken(){
	return md5(uniqid(rand(), true));
}

function isLogin(){
	if(isset($_SESSION['account'])) { 	//檢查session是否有值
		return true;
	}
	
	if(isset($_COOKIE['rememberme'])){	//使用者選擇記住登入狀態
		$SECRET_KEY = "security";
		list ($user, $token, $UserName, $Level, $mac) = explode(':', $_COOKIE['rememberme']);
		if (hash_equals(hash_hmac('sha256', $user . ':' . $token .':'. $UserName . ':' . $Level, $SECRET_KEY), $mac)) {	//使用者名稱和密碼對了，把使用者的個人資料放到session裡面 
			$_SESSION['account'] = $user;   
			$_SESSION['UserName'] = $UserName;
			$_SESSION['Level'] = $Level;
			$db = Database::get();
			saveAction($db,'rememberLogin',$_SERVER['REMOTE_ADDR'],$_SESSION['account'],$_SERVER['REQUEST_URI']);
			return true;	
		}
	}		
	
	//header("Location: /logout"); 
	return false;
}	

function checkAccountByLDAP($user, $ldappass){

	$ldapconn = ldap_connect(LDAP::ADDRESS);
	$ldaprdn = $user . "@" . LDAP::DOMAIN;
	ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
	ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);

	if ($ldapconn){
		$ldapbind = @ldap_bind($ldapconn, $ldaprdn, $ldappass);	// verify binding
		if ($ldapbind) {
			ldap_close($ldapconn);
			return true;
	    }
    }

	ldap_close($ldapconn);
	return false;	
}

//store user's action to DB's log table	
function saveAction($db, $type, $ip, $user, $msg){
	$time = date('Y-m-d H:i:s');
	
	$table = "logs"; // 設定你想新增資料的資料表
	$data_array['type'] = $type;
	$data_array['ip'] = $ip;
	$data_array['user'] = $user;
	$data_array['msg'] = $msg;
	$data_array['time'] = $time;
	$db->insert($table, $data_array);
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
        $filter ="(&(objectClass=computer)(cn=*PC*))";
		$filter ="(objectClass=computer)";
		$result = @ldap_list($ldapconn,$base_dn,$filter) or die ("Error in query");
		$data 	= @ldap_get_entries($ldapconn,$result);
		$num 	= $data["count"];
		if($num!=0){
			echo "<ol>";
			for($i=0; $i<$num;$i++){
				if(isAccountDisable($data[$i]['useraccountcontrol'][0])){
					echo "<li><i class='desktop icon'></i>".$data[$i]['cn'][0]."_已停用</li>";
				}else{
					echo "<li><i class='desktop icon'></i>".$data[$i]['cn'][0]."</li>";
				}
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
	$GMT = 8;	// Time zones with GMT+8
	if($str=='NULL' || $str ==''){
		return $str;
	}else{
		return date('Y-m-d H:i:s', strtotime($str) + $GMT * 3600);
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

// convert json to csv file
function jsonToCSV($json, $cfilename){
	//if (($json = file_get_contents($jfilename)) == false)
	//    die('Error reading json file...');
	$data = json_decode($json, true);
	$fp = fopen($cfilename, 'w');
	$header = false;
	foreach ($data as $row)
	{
		if (empty($header))
		{
			$header = array_keys($row);
			fputcsv($fp, $header);
			$header = array_flip($header);
		}
		fputcsv($fp, array_merge($header, $row));
	}
	fclose($fp);
	return 0;
}

// convert array to csv file
function array2csv($list){
	if (count($list) == 0) {
		   return null;
	}
	$fp = fopen('file.csv', 'w');
	fwrite($fp,"\xEF\xBB\xBF");
	foreach ($list as $fields) {
		fputcsv($fp, $fields);
	}
	fclose($fp);
	return true;
}

// convert array to csv file and download automatically
function array_to_csv_download($array, $filename, $delimiter) {
	header('Content-Encoding: UTF-8');
	header('Content-type: text/csv; charset=UTF-8');
	header('Content-Disposition: attachment; filename="'.$filename.'";');
	//Clean the output buffer
	ob_clean();
	// open the "output" stream
	$f = fopen('php://output', 'w');
	fputs($f, "\xEF\xBB\xBF" ); // UTF-8 BOM !!!!!
	fputcsv($f, array_keys($array[0]));
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

//get ou'description of AD
function get_ou_desc($dn,$ldapconn){
	$desc ="";
	$str_sec = explode(",",$dn);
	for($i=0;$i<2;$i++){
		if(substr_compare($str_sec[$i],"OU",0,2)==0){
			$result_ou = @ldap_search($ldapconn,"ou=TainanComputer,dc=tainan,dc=gov,dc=tw","(".$str_sec[$i].")");
			$data_ou = @ldap_get_entries($ldapconn,$result_ou);
			if(isset($data_ou[0]['description'][0]))	$desc = $desc.$data_ou[0]['description'][0];
		}
	}
	return $desc;
}

//get recursive ou'descriptions of AD
function get_ou_desc_recursive($dn,$ldapconn){
	$desc ="";
	$str_sec = explode(",",$dn);
	for($i=0;$i<count($str_sec);$i++){
		if(substr_compare($str_sec[$i],"OU",0,2)==0){
			$result_ou = @ldap_search($ldapconn,"ou=TainanComputer,dc=tainan,dc=gov,dc=tw","(".$str_sec[$i].")");
			$data_ou = @ldap_get_entries($ldapconn,$result_ou);
			if(isset($data_ou[0]['description'][0]))	$desc = $desc.$data_ou[0]['description'][0]."/";
		}
	}
	return $desc;
}

function formatBytes($bytes, $precision = 1) { 
    $units = array('B', 'KB', 'MB', 'GB', 'TB'); 
    $bytes = (int) $bytes;
    $bytes = max($bytes, 0); 
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
    $pow = min($pow, count($units) - 1); 
	$bytes /= pow(1024, $pow);
	return round($bytes, $precision) . ' ' . $units[$pow]; 
}

function formatNumbers($num, $precision = 1) { 
    $units = array('', 'K', 'M', 'G', 'T'); 
    $num = (int) $num;
    $num = max($num, 0); 
    $pow = floor(($num ? log($num) : 0) / log(1000)); 
    $pow = min($pow, count($units) - 1); 
	$num /= pow(1024, $pow);
	return round($num, $precision) . ' ' . $units[$pow]; 
} 

function filterHtml(&$value) {
    $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
} 

