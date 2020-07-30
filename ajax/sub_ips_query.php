<?php
	use paloalto\api as pa;
	require("../login/function.php");
	require_once("paloalto_api.php");
	require_once("paloalto_config.inc.php");
	if( (empty($_GET['key']) ||  empty($_GET['keyword']) ||  empty($_GET['operator']) ) && count(json_decode($_GET['jsonObj'],true)) == 0  ){
		echo "沒有輸入";
		return;
	}
	$key = $_GET['key'];
	$keyword = $_GET['keyword'];
	$operator = $_GET['operator'];
	$jsonObj = $_GET['jsonObj']; 
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
			'app' => 'eq',
			'action' => 'eq',
		],
		'!=' => [
			'addr.src' => 'notin',
			'addr.dst' => 'notin',
			'port.dst' => 'neq',
			'app' => 'neq',
			'action' => 'neq',
		]
	];
	
	if( count($arr_jsonObj) !=0 ){  // retrieve query
		$query = '';
		foreach($arr_jsonObj as $val){
			$one_query = '( '.$val['keyword'].' '.$query_map[$val['operator']][$val['keyword']].' '.$val['key'].' )';
			$query = $query.' AND '.$one_query;
		}
		$query = substr($query, 4);
	}else{
		if($key == 'any' && $keyword == 'all' && $operator == '='){
			$query = '';
		}else{
			$query = '( '.$keyword.' '.$query_map[$operator][$keyword].' '.$key.' )';
		}
	}
	$nlogs = $max_item;
	$dir = "backward";
	$skip = ($page-1)*$nlogs;
	
	$pa = new pa\PaloaltoAPI($host, $username, $password);
	$log_type_map = ['traffic','threat','data'];

	for($lt=0; $lt<count($log_type_map); $lt++){
		$log_type = $log_type_map[$lt];
		$res = $pa->GetLogList($log_type, $dir, $nlogs, $skip, $query);
		$xml = simplexml_load_string($res) or die("Error: Cannot create object");
		$job = $xml->result->job;

		$type = "op";
		$cmd = "<show><query><result><id>".$job."</id></result></query></show>";
		$res = $pa->GetXmlCmdResponse($type, $cmd);
		$xml = simplexml_load_string($res) or die("Error: Cannot create object");

		if($xml['status'] != 'success'){
			echo "很抱歉，該分類分頁目前沒有資料！";
			return ;
		}
		$log_count = $xml->result->log->logs['count'];
		echo "該分類分頁共搜尋到".$log_count."筆資料！";
		echo "<div class='ui relaxed divided list'>";
			echo "<div class='item'>";
				echo "<div class='content'>";
				echo "<a class='header'>".$log_type_map[$lt]."</a>";
				echo "</div>";
			echo "</div>";
			$count = 0;
			foreach($xml->result->log->logs->entry as $log){
				if($count >= $max_item){
					break;
				}
				echo "<div class='item'>";
				echo "<div class='content'>";
				echo "<a>";
				echo $log->receive_time."&nbsp&nbsp";
				echo "<span style='background:#fde087'>".$log->rule."</span>&nbsp&nbsp";
				echo $log->src."&nbsp&nbsp";
				echo "<span style='background:#dddddd'>".$log->dst."</span>&nbsp&nbsp";
				echo $log->dport."&nbsp&nbsp";
				echo $log->app."&nbsp&nbsp";
				echo "<span style='background:#fbc5c5'>".$log->subtype."</span>&nbsp&nbsp";
				echo $log->action."&nbsp&nbsp";
				echo "<i class='angle double down icon'></i>";
				echo "</a>";
				echo "<div class='description'>";
					echo "<ol>";
					foreach($log as $keyindex => $val){
						echo "<li>$keyindex:&nbsp$val</li>";
					}
					echo "</ol>";
				echo "</div>";
				echo "</div>";
				echo "</div>";
				$count = $count + 1;
			}	
		echo "</div>";	
	}
	
	//The href-link of bottom pages
	echo "<div class='ui pagination menu'>";	
	echo "<a class='item test' href='javascript: void(0)' page='".$prev_page."' key='".$key."' keyword ='".$keyword."' operator='".$operator."' jsonObj='".$jsonObj."'> ← </a>";
	for ($p = $lb; $p <= $ub ;$p++){
		if($p == $page){
			echo"<a class='active item bold' href='javascript: void(0)' page='".$p."' key='".$key."' keyword ='".$keyword."' operator='".$operator."' jsonObj='".$jsonObj."' >".$p."</a>";
		}else{
			echo"<a class='item test' href='javascript: void(0)' page='".$p."' key='".$key."' keyword ='".$keyword."' operator='".$operator."' jsonObj='".$jsonObj."' >".$p."</a>";
		}
	}
	echo"<a class='item test' href='javascript: void(0)' page='".$next_page."' key='".$key."' keyword ='".$keyword."' operator='".$operator."' jsonObj='".$jsonObj."' > → </a>";		   echo "</div>";

	//The mobile href-link of bottom pages
	echo "<div class='ui pagination menu mobile'>";	
	echo "<a class='item test' href='javascript: void(0)' page='".$prev_page."' key='".$key."' keyword ='".$keyword."' operator='".$operator."' jsonObj='".$jsonObj."' > ← </a>";
	echo"<a class='active item bold' href='javascript: void(0)' page='".$page."' key='".$key."' keyword ='".$keyword."' operator='".$operator."' jsonObj='".$jsonObj."' >".$page."</a>";
	echo"<a class='item test' href='javascript: void(0)' page='".$next_page."' key='".$key."' keyword ='".$keyword."' operator='".$operator."' jsonObj='".$jsonObj."' > → </a>";		
	echo "</div>";
?> 
