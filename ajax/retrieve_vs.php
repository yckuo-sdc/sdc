<?php
require '../libraries/Database.php';
require '../config/ChtSecurityAPI.php';
$db = Database::get();
$key = ChtSecurityAPI::KEY;

$table = "api_list"; // 設定你想查詢資料的資料表
$condition = "class LIKE '弱掃平台' ";
$apis = $db->query($table, $condition, $order_by = "1", $fields = "*", $limit = "");

foreach($apis as $api) {
	$nowTime = date("Y-m-d H:i:s");
	switch($api['label']){
		case "ipscanResult":
			$type = "ipscanResult";
			$auth = hash("sha256",$type.$key.$nowTime);
			$url = "https://tainan-vsms.chtsecurity.com/cgi-bin/api/portal.pl?type=".$type."&nowTime=".$nowTime."&auth=".$auth;
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
				$table = "ipscanResult";
				$key_column = "1";
				$id = "1"; 
				$db->delete($table, $key_column, $id); 
				foreach($data as $ipscan){
					$scan['vitem_id']= $db->getEscapedString($ipscan['vitem_id']);
					$scan['OID']= $db->getEscapedString($ipscan['oid']);
					$scan['ou']= $db->getEscapedString($ipscan['ou']);
					$scan['status']= $db->getEscapedString($ipscan['status']);
					$scan['ip']= $db->getEscapedString($ipscan['ip']);
					$scan['system_name']= $db->getEscapedString($ipscan['system_name']);
					$scan['flow_id']= $db->getEscapedString($ipscan['flow_id']);
					$scan['scan_no']= $db->getEscapedString($ipscan['scan_no']);
					$scan['manager']= $db->getEscapedString($ipscan['manager']);
					$scan['email']= $db->getEscapedString($ipscan['email']);
					$scan['vitem_name']= $db->getEscapedString($ipscan['vitem_name']);
					$scan['url']= $db->getEscapedString($ipscan['url']);
					$scan['category']= $db->getEscapedString($ipscan['category']);
					$scan['severity']= $db->getEscapedString($ipscan['severity']);
					$scan['scan_date']= $db->getEscapedString($ipscan['scan_date']);
					$scan['is_duplicated']= $db->getEscapedString($ipscan['is_duplicated']);
					
					$db->insert($table, $scan);
					$count = $count + 1;							
				}
				echo "The ".$count." records have been inserted or updated into the ipscanResult on ".$nowTime."\n\r<br>";
				$status = 200;
			}else{
				echo "No host-data \n\r<br>";
				$status = 400;
			}
			break;
		case "urlscanResult";
			$type = "urlscanResult";
			$auth = hash("sha256",$type.$key.$nowTime);
			$url = "https://tainan-vsms.chtsecurity.com/cgi-bin/api/portal.pl?type=".$type."&nowTime=".$nowTime."&auth=".$auth;
			$preg_url = preg_replace("/ /", "%20", $url);
			$json = file_get_contents($preg_url);		
			// filter out the non-json content
			$pos1 = strpos($json, '[');
			$pos2 = strrpos($json, ']');
			$len = $pos2 - $pos1 + 1;
			$json = substr($json , $pos1 , $len);
			if(($data = json_decode($json,true)) == true){
				$count = 0;	
				$table = "urlscanResult";
				$key_column = "1";
				$id = "1"; 
				$db->delete($table, $key_column, $id); 
				foreach($data as $urlscan){
					$scan['vitem_id']= $db->getEscapedString($urlscan['vitem_id']);
					$scan['OID']= $db->getEscapedString($urlscan['oid']);
					$scan['ou']= $db->getEscapedString($urlscan['ou']);
					$scan['status']= $db->getEscapedString($urlscan['status']);
					$scan['ip']= $db->getEscapedString($urlscan['ip']);
					$scan['system_name']= $db->getEscapedString($urlscan['system_name']);
					$scan['flow_id']= $db->getEscapedString($urlscan['flow_id']);
					$scan['affect_url']= $db->getEscapedString($urlscan['affect_url']);
					$scan['scan_no']= $db->getEscapedString($urlscan['scan_no']);
					$scan['manager']= $db->getEscapedString($urlscan['manager']);
					$scan['email']= $db->getEscapedString($urlscan['email']);
					$scan['vitem_name']= $db->getEscapedString($urlscan['vitem_name']);
					$scan['url']= $db->getEscapedString($urlscan['url']);
					$scan['category']= $db->getEscapedString($urlscan['category']);
					$scan['severity']= $db->getEscapedString($urlscan['severity']);
					$scan['scan_date']= $db->getEscapedString($urlscan['scan_date']);
					$scan['is_duplicated']= $db->getEscapedString($urlscan['is_duplicated']);

					//print_r($scan);		
					$db->insert($table, $scan);
					$count = $count + 1;							
				}
				echo "The ".$count." records have been inserted or updated into the urlscanResult on ".$nowTime."\n\r<br>";
				$status = 200;
			}else{
				echo "No url-data \n\r<br>";
				$status = 400;
			}
			break;
		case "scanTarget";
			$type = "scanTarget";
			$auth = hash("sha256",$type.$key.$nowTime);
			$url = "https://tainan-vsms.chtsecurity.com/cgi-bin/api/portal.pl?type=".$type."&nowTime=".$nowTime."&auth=".$auth;
			$preg_url = preg_replace("/ /", "%20", $url);
			$json = file_get_contents($preg_url);		
			// filter out the non-json content
			$pos1 = strpos($json, '[');
			$pos2 = strrpos($json, ']');
			$len = $pos2 - $pos1 + 1;
			$json = substr($json , $pos1 , $len);
			if(($data = json_decode($json,true)) == true){
				$count = 0;	
				$table = "scanTarget";
				$key_column = "1";
				$id = "1"; 
				$db->delete($table, $key_column, $id); 
				foreach($data as $scanTarget){
					$scanTarget['oid']= $db->getEscapedString($scanTarget['oid']);
					$scanTarget['ou']= $db->getEscapedString($scanTarget['ou']);
					$scanTarget['system_name']= $db->getEscapedString($scanTarget['system_name']);
					$scanTarget['hostname']= $db->getEscapedString($scanTarget['hostname']);
					$scanTarget['ip']= $db->getEscapedString($scanTarget['ip']);
					$scanTarget['domain']= $db->getEscapedString($scanTarget['domain']);
					$scanTarget['manager']= $db->getEscapedString($scanTarget['manager']);
					$scanTarget['email']= $db->getEscapedString($scanTarget['email']);
					
					$db->insert($table, $scanTarget);
					$count = $count + 1;							
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
	$table = "ip_and_url_scanResult";
	$key_column = "1";
	$id = "1"; 
	$db->delete($table, $key_column, $id); 
	
	//insert the table 'ip_and_url_scanResult' from two tables 
	$sql = "INSERT INTO ip_and_url_scanResult(type, vitem_id, OID, ou, status, ip,system_name, flow_id, scan_no, affect_url, manager, email, vitem_name, url, category, severity, scan_date, is_duplicated)
			SELECT '主機弱點' AS type, vitem_id, OID, ou, status, ip, system_name, flow_id, scan_no, 'null' AS affect_url, manager, email, vitem_name, url, category, severity, scan_date, is_duplicated
			FROM ipscanResult
			UNION ALL
			SELECT '網站弱點' AS type,vitem_id, OID, ou, status, ip, system_name, flow_id, scan_no, affect_url, manager, email, vitem_name, url, category, severity, scan_date, is_duplicated
			FROM urlscanResult";
	$db->execute($sql);

	$table = "api_status"; // 設定你想新增資料的資料表
	$data_array['api_id'] = $api['id'];
	$data_array['url'] = $url;
	$data_array['status'] = $status;
	$data_array['data_number'] = $count;
	$data_array['last_update'] = $nowTime;
	$db->insert($table, $data_array);
}


