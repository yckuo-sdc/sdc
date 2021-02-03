<?php
// input validation
$v1 = 0;
$v2 = 0;
foreach($_GET as $getkey => $val){
	$$getkey = $val;
	if($getkey == "jsonObj" && $val == "[]"){
		$v1 = 1;	
	}elseif($getkey != "jsonObj" && $val == ""){
		$v2 = 1;	
	}
}

if($v1 && $v2){
	echo "沒有輸入";
	return 0;
}

$page = isset($page) ? $page : 1;
$ap = isset($ap) ? $ap : 'html';

$arr_jsonStatus = json_decode($jsonStatus,true);
$arr_jsonObj = json_decode($jsonObj,true);

$table_map = [
	'ip_and_url_scanResult' => 'ip_and_url_scanResult',
	'ipscanResult' => 'ipscanResult',
	'urlscanResult' => 'urlscanResult'
];
$table = $table_map[$type];

$status_map = [	//overdue_and_unfinish + non_overdue_and_unfinish + finish
	"1" => [
		"1" =>  [ 
			"1" => "" ,  
			"0" => "AND status IN ('待處理','待處理(經複查仍有弱點)','豁免(待簽核)','誤判(待簽核)','已修補(待複檢)')"
		],
		"0" => [ 
			"1" => "AND (
				( severity IN ('High','Critical') AND status IN ('待處理','待處理(經複查仍有弱點)','豁免(待簽核)','誤判(待簽核)','已修補(待複檢)') AND scan_date < DATE_SUB(NOW(), INTERVAL 1 MONTH) )
				OR 
				( severity IN ('Medium') AND status IN ('待處理','待處理(經複查仍有弱點)','豁免(待簽核)','誤判(待簽核)','已修補(待複檢)') AND scan_date < DATE_SUB(NOW(), INTERVAL 2 MONTH) )
				OR status IN ('已修補','豁免','誤判')
			)"  ,  
			"0" => "AND (
				( severity IN ('High','Critical') AND status IN ('待處理','待處理(經複查仍有弱點)','豁免(待簽核)','誤判(待簽核)','已修補(待複檢)') AND scan_date < DATE_SUB(NOW(), INTERVAL 1 MONTH) )
				OR 
				( severity IN ('Medium') AND status IN ('待處理','待處理(經複查仍有弱點)','豁免(待簽核)','誤判(待簽核)','已修補(待複檢)') AND scan_date < DATE_SUB(NOW(), INTERVAL 2 MONTH) )
			)"  
		]
	],
	"0" => [	
		"1" => [
			"1" => "AND (
				( severity IN ('High','Critical') AND status IN ('待處理','待處理(經複查仍有弱點)','豁免(待簽核)','誤判(待簽核)','已修補(待複檢)') AND scan_date >= DATE_SUB(NOW(), INTERVAL 1 MONTH) )
				OR 
				( severity IN ('Medium') AND status IN ('待處理','待處理(經複查仍有弱點)','豁免(待簽核)','誤判(待簽核)','已修補(待複檢)') AND scan_date >= DATE_SUB(NOW(), INTERVAL 2 MONTH) )
				OR status IN ('已修補','豁免','誤判')
			)"  ,  
			"0" => "AND (
				( severity IN ('High','Critical') AND status IN ('待處理','待處理(經複查仍有弱點)','豁免(待簽核)','誤判(待簽核)','已修補(待複檢)') AND scan_date >= DATE_SUB(NOW(), INTERVAL 1 MONTH) )
				OR 
				( severity IN ('Medium') AND status IN ('待處理','待處理(經複查仍有弱點)','豁免(待簽核)','誤判(待簽核)','已修補(待複檢)') AND scan_date >= DATE_SUB(NOW(), INTERVAL 2 MONTH) )
			)" 
		],
		"0" => [ 
			"1" => "AND status IN ('已修補','豁免','誤判')", 
			'0' => "AND status IN ('')"  
		]
	]	
];

$status_condition = $status_map[$arr_jsonStatus['overdue_and_unfinish']][$arr_jsonStatus['non_overdue_and_unfinish']][$arr_jsonStatus['finish']];

//retrieve condition
$data_array = [];
if(count($arr_jsonObj) != 0) {
	$condition = "";
	foreach($arr_jsonObj as $val){
		if($val['keyword'] == "all"){
			$res = $db->getFullTextSearchCondition($table, $val['key']);
			$one_condition = "(".$res['condition'].") "; 
			$data_array = array_merge($data_array, $res['data']);
		}else{
			$one_condition = $val['keyword']." LIKE ?";
			$data_array[] = "%".$val['key']."%"; 
		}
		$condition = $condition." AND ".$one_condition;
	}
	$condition = substr($condition,4)." ".$status_condition;
}else{
	if($keyword == "all"){
		$res = $db->getFullTextSearchCondition($table, $key);
		$condition = "(".$res['condition'].") "; 
		$data_array = $res['data'];
	}else{
		$condition = $keyword." LIKE ? ".$status_condition;
		$data_array[] = "%".$key."%"; 
	}
}

//echo $condition."<br>";
$order_by = "scan_no DESC,ou DESC,system_name DESC,status DESC";

$limit = 10;
$links = 4;
$query = "SELECT * FROM {$table} WHERE {$condition} ORDER BY {$order_by}";

$Paginator = new Paginator($query, $data_array);
$vuls = $Paginator->getData($limit, $page, $data_array);
$last_num_rows = $Paginator->getTotal();

if($ap=='html'){
	if ($last_num_rows == 0){
		echo "很抱歉，該分類目前沒有資料！";
	}
	else{
		echo "該分類共搜尋到".$last_num_rows."筆資料！";
		echo "<div class='ui relaxed divided list'>";
		foreach($vuls->data as $vul) {
			echo "<div class='item'>";
			echo "<div class='content'>";
				echo "<a>";
				echo "<span style='background:#f3c4c4'>".$vul['type']."</span>&nbsp&nbsp";
				echo $vul['flow_id']."&nbsp&nbsp";
				echo str_replace("/臺南市政府/","",$vul['ou'])."&nbsp&nbsp";
				echo "<span style='background:#fde087'>".$vul['system_name']."</span>&nbsp&nbsp";
				echo $vul['status']."&nbsp&nbsp";
				echo "<span style='background:#DDDDDD'>".$vul['vitem_name']."</span>&nbsp&nbsp";
				echo $vul['scan_no']."&nbsp&nbsp";
		
				echo "<i class='angle down icon'></i>";
				echo "</a>";
				echo "<div class='description'>";
					echo "<ol>";
					echo "<li>弱點類別:".$vul['type']."</li>";
					echo "<li>流水號:".$vul['flow_id']."</li>";
					echo "<li>弱點序號:".$vul['vitem_id']."</li>";
					echo "<li>弱點名稱:".$vul['vitem_name']."</li>";
					echo "<li>OID:".$vul['OID']."</li>";
					echo "<li>單位:".str_replace("/臺南市政府/","",$vul['ou'])."</li>";
					echo "<li>系統名稱:".$vul['system_name']."</li>";
					echo "<li>IP:".$vul['ip']."</li>";
					echo "<li>掃描日期:".date_format(new DateTime($vul['scan_date']),'Y-m-d')."</li>";
					echo "<li>管理員:".$vul['manager']."</li>";
					echo "<li>Email:".$vul['email']."</li>";
					echo "<li>影響網址:<a href='".$vul['affect_url']."' target='_blank'>".$vul['affect_url']."</a></li>";
					echo "<li>弱點詳細資訊:<a href='".$vul['url']."' target='_blank'>".$vul['url']."</a></li>";
					echo "<li>總類:".$vul['category']."</li>";
					echo "<li>風險程度:".$vul['severity']."</li>";
					echo "<li>弱點處理情形:".$vul['status']."</li>";
					echo "<li>掃描期別:".$vul['scan_no']."</li>";
					echo "</ol>";
				echo "</div>";
				echo "</div>";
			echo "</div>";
		}
		echo "</div>";

		$pageAttr['key'] = $key;	
		$pageAttr['keyword'] = $keyword;	
		$pageAttr['type'] = $type;	
		$pageAttr['jsonStatus'] = $jsonStatus;	
		$pageAttr['jsonObj'] = $jsonObj;	

		echo $Paginator->createLinks($links, 'ui pagination menu', $pageAttr, $method='ajax');
	}
}elseif($ap='csv'){
    $total_vuls = $db->execute($query, $data_array);
	$arrs = array();
	foreach($total_vuls as $vul) {
        $arr = array();
		foreach($vul as $key => $val){
			$arr[$key] = $val;
		}
		array_push($arrs,$arr);	
	}
    array_to_csv_download($arrs, "export.csv", ";"); 	
}
