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
		list ($user, $token, $userName, $level, $mac) = explode(':', $_COOKIE['rememberme']);
		if (hash_equals(hash_hmac('sha256', $user . ':' . $token .':'. $userName . ':' . $level, $SECRET_KEY), $mac)) {	//使用者名稱和密碼對了，把使用者的個人資料放到session裡面 
			$_SESSION['account'] = $user;   
			$_SESSION['username'] = $userName;
			$_SESSION['level'] = $level;
			$db = Database::get();
			saveAction($db,'rememberLogin',$_SERVER['REMOTE_ADDR'],$_SESSION['account'],$_SERVER['REQUEST_URI']);
			return true;	
		}
	}		
	
	return false;
}	

function checkAccountByLDAP($user, $ldappass, &$user_attributes){
	$ldapconn = ldap_connect(LDAP::HOST);
	$ldaprdn = $user . "@" . LDAP::DOMAIN;
	ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
	ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);

	if ($ldapconn){
		$ldapbind = @ldap_bind($ldapconn, $ldaprdn, $ldappass);
		if ($ldapbind) {
            //bind sdc-ou
            $base = "OU=395000300A,OU=395002900-,OU=395000000A,OU=TainanLocalUser,DC=tainan,DC=gov,DC=tw";
            $filter = "(cn=" . $user . "*)";
            $attributes = array("cn", "displayname");
            $result = ldap_search($ldapconn, $base, $filter, $attributes) or die ("Error in query");
            $entries = ldap_get_entries($ldapconn,$result);
            $username = $entries[0]['displayname'][0];
            $user_attributes = array('username' => $username);

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

// check the disabled of AD account
function isDisable($useraccountcontrol){
	$hexValue = dechex($useraccountcontrol);
	$len = strlen($hexValue);
	$accountdisable = $hexValue[$len-1];

	if($accountdisable == 2){
		return true;
	}else{
		return false;
	}
}

function getUACDescription($useraccountcontrol){
    $UAC_flag_array = array(
        array('property' => 'SCRIPT', 'hex_value' => '0x0001', 'dec_value' => '1'),
		array('property' => 'ACCOUNTDISABLE', 'hex_value' => '0x0002', 'dec_value' => '2'),
		array('property' => 'HOMEDIR_REQUIRED', 'hex_value' => '0x0008', 'dec_value' => '8'),
		array('property' => 'LOCKOUT', 'hex_value' => '0x0010', 'dec_value' => '16'),
		array('property' => 'PASSWD_NOTREQD', 'hex_value' => '0x0020', 'dec_value' => '32'),
		array('property' => 'PASSWD_CANT_CHANGE', 'hex_value' => '0x0040', 'dec_value' => '64'),
		array('property' => 'ENCRYPTED_TEXT_PWD_ALLOWED', 'hex_value' => '0x0080', 'dec_value' => '128'),
		array('property' => 'TEMP_DUPLICATE_ACCOUNT', 'hex_value' => '0x0100', 'dec_value' => '256'),
		array('property' => 'NORMAL_ACCOUNT', 'hex_value' => '0x0200', 'dec_value' => '512'),
		array('property' => 'INTERDOMAIN_TRUST_ACCOUNT', 'hex_value' => '0x0800', 'dec_value' => '2048'),
		array('property' => 'WORKSTATION_TRUST_ACCOUNT', 'hex_value' => '0x1000', 'dec_value' => '4096'),
		array('property' => 'SERVER_TRUST_ACCOUNT', 'hex_value' => '0x2000', 'dec_value' => '8192'),
		array('property' => 'DONT_EXPIRE_PASSWORD', 'hex_value' => '0x10000', 'dec_value' => '65536'),
		array('property' => 'MNS_LOGON_ACCOUNT', 'hex_value' => '0x20000', 'dec_value' => '131072'),
		array('property' => 'SMARTCARD_REQUIRED', 'hex_value' => '0x40000', 'dec_value' => '262144'),
		array('property' => 'TRUSTED_FOR_DELEGATION', 'hex_value' => '0x80000', 'dec_value' => '524288'),
		array('property' => 'NOT_DELEGATED', 'hex_value' => '0x100000', 'dec_value' => '1048576'),
		array('property' => 'USE_DES_KEY_ONLY', 'hex_value' => '0x200000', 'dec_value' => '2097152'),
		array('property' => 'DONT_REQ_PREAUTH', 'hex_value' => '0x400000', 'dec_value' => '4194304'),
		array('property' => 'PASSWORD_EXPIRED', 'hex_value' => '0x800000', 'dec_value' => '8388608'),
		array('property' => 'TRUSTED_TO_AUTH_FOR_DELEGATION', 'hex_value' => '0x1000000', 'dec_value' => '16777216'),
		array('property' => 'PARTIAL_SECRETS_ACCOUNT', 'hex_value' => '0x04000000', 'dec_value' => '67108864'),
	);

    // convert string to int base 10 
    $useraccountcontrol = intval($useraccountcontrol);
    // convert base 10 to base 16
	$hexValue = intval($useraccountcontrol, 16);

	$filtered_array = array_filter($UAC_flag_array, function($value) use(&$hexValue) {
		return intval($value['hex_value'], 16) & $hexValue;	
	});
	
    $property_array = array_column($filtered_array, 'property');

    return implode(" | ", $property_array);
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

function test_print($item, $key) {
    echo "<div class='ui black label'>" . $item . "</div>";
}

function filterHtml(&$value) {
    $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
} 

function breadcrumbs($separator = ' &raquo; ', $home = 'Home') {
    // This gets the REQUEST_URI (/path/to/file.php), splits the string (using '/') into an array, and then filters out any empty values
    $path = array_filter(explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)));

    // This will build our "base URL" ... Also accounts for HTTPS :)
    $base = ($_SERVER['HTTPS'] ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';

    // Initialize a temporary array with our breadcrumbs. (starting with our home page, which I'm assuming will be the base URL)
    $breadcrumbs = array("<a href=\"$base\">$home</a>");

    // Initialize crumbs to track path for proper link
    $crumbs = '';

    // Find out the index for the last value in our path array
    $last = @end(array_keys($path));

    // Build the rest of the breadcrumbs
    foreach ($path as $x => $crumb) {
        // Our "title" is the text that will be displayed (strip out .php and turn '_' into a space)
        $title = ucwords(str_replace(array('.php', '_', '%20'), array('', ' ', ' '), $crumb));

        // If we are not on the last index, then display an <a> tag
        if ($x != $last) {
            $breadcrumbs[] = "<a href=\"$base$crumbs$crumb\">$title</a>";
            $crumbs .= $crumb . '/';
        }
        // Otherwise, just display the title (minus)
        else {
            $breadcrumbs[] = $title;
        }

    }

    // Build our temporary array (pieces of bread) into one big string :)
    return implode($separator, $breadcrumbs);
}

function createWebadMessageBox($result, $label) {
    $html = "";
    if ($result == '"1."') {
        $html .= "<div class='ui info message'>";
	    $html .= $label . " 執行結果: ". $result;
		$html .= "</div>";
    } else {
        $html .= "<div class='ui negative message'>";
	    $html .= $label . " 執行結果: ". $result;
		$html .= "</div>";
    }
    return $html;
}



