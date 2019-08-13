<?php
	require("../chtsecurity.inc.php");
	//mysql
	require("../mysql_connect.inc.php");
	date_default_timezone_set("Asia/Taipei");
	$nowTime 	= date("Y-m-d H:i:s");
	$host_type 	= "ipscanResult";
	$web_type 	= "urlscanResult";
	$host_auth	= hash("sha256",$host_type.$chtsecurity_key.$nowTime);
	$web_auth	= hash("sha256",$web_type.$chtsecurity_key.$nowTime);
	$host_url	= "https://tainan-vsms.chtsecurity.com/cgi-bin/api/portal.pl?type=".$host_type."&nowTime=".$nowTime."&auth=".$host_auth;
	$web_url	= "https://tainan-vsms.chtsecurity.com/cgi-bin/api/portal.pl?type=".$web_type."&nowTime=".$nowTime."&auth=".$web_auth;
	//replace all instances of spaces in urls with %20
	$preg_url = preg_replace("/ /", "%20", $host_url);
	$json = file_get_contents($preg_url);		
	$data = json_decode($json,true);
	$count = 0;
	foreach($data as $ipscan){
		$ipscan['vitem_id']= mysqli_real_escape_string($conn,$ipscan['vitem_id']);
		$ipscan['ou']= mysqli_real_escape_string($conn,$ipscan['ou']);
		$ipscan['status']= mysqli_real_escape_string($conn,$ipscan['status']);
		$ipscan['ip']= mysqli_real_escape_string($conn,$ipscan['ip']);
		$ipscan['system_name']= mysqli_real_escape_string($conn,$ipscan['system_name']);
		$ipscan['flow_id']= mysqli_real_escape_string($conn,$ipscan['flow_id']);
		$ipscan['scan_no']= mysqli_real_escape_string($conn,$ipscan['scan_no']);
		$ipscan['manager']= mysqli_real_escape_string($conn,$ipscan['manager']);
		$ipscan['email']= mysqli_real_escape_string($conn,$ipscan['email']);
		$ipscan['vitem_name']= mysqli_real_escape_string($conn,$ipscan['vitem_name']);
		$ipscan['url']= mysqli_real_escape_string($conn,$ipscan['url']);
		$ipscan['category']= mysqli_real_escape_string($conn,$ipscan['category']);
		$ipscan['severity']= mysqli_real_escape_string($conn,$ipscan['severity']);
		$ipscan['scan_date']= mysqli_real_escape_string($conn,$ipscan['scan_date']);
		$ipscan['is_duplicated']= mysqli_real_escape_string($conn,$ipscan['is_duplicated']);
		// INSERT to table ON DUPLICATE KEY UPDATE data
		$sql = "insert into ipscanResult(vitem_id,ou,status,ip,system_name,flow_id,scan_no,manager,email,vitem_name,url,category,severity,scan_date,is_duplicated ) values('".$ipscan['vitem_id']."','".$ipscan['ou']."','".$ipscan['status']."','".$ipscan['ip']."','".$ipscan['system_name']."','".$ipscan['flow_id']."','".$ipscan['scan_no']."','".$ipscan['manager']."','".$ipscan['email']."','".$ipscan['vitem_name']."','".$ipscan['url']."','".$ipscan['category']."','".$ipscan['severity']."','".$ipscan['scan_date']."','".$ipscan['is_duplicated']."')
		ON DUPLICATE KEY UPDATE vitem_id = '".$ipscan['vitem_id']."',ou = '".$ipscan['ou']."',status = '".$ipscan['status']."',ip = '".$ipscan['ip']."',system_name = '".$ipscan['system_name']."',scan_no = '".$ipscan['scan_no']."',manager = '".$ipscan['manager']."',email = '".$ipscan['email']."',vitem_name = '".$ipscan['vitem_name']."',url = '".$ipscan['url']."',category = '".$ipscan['category']."',severity = '".$ipscan['severity']."',scan_date = '".$ipscan['scan_date']."',is_duplicated = '".$ipscan['is_duplicated']."' ";
		if ($conn->query($sql) == TRUE) {
			//echo "此筆資料已被上傳成功\n\r";		
			$count = $count + 1;							
		} else {
			echo "Error: " . $sql . "<br>" . $conn->error."<p>\n\r";
		}
	}
	echo "<p>";
	echo "The ".$count." records have been inserted or updated into the ipscanResult \n\r<br>";


	 
	$preg_url = preg_replace("/ /", "%20", $web_url);
	$json = file_get_contents($preg_url);		
	$data = json_decode($json,true);
	$count = 0;	
	
	foreach($data as $urlscan){
		$urlscan['vitem_id']= mysqli_real_escape_string($conn,$urlscan['vitem_id']);
		$urlscan['ou']= mysqli_real_escape_string($conn,$urlscan['ou']);
		$urlscan['status']= mysqli_real_escape_string($conn,$urlscan['status']);
		$urlscan['ip']= mysqli_real_escape_string($conn,$urlscan['ip']);
		$urlscan['system_name']= mysqli_real_escape_string($conn,$urlscan['system_name']);
		$urlscan['flow_id']= mysqli_real_escape_string($conn,$urlscan['flow_id']);
		$urlscan['affect_url']= mysqli_real_escape_string($conn,$urlscan['affect_url']);
		$urlscan['scan_no']= mysqli_real_escape_string($conn,$urlscan['scan_no']);
		$urlscan['manager']= mysqli_real_escape_string($conn,$urlscan['manager']);
		$urlscan['email']= mysqli_real_escape_string($conn,$urlscan['email']);
		$urlscan['vitem_name']= mysqli_real_escape_string($conn,$urlscan['vitem_name']);
		$urlscan['url']= mysqli_real_escape_string($conn,$urlscan['url']);
		$urlscan['category']= mysqli_real_escape_string($conn,$urlscan['category']);
		$urlscan['severity']= mysqli_real_escape_string($conn,$urlscan['severity']);
		$urlscan['scan_date']= mysqli_real_escape_string($conn,$urlscan['scan_date']);
		$urlscan['is_duplicated']= mysqli_real_escape_string($conn,$urlscan['is_duplicated']);
		// INSERT to table ON DUPLICATE KEY UPDATE data
		$sql = "insert into urlscanResult(vitem_id,ou,status,ip,system_name,flow_id,scan_no,affect_url,manager,email,vitem_name,url,category,severity,scan_date,is_duplicated ) values('".$urlscan['vitem_id']."','".$urlscan['ou']."','".$urlscan['status']."','".$urlscan['ip']."','".$urlscan['system_name']."','".$urlscan['flow_id']."','".$urlscan['scan_no']."','".$urlscan['affect_url']."','".$urlscan['manager']."','".$urlscan['email']."','".$urlscan['vitem_name']."','".$urlscan['url']."','".$urlscan['category']."','".$urlscan['severity']."','".$urlscan['scan_date']."','".$urlscan['is_duplicated']."')
		ON DUPLICATE KEY UPDATE vitem_id = '".$urlscan['vitem_id']."',ou = '".$urlscan['ou']."',status = '".$urlscan['status']."',ip = '".$urlscan['ip']."',system_name = '".$urlscan['system_name']."',scan_no = '".$urlscan['scan_no']."',affect_url = '".$urlscan['affect_url']."',manager = '".$urlscan['manager']."',email = '".$urlscan['email']."',vitem_name = '".$urlscan['vitem_name']."',url = '".$urlscan['url']."',category = '".$urlscan['category']."',severity = '".$urlscan['severity']."',scan_date = '".$urlscan['scan_date']."',is_duplicated = '".$urlscan['is_duplicated']."' ";
		if ($conn->query($sql) == TRUE) {
			//echo "此筆資料已被上傳成功\n\r";									
			$count = $count + 1;							
		} else {
			echo "Error: " . $sql . "<br>" . $conn->error."<p>\n\r";
		}
	}

	echo "The ".$count." records have been inserted or updated into the urlscanResult \n\r<br>";
	echo "</p>"; 
	$conn->close();	





	//json schema
	/*
		//ipscan
		"vitem_id":"34460",
		"ou":"/臺南市政府/區公所",
		"status":"待處理",
		"ip":"117.56.243.143",
		"system_name":"西港區公所網站",
		"flow_id":"11",
		"scan_no":"108-1",
		"manager":"吳隆禧",
		"email":"lonch@mail.tainan.gov.tw",
		"vitem_name":"Unsupported Web Server Detection",
		"url":"https://tainan-vsms.chtsecurity.com/cgi-bin/scanreply/list_ip_vitemDetail.pl?result_id=117.56.243.143||22||34460||R12131||80",
		"category":"其他",
		"severity":"High",
		"scan_date":"2019-03-11 18:15:16"
		
		//urlscan
		"vitem_id":"Cross site scripting",
		"ou":"/臺南市政府/區公所",
		"status":"待處理",
		"ip":"61.216.49.197",
		"system_name":"仁德區公所網站;六甲區公所網站;永康區公所網站",
		"flow_id":"79703","
		scan_no":"4",
		"affect_url":"http://www.rende.gov.tw/department/view/227/4801",
		"manager":"張家賓",
		"email":"bearchang@mail.tainan.gov.tw",
		"vitem_name":"Cross site scripting","
		url":"https://tainan-vsms.chtsecurity.com/cgi-bin/scanreply/list_web_vitemDetail.pl?result_id=79703",
		"category":"跨網站腳本攻擊",
		"severity":"High",
		"scan_date":"2019-04-12 18:00:00"


	 */
?>
