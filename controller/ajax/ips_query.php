<?php
// input validation
$v1 = 0;
$v2 = 0;

foreach ($_GET as $getkey => $val) {
	${$getkey} = $val;
	if ($getkey == "jsonConditions" && $val == "[]") {
		$v1 = 1;	
	} elseif ($getkey != "jsonConditions" && $val == "") {
		$v2 = 1;	
	}
}

if ($v1 && $v2) {
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

$arr_jsonConditions = json_decode($jsonConditions,true);

$field_operator_map = array (
    'addr.src' => array (
        '=' => 'in',
        '!=' => 'notin',
    ),
    'addr.dst' => array (
        '=' => 'in',
        '!=' => 'notin',
    ),
    'port.dst' => array (
        '=' => 'eq',
        '!=' => 'neq',
    ),
    'rule' => array (
        '=' => 'eq',
        '!=' => 'neq',
    ),
    'app' => array (
        '=' => 'eq',
        '!=' => 'neq',
    ),
    'action' => array (
        '=' => 'eq',
        '!=' => 'neq',
    ),
    'receive_time' => array (
        '=' => 'eq',
        '>=' => 'geq',
        '<=' => 'leq',
    ),
);

// retrieve query
$query = "";
if (empty($arr_jsonConditions)) {   // single query 
	if ($key != 'any' || $keyword != 'all' ||  $operator != '=') {
        $translated_operator = @$field_operator_map[$keyword][$operator];
        if (empty($translated_operator)) {  
            echo "invalid operator " . $operator . " for field " . $keyword;
            return ;
        }

		$query = "( " . $keyword . " " . $translated_operator . " '" . $key . "' )";
	}
} else {    // multipe query
	foreach($arr_jsonConditions as $val) {
        $key = $val['key'];
        $keyword = $val['keyword'];
        $operator = $val['operator'];
        $translated_operator = @$field_operator_map[$keyword][$operator];
        if (empty($translated_operator)) {  
            echo "invalid operator " . $operator . " for field " . $keyword;
            return ;
        }

		$single_query = "( " . $keyword . " " . $translated_operator . " '" . $key . "' )";
		$query = $query . " AND " . $single_query;
	}
	$query = substr($query, 4);
}

$nlogs = $max_item;
$dir = "backward";
$skip = ($page-1)*$nlogs;

$pa = new PaloAltoAPI($type);
$log_type_map = ['traffic', 'threat', 'data'];
$header_keys = ['receive_time', 'rule', 'src', 'dst', 'dport', 'app', 'subtype', 'action']; 
?>
<?php foreach($log_type_map as $log_type): ?>
	<?php $data = $pa->retrieveLogs($log_type, $dir, $nlogs, $skip, $query); ?>
    該分類分頁共搜尋到 <?=$data['log_count']?> 筆資料！
	<div class='ui relaxed divided list'>
		 <div class='item'>
			<div class='content'>
                <a class='header'><?=$log_type?></a>
			</div>
		</div>
		<?php foreach($data['logs'] as $log): ?>
            <?php $header_array = array_keys_whitelist($log, $header_keys); ?>
            <?php if (array_key_exists("rule", $header_array)): ?>
                <?php $header_array['rule'] = "<div class='ui orange label'>" . $header_array['rule'] . "</div>"; ?>
            <?php endif ?>
            <?php if (array_key_exists("action", $header_array)): ?>
                <?php $header_array['action'] = "<div class='ui grey label'>" . $header_array['action'] . "</div>"; ?>
            <?php endif ?>
            <?php $header_text = implode(" <font color='silver'>/</font> ", $header_array); ?>
			<div class='item'>
                <div class='content'>
                    <a>
                        <?=$header_text?>
                        <i class='angle down icon'></i>
                    </a>
                    <div class='description'>
                        <div class="ui ordered list">
                            <?php foreach($log as $keyindex => $val): ?>
                                <div class="item">
                                    <?=$keyindex?>:&nbsp;
                                     <?php if(!is_array($val)): ?>
                                        <?=$val?>
                                     <?php else: ?>
                                        <?php array_walk_recursive($val, 'test_print'); ?>
                                     <?php endif ?>
                                </div>
                            <?php endforeach ?>
                        </div>
                    </div>
                </div>
			</div>
		 <?php endforeach ?>
	</div>	
<?php endforeach ?>

<!-- the desktop pagination -->
<div class='ui pagination menu'>
    <a class='item' page='<?=$prev_page?>' key='<?=$key?>' keyword ='<?=$keyword?>' operator='<?=$operator?>' jsonConditions='<?=$jsonConditions?>' type='<?=$type?>'> ← </a>
    <?php for($p = $lb; $p <= $ub ;$p++): ?>
        <?php if($p == $page): ?>
            <a class='active item bold' page='<?=$p?>' key='<?=$key?>' keyword ='<?=$keyword?>' operator='<?=$operator?>' jsonConditions='<?=$jsonConditions?>' type='<?=$type?>' ><?=$p?></a>
        <?php else: ?>
            <a class='item' page='<?=$p?>' key='<?=$key?>' keyword ='<?=$keyword?>' operator='<?=$operator?>' jsonConditions='<?=$jsonConditions?>' type='<?=$type?>' ><?=$p?></a>
        <?php endif ?> 
    <?php endfor ?>
    <a class='item' page='<?=$next_page?>' key='<?=$key?>' keyword ='<?=$keyword?>' operator='<?=$operator?>' jsonConditions='<?=$jsonConditions?>' type='<?=$type?>' > → </a>
</div>

<!-- the mobile pagination -->
<div class='ui pagination menu mobile'>	
    <a class='item' page='<?=$prev_page?>' key='<?=$key?>' keyword ='<?=$keyword?>' operator='<?=$operator?>' jsonConditions='<?=$jsonConditions?>' type='<?=$type?>' > ← </a>
    <a class='active item bold' page='<?=$page?>' key='<?=$key?>' keyword ='<?=$keyword?>' operator='<?=$operator?>' jsonConditions='<?=$jsonConditions?>' type='<?=$type?>' ><?=$page?></a>
    <a class='item' page='<?=$next_page?>' key='<?=$key?>' keyword ='<?=$keyword?>' operator='<?=$operator?>' jsonConditions='<?=$jsonConditions?>' type='<?=$type?>' > → </a>
</div>
