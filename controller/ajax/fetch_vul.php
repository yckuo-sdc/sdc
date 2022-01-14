<?php
require_once __DIR__ .'/../../vendor/autoload.php';

$db = Database::get();

$table = "apis"; // 設定你想查詢資料的資料表
$condition = "class LIKE :class";
$apis = $db->query($table, $condition, $order_by = "1", $fields = "*", $limit = "", [':class'=>'弱掃平台']);

foreach($apis as $api) {
	$nowTime = date("Y-m-d H:i:s");
    $ou = "2.16.886.101.90028.20002";
	switch ($api['label']) {
		case "ipscanResult":
			$type = $api['label'];
			$auth = hash("sha256", $type . ChtSecurity::APIKEY . $nowTime);
			$query = array(
				'type' => $type,
				'nowTime' => $nowTime,
				'auth' => $auth,
                'ou' => $ou,
			);

			$url = ChtSecurity::API_URL . "?" . http_build_query($query);
            $json = sendHttpRequest($url);
            $data_array = json_decode($json, true);

            $count = 0;
			if (empty($data_array)) {
				echo "No " . $api['label'] . "data \n<br>";
				$status = 400;
			} else {
				$table = "ipscan_results";
				$key_column = "1";
				$id = "1"; 
				$db->delete($table, $key_column, $id); 
				foreach ($data_array as $ipscan) {
					$db->insert($table, $ipscan);
					$count = $count + 1;							
				}
				$nowTime = date("Y-m-d H:i:s");
				echo "The ".$count." records have been inserted or updated into the ipscanResult on ".$nowTime."\n\r<br>";
				$status = 200;
			}	
            break;
		case "urlscanResult";
			$type = $api['label'];
			$auth = hash("sha256", $type . ChtSecurity::APIKEY . $nowTime);
			$query = array(
				'type' => $type,
				'nowTime' => $nowTime,
				'auth' => $auth,
                'ou' => $ou,
			);

			$url = ChtSecurity::API_URL . "?" . http_build_query($query);
            $json = sendHttpRequest($url);
            $data_array = json_decode($json, true);

            $count = 0;	
			if (empty($data_array)) {
				echo "No " . $api['label'] . "data \n<br>";
				$status = 400;
			} else {
				$table = "urlscan_results";
				$key_column = "1";
				$id = "1"; 
				$db->delete($table, $key_column, $id); 
				foreach ($data_array as $urlscan) {
					$db->insert($table, $urlscan);
					$count = $count + 1;							
				}
				$nowTime = date("Y-m-d H:i:s");
				echo "The ".$count." records have been inserted or updated into the urlscanResult on ".$nowTime."\n\r<br>";
				$status = 200;
			}
            break;
		case "scanTarget";
			$type = $api['label'];
			$auth = hash("sha256", $type . ChtSecurity::APIKEY . $nowTime);
			$query = array(
				'type' => $type,
				'nowTime' => $nowTime,
				'auth' => $auth,
                'ou' => $ou,
			);

			$url = ChtSecurity::API_URL . "?" . http_build_query($query);
            $json = sendHttpRequest($url);
            $data_array = json_decode($json, true);

            $count = 0;	
			if (empty($data_array)) {
				echo "No " . $api['label'] . "data \n<br>";
				$status = 400;
			} else {
				$table = "scan_targets";
				$key_column = "1";
				$id = "1"; 
				$db->delete($table, $key_column, $id); 
				foreach($data_array as $scanTarget){
					$db->insert($table, $scanTarget);
					$count = $count + 1;							
				}
				$nowTime = date("Y-m-d H:i:s");
				echo "The ".$count." records have been inserted or updated into the scanTarget on ".$nowTime."\n\r<br>";
				$status = 200;
			}
            break;
	}
		
	$error = $db->getErrorMessageArray();
    if (!empty($error)) {
		return;
	}

    if ($status == 200) {
        $table = "api_status"; // 設定你想新增資料的資料表
        $data_array = array();
        $data_array['api_id'] = $api['id'];
        $data_array['url'] = $url;
        $data_array['status'] = $status;
        $data_array['data_number'] = $count;
        $data_array['updated_at'] = $nowTime;
        $db->insert($table, $data_array);
    }
}

//truncate the table 'scan_results'
$table = "scan_results";
$key_column = "1";
$id = "1"; 
$db->delete($table, $key_column, $id); 

//insert the table 'scan_results' from two tables 
$sql = "INSERT INTO scan_results(type, vitem_id, oid, ou, status, ip,system_name, flow_id, scan_no, affect_url, manager, email, vitem_name, url, category, severity, scan_date, is_duplicated)
		SELECT '主機弱點' AS type, vitem_id, oid, ou, status, ip, system_name, flow_id, scan_no, 'null' AS affect_url, manager, email, vitem_name, url, category, severity, scan_date, is_duplicated
		FROM ipscan_results
		UNION ALL
		SELECT '網站弱點' AS type,vitem_id, oid, ou, status, ip, system_name, flow_id, scan_no, affect_url, manager, email, vitem_name, url, category, severity, scan_date, is_duplicated
		FROM urlscan_results";
$db->execute($sql, []);

function sendHttpRequest($url) {
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_SSL_VERIFYPEER => false,
      CURLOPT_SSL_VERIFYHOST => false,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_HTTPHEADER => array("Content-Type: application/json"),
    ));
    $response = curl_exec($curl);

    // Check if any error occurred
    if (curl_errno($curl)) {
        echo 'Curl error: ' . curl_error($curl);
    }

    curl_close($curl);
    return $response;
}

