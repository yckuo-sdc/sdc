<?php
require '../vendor/autoload.php';

$db = Database::get();
$key = ChtSecurityAPI::KEY;

$table = "api_list"; // 設定你想查詢資料的資料表
$condition = "class LIKE :class";
$apis = $db->query($table, $condition, $order_by = "1", $fields = "*", $limit = "", [':class'=>'弱掃平台']);

$Options = array(
	"ssl"=>array(
		"verify_peer"=>false,
		"verify_peer_name"=>false,
	),
);  	

foreach($apis as $api) {
	$nowTime = date("Y-m-d H:i:s");
	switch($api['label']){
		case "ipscanResult":
			$type = "ipscanResult";
			$auth = hash("sha256",$type.$key.$nowTime);
			$url = "https://tainan-vsms.chtsecurity.com/cgi-bin/api/portal.pl?type=".$type."&nowTime=".$nowTime."&auth=".$auth;
			$preg_url = preg_replace("/ /", "%20", $url);  //replace all instances of spaces in urls with %20
            $json = file_get_contents($preg_url, false, stream_context_create($Options));
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
					$scan['vitem_id']= $ipscan['vitem_id'];
					$scan['OID']= $ipscan['oid'];
					$scan['ou']= $ipscan['ou'];
					$scan['status']= $ipscan['status'];
					$scan['ip']= $ipscan['ip'];
					$scan['system_name']= $ipscan['system_name'];
					$scan['flow_id']= $ipscan['flow_id'];
					$scan['scan_no']= $ipscan['scan_no'];
					$scan['manager']= $ipscan['manager'];
					$scan['email']= $ipscan['email'];
					$scan['vitem_name']= $ipscan['vitem_name'];
					$scan['url']= $ipscan['url'];
					$scan['category']= $ipscan['category'];
					$scan['severity']= $ipscan['severity'];
					$scan['scan_date']= $ipscan['scan_date'];
					$scan['is_duplicated']= $ipscan['is_duplicated'];
					
					$db->insert($table, $scan);
					$count = $count + 1;							
				}
				$nowTime = date("Y-m-d H:i:s");
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
            $json = file_get_contents($preg_url, false, stream_context_create($Options));
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
					$scan['vitem_id']= $urlscan['vitem_id'];
					$scan['OID']= $urlscan['oid'];
					$scan['ou']= $urlscan['ou'];
					$scan['status']= $urlscan['status'];
					$scan['ip']= $urlscan['ip'];
					$scan['system_name']= $urlscan['system_name'];
					$scan['flow_id']= $urlscan['flow_id'];
					$scan['affect_url']= $urlscan['affect_url'];
					$scan['scan_no']= $urlscan['scan_no'];
					$scan['manager']= $urlscan['manager'];
					$scan['email']= $urlscan['email'];
					$scan['vitem_name']= $urlscan['vitem_name'];
					$scan['url']= $urlscan['url'];
					$scan['category']= $urlscan['category'];
					$scan['severity']= $urlscan['severity'];
					$scan['scan_date']= $urlscan['scan_date'];
					$scan['is_duplicated']= $urlscan['is_duplicated'];

					//print_r($scan);		
					$db->insert($table, $scan);
					$count = $count + 1;							
				}
				$nowTime = date("Y-m-d H:i:s");
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
            $json = file_get_contents($preg_url, false, stream_context_create($Options));
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
					$scanTarget['oid']= $scanTarget['oid'];
					$scanTarget['ou']= $scanTarget['ou'];
					$scanTarget['system_name']= $scanTarget['system_name'];
					$scanTarget['hostname']= $scanTarget['hostname'];
					$scanTarget['ip']= $scanTarget['ip'];
					$scanTarget['domain']= $scanTarget['domain'];
					$scanTarget['manager']= $scanTarget['manager'];
					$scanTarget['email']= $scanTarget['email'];
					
					$db->insert($table, $scanTarget);
					$count = $count + 1;							
				}
				$nowTime = date("Y-m-d H:i:s");
				echo "The ".$count." records have been inserted or updated into the scanTarget on ".$nowTime."\n\r<br>";
				$status = 200;
			}else{
				echo "No target-data \n\r<br>";
				$status = 400;
			}
			break;
	}
		
	$error = $db->getErrorMessageArray();
	if(@count($error) > 0) {
		return;
	}

	$table = "api_status"; // 設定你想新增資料的資料表
	$data_array['api_id'] = $api['id'];
	$data_array['url'] = $url;
	$data_array['status'] = $status;
	$data_array['data_number'] = $count;
	$data_array['last_update'] = $nowTime;
	$db->insert($table, $data_array);
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
$db->execute($sql, []);
