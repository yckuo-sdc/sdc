<?php
require_once __DIR__ .'/../../vendor/autoload.php';

$db = Database::get();

$table = "apis"; // 設定你想查詢資料的資料表
$condition = "class LIKE :class";
$apis = $db->query($table, $condition, $order_by = "1", $fields = "*", $limit = "", [':class'=>'弱掃平台']);
$oid_array = array(
	'yunlin' => '2.16.886.101.90012.20002', // 雲林縣
	'cyhg' => '2.16.886.101.90013.20002', // 嘉義縣
 	'chiayi' => '2.16.886.101.90014.20002', // 嘉義市
	'tainan' => '2.16.886.101.90028.20002', // 臺南市
);

foreach($apis as $api) {
	$nowTime = date("Y-m-d H:i:s");
    $error_array = array();
    $status_array = array();
    echo "The " . $api['label'] . " has been updated on " . $nowTime . "<br>\n\r";

	foreach ($oid_array as $ou_name => $oid) {
        echo $ou_name . ": ";

		switch ($api['label']) {
			case "ipscanResult":
				$type = $api['label'];
				$auth = hash("sha256", $type . ChtSecurity::APIKEY . $nowTime);
				$query = array(
					'type' => $type,
					'nowTime' => $nowTime,
					'auth' => $auth,
					'ou' => $oid,
				);

				$url = ChtSecurity::API_URL . "?" . http_build_query($query);
				$json = sendHttpRequest($url);
				$data_array = json_decode($json, true);

				$count = 0;
				if (empty($data_array)) {
					echo "no data<br>\n\r";
                    $status_array[] = 400;
				} else {
                    $table = "ipscan_results";
					$sql = "DELETE FROM ipscan_results WHERE oid LIKE :oid";
					$db->execute($sql, [':oid' => $oid . '%']);
					foreach ($data_array as $ipscan) {
						$db->insert($table, $ipscan);
						$count = $count + 1;							
					}
					$nowTime = date("Y-m-d H:i:s");
					echo $count . " records<br>\n\r";
                    $status_array[] = 200;
				}	
				break;
			case "urlscanResult";
				$type = $api['label'];
				$auth = hash("sha256", $type . ChtSecurity::APIKEY . $nowTime);
				$query = array(
					'type' => $type,
					'nowTime' => $nowTime,
					'auth' => $auth,
					'ou' => $oid,
				);

				$url = ChtSecurity::API_URL . "?" . http_build_query($query);
				$json = sendHttpRequest($url);
				$data_array = json_decode($json, true);

				$count = 0;	
				if (empty($data_array)) {
					echo "no data<br>\n\r";
                    $status_array[] = 400;
				} else {
                    $table = "urlscan_results";
					$sql = "DELETE FROM urlscan_results WHERE oid LIKE :oid";
					$db->execute($sql, [':oid' => $oid . '%']);
					foreach ($data_array as $urlscan) {
						$db->insert($table, $urlscan);
						$count = $count + 1;							
					}
					$nowTime = date("Y-m-d H:i:s");
					echo $count . " records<br>\n\r";
                    $status_array[] = 200;
				}
				break;
			case "scanTarget";
				$type = $api['label'];
				$auth = hash("sha256", $type . ChtSecurity::APIKEY . $nowTime);
				$query = array(
					'type' => $type,
					'nowTime' => $nowTime,
					'auth' => $auth,
					'ou' => $oid,
				);

				$url = ChtSecurity::API_URL . "?" . http_build_query($query);
				$json = sendHttpRequest($url);
				$data_array = json_decode($json, true);

				$count = 0;	
				if (empty($data_array)) {
					echo "no data<br>\n\r";
                    $status_array[] = 400;
				} else {
                    $table = "scan_targets";
					$sql = "DELETE FROM scan_targets WHERE oid LIKE :oid";
					$db->execute($sql, [':oid' => $oid . '%']);
					foreach($data_array as $scanTarget){
                        var_dump($scanTarget);
						$db->insert($table, $scanTarget);
						$count = $count + 1;							
					}
					$nowTime = date("Y-m-d H:i:s");
					echo $count . " records<br>\n\r";
                    $status_array[] = 200;
				}
				break;
		}

        $error_array[] = $db->getErrorMessageArray();
	}	

    foreach ($error_array as $error) {
        if (!empty($error)) {
            return;
        }
    }

    foreach ($status_array as $status) {
        if ($status == 200) {
            $table = "api_status"; // 設定你想新增資料的資料表
            $data_array = array();
            $data_array['api_id'] = $api['id'];
            $data_array['url'] = $url;
            $data_array['status'] = 200;
            $data_array['data_number'] = $count;
            $data_array['updated_at'] = $nowTime;
            $db->insert($table, $data_array);
            break;
        }
    }

    echo "<div class='ui divider'></div>";
}

//truncate the table 'scan_results'
$table = "scan_results";
$key_column = "1";
$id = "1"; 
$db->delete($table, $key_column, $id); 

//insert the table 'scan_results' from two tables 
$sql = 
"INSERT INTO 
    scan_results(type, vitem_id, oid, ou, status, ip,system_name, flow_id, scan_no, affect_url, manager, email, vitem_name, url, category, severity, scan_date, is_duplicated)
SELECT
    '主機弱點' AS type, vitem_id, oid, ou, status, ip, system_name, flow_id, scan_no, 'null' AS affect_url, manager, email, vitem_name, url, category, severity, scan_date, is_duplicated
FROM 
    ipscan_results
UNION ALL
SELECT 
    '網站弱點' AS type,vitem_id, oid, ou, status, ip, system_name, flow_id, scan_no, affect_url, manager, email, vitem_name, url, category, severity, scan_date, is_duplicated
FROM 
    urlscan_results";
$db->execute($sql, []);

//truncate the table 'scan_stats
$table = "scan_stats";
$key_column = "1";
$id = "1"; 
$db->delete($table, $key_column, $id); 

//insert the table 'scan_stats' 
$sql = 
"INSERT INTO 
    scan_stats(oid, ou, system_name, manager, system_living, manager_living, number, fixed_number, high_risk_number, fixed_high_risk_number, overdue_high_risk_number, overdue_medium_risk_number)
SELECT
    oid,
    ou,
    system_name,
    manager,
    system_living,
    manager_living,
    SUM(number) AS number,
    SUM(fixed_number) AS fixed_number,
    SUM(high_risk_number) AS high_risk_number,
    SUM(fixed_high_risk_number) AS fixed_high_risk_number,
    SUM(overdue_high_risk_number) AS overdue_high_risk_number,
    SUM(overdue_medium_risk_number) AS overdue_medium_risk_number 
FROM
    (
        SELECT
            oid,
            ou,
            system_name,
            manager,
            1 AS system_living,
            1 AS manager_living,
            '0' AS number,
            '0' AS fixed_number,
            '0' AS high_risk_number,
            '0' AS fixed_high_risk_number,
            '0' AS overdue_high_risk_number,
            '0' AS overdue_medium_risk_number 
        FROM
            scan_targets
        UNION ALL
        SELECT
            oid,
            ou,
            system_name,
            manager,
            (
                SELECT count(*) FROM scan_targets
                WHERE INSTR(CONCAT(',', GROUP_CONCAT(distinct scan_results.ip), ','), CONCAT(',', ip, ','))
            ) > 0 AS system_living,
            (
                SELECT count(*) FROM scan_targets
                WHERE manager = scan_results.manager AND system_name = scan_results.system_name
            ) AS manager_living,
            COUNT(system_name) AS number,
            SUM(
                CASE WHEN(status IN('已修補', '豁免', '誤判')) THEN 1 ELSE 0
                END
            ) AS fixed_number,
            SUM(
                CASE WHEN(severity IN('High', 'Critical')) THEN 1 ELSE 0
                END
            ) AS high_risk_number,
            SUM(
                CASE WHEN(
                    severity IN('High', 'Critical')
                    AND
                    status IN('已修補', '豁免', '誤判')
                ) THEN 1 ELSE 0
                END
            ) AS fixed_high_risk_number,
            SUM(
                CASE WHEN(
                    severity IN('High', 'Critical')
                    AND
                    status NOT IN('已修補', '豁免', '誤判')
                    AND
                    scan_date <(NOW() - INTERVAL 1 MONTH)
                ) THEN 1 ELSE 0
                END
            ) AS overdue_high_risk_number,
            SUM(
                CASE WHEN(
                    severity = 'Medium'
                    AND
                    status NOT IN('已修補', '豁免', '誤判')
                    AND
                    scan_date <(NOW() - INTERVAL 2 MONTH)
                ) THEN 1 ELSE 0
                END
            ) AS overdue_medium_risk_number 
        FROM
            scan_results
        GROUP BY
            oid,
            ou,
            system_name,
            manager
    ) B
-- WHERE 
    -- B.oid LIKE '2.16.886.101.90028.20002%' -- just show tainan gov
GROUP BY
    B.oid,
    B.ou,
    B.system_name,
    B.manager,
    B.system_living,
    B.manager_living
ORDER BY
    B.oid,
    B.system_living DESC,
    B.system_name ASC,
    B.manager_living DESC";
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

