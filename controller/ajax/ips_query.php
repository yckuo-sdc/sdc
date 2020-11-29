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

$page = isset($_GET['page']) ? $_GET['page'] : 1;	
$prev_page = ($page == 1) ? 1 : ($page-1);
$next_page = $page + 1;
$max_page = 10;
$max_item = 10;
$lb = ($page <= $max_page) ? 1 : $page - $max_page + 1;
$ub = ($page <= $max_page) ? $max_page : $page;					

$arr_jsonObj = json_decode($jsonObj,true);
$query_map =[ // operator + keyword
	'=' => [
		'addr.src' => 'in',
		'addr.dst' => 'in',
		'port.dst' => 'eq',
		'rule' => 'eq',
		'app' => 'eq',
		'action' => 'eq',
	],
	'!=' => [
		'addr.src' => 'notin',
		'addr.dst' => 'notin',
		'port.dst' => 'neq',
		'rule' => 'neq',
		'app' => 'neq',
		'action' => 'neq',
	]
];

if( count($arr_jsonObj) !=0 ){  // retrieve query
	$query = '';
	foreach($arr_jsonObj as $val){
		$one_query = "( ".$val['keyword']." ".$query_map[$val['operator']][$val['keyword']]." '".$val['key']."' )";
		$query = $query." AND ".$one_query;
	}
	$query = substr($query, 4);
}else{
	if($key == 'any' && $keyword == 'all' && $operator == '='){
		$query = "";
	}else{
		$query = "( ".$keyword." ".$query_map[$operator][$keyword]." '".$key."' )";
	}
}

//echo $query."<br>";
$nlogs = $max_item;
$dir = 'backward';
$skip = ($page-1)*$nlogs;
$host = PaloAltoAPI::HOSTMAP[$type];

$pa = new PaloAltoAPI($host);
$log_type_map = ['traffic','threat','data'];

for($i=0; $i<count($log_type_map); $i++){
	if(!$data = $pa->getLogList($log_type = $log_type_map[$i], $dir, $nlogs, $skip, $query)){
        echo "很抱歉，該分類分頁目前沒有資料！";
        return;
    }
?>
    該分類分頁共搜尋到<?=$data['log_count']?>筆資料！
	<div class='ui relaxed divided list'>
		 <div class='item'>
			<div class='content'>
			<a class='header'><?=$log_type_map[$i]?></a>
			</div>
		</div>
		<?php foreach($data['logs'] as $log){ ?>
			<div class='item'>
			<div class='content'>
			<a>
			<?=$log->receive_time?>&nbsp&nbsp
			<span style='background:#fde087'><?=$log->rule?></span>&nbsp&nbsp
			<?=$log->src?>&nbsp&nbsp
			<span style='background:#dddddd'><?=$log->dst?></span>&nbsp&nbsp
			<?=$log->dport?>&nbsp&nbsp
			<?=$log->app?>&nbsp&nbsp
			<span style='background:#fbc5c5'><?=$log->subtype?></span>&nbsp&nbsp
			<?=$log->action?>&nbsp&nbsp
			<i class='angle down icon'></i>
			</a>
			<div class='description'>
				<ol>
				<?php foreach($log as $keyindex => $val){ ?>
					<li><?=$keyindex?>:&nbsp<?=$val?></li>
				<?php } ?>
				</ol>
			</div>
			</div>
			</div>
		 <?php } ?>
	</div>	
<?php } ?>

<!--The desktop href-link of bottom pages-->
<div class='ui pagination menu'>
    <a class='item' href='javascript: void(0)' page='<?=$prev_page?>' key='<?=$key?>' keyword ='<?=$keyword?>' operator='<?=$operator?>' jsonObj='<?=$jsonObj?>' type='<?=$type?>'> ← </a>
    <?php for ($p = $lb; $p <= $ub ;$p++){
        if($p == $page){ ?>
            <a class='active item bold' href='javascript: void(0)' page='<?=$p?>' key='<?=$key?>' keyword ='<?=$keyword?>' operator='<?=$operator?>' jsonObj='<?=$jsonObj?>' type='<?=$type?>' ><?=$p?></a>
        <?php }else{ ?>
            <a class='item' href='javascript: void(0)' page='<?=$p?>' key='<?=$key?>' keyword ='<?=$keyword?>' operator='<?=$operator?>' jsonObj='<?=$jsonObj?>' type='<?=$type?>' ><?=$p?></a>
        <?php } 
        } ?>
    <a class='item' href='javascript: void(0)' page='<?=$next_page?>' key='<?=$key?>' keyword ='<?=$keyword?>' operator='<?=$operator?>' jsonObj='<?=$jsonObj?>' type='<?=$type?>' > → </a>
</div>

<!--The mobile href-link of bottom pages-->
<div class='ui pagination menu mobile'>	
    <a class='item' href='javascript: void(0)' page='<?=$prev_page?>' key='<?=$key?>' keyword ='<?=$keyword?>' operator='<?=$operator?>' jsonObj='<?=$jsonObj?>' type='<?=$type?>' > ← </a>
    <a class='active item bold' href='javascript: void(0)' page='<?=$page?>' key='<?=$key?>' keyword ='<?=$keyword?>' operator='<?=$operator?>' jsonObj='<?=$jsonObj?>' type='<?=$type?>' ><?=$page?></a>";
    <a class='item' href='javascript: void(0)' page='<?=$next_page?>' key='<?=$key?>' keyword ='<?=$keyword?>' operator='<?=$operator?>' jsonObj='<?=$jsonObj?>' type='<?=$type?>' > → </a>
</div>
