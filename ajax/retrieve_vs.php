<?php
//mysql
require("../mysql_connect.inc.php");
require '../config/ChtSecurityAPI.php';
$key = ChtSecurityAPI::KEY;

$nowTime 	= date("Y-m-d H:i:s");
$sql = "SELECT * FROM api_list WHERE class LIKE '弱掃平台' ";
$result = mysqli_query($conn,$sql);

while($row = mysqli_fetch_assoc($result)) {
	$nowTime 	= date("Y-m-d H:i:s");
	switch($row['label']){
		case "ipscanResult":
			$type 	= "ipscanResult";
			$auth	= hash("sha256",$type.$key.$nowTime);
			$url	= "https://tainan-vsms.chtsecurity.com/cgi-bin/api/portal.pl?type=".$type."&nowTime=".$nowTime."&auth=".$auth;
			//replace all instances of spaces in urls with %20
			$preg_url = preg_replace("/ /", "%20", $url);
			$json = file_get_contents($preg_url);		
			// filter out the non-json content
			$pos1 = strpos($json, '[');
			$pos2 = strrpos($json, ']');
			$len = $pos2 - $pos1 + 1;
			$json = substr($json , $pos1 , $len);
			if(($data = json_decode($json,true)) == true){
				$count = 0;
				$sql = "TRUNCATE TABLE ipscanResult";
				$conn->query($sql); 
				foreach($data as $ipscan){
					$ipscan['vitem_id']= mysqli_real_escape_string($conn,$ipscan['vitem_id']);
					$ipscan['OID']= mysqli_real_escape_string($conn,$ipscan['oid']);
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
					$sql = "insert into ipscanResult(vitem_id,OID,ou,status,ip,system_name,flow_id,scan_no,manager,email,vitem_name,url,category,severity,scan_date,is_duplicated ) values('".$ipscan['vitem_id']."','".$ipscan['OID']."','".$ipscan['ou']."','".$ipscan['status']."','".$ipscan['ip']."','".$ipscan['system_name']."','".$ipscan['flow_id']."','".$ipscan['scan_no']."','".$ipscan['manager']."','".$ipscan['email']."','".$ipscan['vitem_name']."','".$ipscan['url']."','".$ipscan['category']."','".$ipscan['severity']."','".$ipscan['scan_date']."','".$ipscan['is_duplicated']."')
					ON DUPLICATE KEY UPDATE vitem_id = '".$ipscan['vitem_id']."',OID = '".$ipscan['OID']."',ou = '".$ipscan['ou']."',status = '".$ipscan['status']."',ip = '".$ipscan['ip']."',system_name = '".$ipscan['system_name']."',scan_no = '".$ipscan['scan_no']."',manager = '".$ipscan['manager']."',email = '".$ipscan['email']."',vitem_name = '".$ipscan['vitem_name']."',url = '".$ipscan['url']."',category = '".$ipscan['category']."',severity = '".$ipscan['severity']."',scan_date = '".$ipscan['scan_date']."',is_duplicated = '".$ipscan['is_duplicated']."' ";
					if ($conn->query($sql) == TRUE) {
						//echo "此筆資料已被上傳成功\n\r";		
						$count = $count + 1;							
					} else {
						echo "Error: " . $sql . "<br>" . $conn->error."<p>\n\r";
					}
				}
				echo "The ".$count." records have been inserted or updated into the ipscanResult on ".$nowTime."\n\r<br>";
				$status = 200;
			}else{
				echo "No host-data \n\r<br>";
				$status = 400;
			}
			break;
		case "urlscanResult";
			$type 	= "urlscanResult";
			$auth	= hash("sha256",$type.$key.$nowTime);
			$url	= "https://tainan-vsms.chtsecurity.com/cgi-bin/api/portal.pl?type=".$type."&nowTime=".$nowTime."&auth=".$auth;
			$preg_url = preg_replace("/ /", "%20", $url);
			$json = file_get_contents($preg_url);		
			// filter out the non-json content
			$pos1 = strpos($json, '[');
			$pos2 = strrpos($json, ']');
			$len = $pos2 - $pos1 + 1;
			$json = substr($json , $pos1 , $len);
			if(($data = json_decode($json,true)) == true){
				$count = 0;	
				$sql = "TRUNCATE TABLE urlscanResult";
				$conn->query($sql); 
				foreach($data as $urlscan){
					$urlscan['vitem_id']= mysqli_real_escape_string($conn,$urlscan['vitem_id']);
					$urlscan['OID']= mysqli_real_escape_string($conn,$urlscan['oid']);
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
					$sql = "insert into urlscanResult(vitem_id,OID,ou,status,ip,system_name,flow_id,scan_no,affect_url,manager,email,vitem_name,url,category,severity,scan_date,is_duplicated ) values('".$urlscan['vitem_id']."','".$urlscan['OID']."','".$urlscan['ou']."','".$urlscan['status']."','".$urlscan['ip']."','".$urlscan['system_name']."','".$urlscan['flow_id']."','".$urlscan['scan_no']."','".$urlscan['affect_url']."','".$urlscan['manager']."','".$urlscan['email']."','".$urlscan['vitem_name']."','".$urlscan['url']."','".$urlscan['category']."','".$urlscan['severity']."','".$urlscan['scan_date']."','".$urlscan['is_duplicated']."')
					ON DUPLICATE KEY UPDATE vitem_id = '".$urlscan['vitem_id']."',OID = '".$urlscan['OID']."',ou = '".$urlscan['ou']."',status = '".$urlscan['status']."',ip = '".$urlscan['ip']."',system_name = '".$urlscan['system_name']."',scan_no = '".$urlscan['scan_no']."',affect_url = '".$urlscan['affect_url']."',manager = '".$urlscan['manager']."',email = '".$urlscan['email']."',vitem_name = '".$urlscan['vitem_name']."',url = '".$urlscan['url']."',category = '".$urlscan['category']."',severity = '".$urlscan['severity']."',scan_date = '".$urlscan['scan_date']."',is_duplicated = '".$urlscan['is_duplicated']."' ";
					if ($conn->query($sql) == TRUE) {
						//echo "此筆資料已被上傳成功\n\r";									
						//echo $sql."<br>";
						$count = $count + 1;							
					} else {
						echo "Error: " . $sql . "<br>" . $conn->error."<p>\n\r";
					}
				}
				echo "The ".$count." records have been inserted or updated into the urlscanResult on ".$nowTime."\n\r<br>";
				$status = 200;
			}else{
				echo "No url-data \n\r<br>";
				$status = 400;
			}
			break;
		case "scanTarget";
			$type= "scanTarget";
			$auth= hash("sha256",$type.$key.$nowTime);
			$url	= "https://tainan-vsms.chtsecurity.com/cgi-bin/api/portal.pl?type=".$type."&nowTime=".$nowTime."&auth=".$auth;
			$preg_url = preg_replace("/ /", "%20", $url);
			$json = file_get_contents($preg_url);		
			// filter out the non-json content
			$pos1 = strpos($json, '[');
			$pos2 = strrpos($json, ']');
			$len = $pos2 - $pos1 + 1;
			$json = substr($json , $pos1 , $len);
			if(($data = json_decode($json,true)) == true){
				$count = 0;	
				$sql = "TRUNCATE TABLE scanTarget";
				$conn->query($sql); 
				foreach($data as $scanTarget){
					$scanTarget['oid']= mysqli_real_escape_string($conn,$scanTarget['oid']);
					$scanTarget['ou']= mysqli_real_escape_string($conn,$scanTarget['ou']);
					$scanTarget['system_name']= mysqli_real_escape_string($conn,$scanTarget['system_name']);
					$scanTarget['hostname']= mysqli_real_escape_string($conn,$scanTarget['hostname']);
					$scanTarget['ip']= mysqli_real_escape_string($conn,$scanTarget['ip']);
					$scanTarget['domain']= mysqli_real_escape_string($conn,$scanTarget['domain']);
					$scanTarget['manager']= mysqli_real_escape_string($conn,$scanTarget['manager']);
					$scanTarget['email']= mysqli_real_escape_string($conn,$scanTarget['email']);
					// INSERT to table ON DUPLICATE KEY UPDATE data
					$sql = "insert into scanTarget(oid,ou,ip,system_name,hostname,domain,manager,email) values('".$scanTarget['oid']."','".$scanTarget['ou']."','".$scanTarget['ip']."','".$scanTarget['system_name']."','".$scanTarget['hostname']."','".$scanTarget['domain']."','".$scanTarget['manager']."','".$scanTarget['email']."')
					ON DUPLICATE KEY UPDATE oid = '".$scanTarget['oid']."',ou = '".$scanTarget['ou']."',ip = '".$scanTarget['ip']."',system_name = '".$scanTarget['system_name']."',hostname = '".$scanTarget['hostname']."',domain = '".$scanTarget['domain']."',manager = '".$scanTarget['manager']."',email = '".$scanTarget['email']."' ";
					if ($conn->query($sql) == TRUE) {
						//echo "此筆資料已被上傳成功\n\r";									
						//echo $sql."<br>";
						$count = $count + 1;							
					} else {
						echo "Error: " . $sql . "<br>" . $conn->error."<p>\n\r";
					}
				}

				echo "The ".$count." records have been inserted or updated into the scanTarget on ".$nowTime."\n\r<br>";
				$status = 200;
			}else{
				echo "No target-data \n\r<br>";
				$status = 400;
			}
			break;
	}
	
	//truncate the table 'ip_and_url_scanResult'
	$sql = "TRUNCATE TABLE ip_and_url_scanResult";
	$conn->query($sql); 
	
	//insert the table 'ip_and_url_scanResult' from two tables 
	$sql = "INSERT INTO ip_and_url_scanResult(type, vitem_id, OID, ou, status, ip,system_name, flow_id, scan_no, affect_url, manager, email, vitem_name, url, category, severity, scan_date, is_duplicated)
			SELECT '主機弱點' AS type, vitem_id, OID, ou, status, ip, system_name, flow_id, scan_no, 'null' AS affect_url, manager, email, vitem_name, url, category, severity, scan_date, is_duplicated
			FROM ipscanResult
			UNION ALL
			SELECT '網站弱點' AS type,vitem_id, OID, ou, status, ip, system_name, flow_id, scan_no, affect_url, manager, email, vitem_name, url, category, severity, scan_date, is_duplicated
			FROM urlscanResult";
	if ($conn->query($sql) == TRUE){
	}else {
		echo "Error: " . $sql . "<br>" . $conn->error."<p>\n\r";
	}
	
	$sql = "INSERT INTO api_status(api_id,url,status,data_number,last_update) VALUES(".$row['id'].",'".$url."',".$status.",".$count.",'".$nowTime."')";
	if ($conn->query($sql) == TRUE){
	}else {
		echo "Error: " . $sql . "<br>" . $conn->error."<p>\n\r";
	}
}

$conn->close();	

