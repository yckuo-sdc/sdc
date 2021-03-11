<?php
// input validation
$v1 = 0;
$v2 = 0;
foreach($_GET as $getkey => $val){
	$$getkey = $val;
	if($getkey == "jsonConditions" && $val == "[]"){
		$v1 = 1;	
	}elseif($getkey != "jsonConditions" && $val == ""){
		$v2 = 1;	
	}
}

if($v1 && $v2){
	echo "沒有輸入";
	return 0;
}

$page = isset($page) ? $page : 1;
$ap = isset($ap) ? $ap : 'html';

$jsonStates = json_decode($jsonStates,true);
$jsonConditions = json_decode($jsonConditions,true);

$table_map = ['scanResult' => 'scan_results'];
if(!array_key_exists($type, $table_map)) {
    return ;
}
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

$status_condition = $status_map[$jsonStates['overdue_and_unfinish']][$jsonStates['non_overdue_and_unfinish']][$jsonStates['finish']];

// get conditions
$data_array = [];
if(count($jsonConditions) != 0) {
	$condition = "";
	foreach($jsonConditions as $val){
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
	if ($keyword == 'all' && $key == 'any') {
        $condition = "1 = ?";
        $data_array[] = 1;
	} elseif($keyword == "all" && $key != 'any') {
		$res = $db->getFullTextSearchCondition($table, $key);
		$condition = "(".$res['condition'].") ".$status_condition; 
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

$pageAttr = array();

if ($ap == 'csv') {
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
} elseif($ap == 'html') {
    ?>
	<?php if ($last_num_rows == 0): ?>
		很抱歉，該分類目前沒有資料！
	<?php else: ?>
		該分類共搜尋到<?=$last_num_rows?>筆資料！
		<div class='ui relaxed divided list'>
		<?php foreach($vuls->data as $vul): ?>
			<div class='item'>
                <div class='content'>
                    <a>
                        <span style='background:#f3c4c4'><?=$vul['type']?></span>&nbsp&nbsp
                        <?=$vul['flow_id']?>&nbsp&nbsp
                        <?=str_replace("/臺南市政府/","",$vul['ou'])?>&nbsp&nbsp
                        <span style='background:#fde087'><?=$vul['system_name']?></span>&nbsp&nbsp
                        <?=$vul['status']?>&nbsp&nbsp
                        <span style='background:#DDDDDD'><?=$vul['vitem_name']?></span>&nbsp&nbsp
                        <?=$vul['scan_no']?>&nbsp&nbsp
                        <i class='angle down icon'></i>
                    </a>
                    <div class='description'>
                        <ol>
                            <li>弱點類別: <?=$vul['type']?></li>
                            <li>流水號: <?=$vul['flow_id']?></li>
                            <li>弱點序號: <?=$vul['vitem_id']?></li>
                            <li>弱點名稱: <?=$vul['vitem_name']?></li>
                            <li>OID: <?=$vul['OID']?></li>
                            <li>單位: <?=str_replace("/臺南市政府/","",$vul['ou'])?></li>
                            <li>系統名稱: <?=$vul['system_name']?></li>
                            <li>IP: <?=$vul['ip']?></li>
                            <li>掃描日期: <?=date_format(new DateTime($vul['scan_date']),'Y-m-d')?></li>
                            <li>管理員: <?=$vul['manager']?></li>
                            <li>Email: <?=$vul['email']?></li>
                            <li>影響網址: <a href='<?=$vul['affect_url']?>' target='_blank'><?=$vul['affect_url']?></a></li>
                            <li>弱點詳細資訊: <a href='<?=$vul['url']?>' target='_blank'><?=$vul['url']?></a></li>
                            <li>弱點詳細資訊: <a href='/ajax/vul_detail/?url=<?=urlencode($vul['url'])?>' target='_blank'>靜態頁面 <i class="external alternate icon"></i></a></li>
                            <li>總類: <?=$vul['category']?></li>
                            <li>風險程度: <?=$vul['severity']?></li>
                            <li>弱點處理情形: <?=$vul['status']?></li>
                            <li>掃描期別: <?=$vul['scan_no']?></li>
                        </ol>
                    </div>
                </div>
			</div>
		<?php endforeach ?>
		</div>
		<?=$Paginator->createLinks($links, 'ui pagination menu', $pageAttr, $method='ajax')?>
	<?php endif ?>
<?php
}
