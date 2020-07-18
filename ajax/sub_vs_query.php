<?php
	require("../login/function.php");
	if( (!empty($_GET['key']) && !empty($_GET['keyword']) && !empty($_GET['type']) ) || count(json_decode($_GET['jsonObj'],true)) !=0  ){
		//過濾特殊字元(')
		$key = $_GET['key'];
		$keyword = $_GET['keyword'];
		$type = $_GET['type'];
		$overdue_and_unfinish = $_GET['overdue_and_unfinish'];
		$non_overdue_and_unfinish = $_GET['non_overdue_and_unfinish'];
		$finish = $_GET['finish'];
		$jsonObj = $_GET['jsonObj']; 
		if (!isset($_GET['page']))	$pages = 1; 
		else						$pages = $_GET['page']; 
		if (!isset($_GET['ap']))	$ap = 'html'; 
		else						$ap = $_GET['ap']; 

		$jsonObj = json_decode($jsonObj,true);

		//connect database
        require("../mysql_connect.inc.php");
		 //特殊字元跳脫(NUL (ASCII 0), \n, \r, \, ', ", and Control-Z)
		$type = mysqli_real_escape_string($conn,$type);
		$overdue_and_unfinish = mysqli_real_escape_string($conn,$overdue_and_unfinish);
		$non_overdue_and_unfinish = mysqli_real_escape_string($conn,$non_overdue_and_unfinish);
		$finish = mysqli_real_escape_string($conn,$finish);
		
		$table_map = [
			'ip_and_url_scanResult' => 'V_ip_and_url_scanResult',
			'ipscanResult' => 'ipscanResult',
			'urlscanResult' => 'urlscanResult'
		];
		$table = $table_map[$type];
		
		$status_map = [	//overdue_and_unfinish + non_overdue_and_unfinish + finish
			"true" => [
				"true" =>  [ 
					"true" => "" , //done 
					"false" => "AND status IN ('待處理','待處理(經複查仍有弱點)','豁免(待簽核)','誤判(待簽核)','已修補(待複檢)')" //done
				],
				"false" => [ 
					"true" => "AND (
						( severity IN ('High','Critical') AND status IN ('待處理','待處理(經複查仍有弱點)','豁免(待簽核)','誤判(待簽核)','已修補(待複檢)') AND scan_date < DATE_SUB(NOW(), INTERVAL 1 MONTH) )
						OR 
						( severity IN ('Medium') AND status IN ('待處理','待處理(經複查仍有弱點)','豁免(待簽核)','誤判(待簽核)','已修補(待複檢)') AND scan_date < DATE_SUB(NOW(), INTERVAL 2 MONTH) )
						OR status IN ('已修補','豁免','誤判')
					)"  ,  //done
					"false" => "AND (
						( severity IN ('High','Critical') AND status IN ('待處理','待處理(經複查仍有弱點)','豁免(待簽核)','誤判(待簽核)','已修補(待複檢)') AND scan_date < DATE_SUB(NOW(), INTERVAL 1 MONTH) )
						OR 
						( severity IN ('Medium') AND status IN ('待處理','待處理(經複查仍有弱點)','豁免(待簽核)','誤判(待簽核)','已修補(待複檢)') AND scan_date < DATE_SUB(NOW(), INTERVAL 2 MONTH) )
					)"  //done
				]
			],
			"false" => [	
				"true" => [
					"true" => "AND (
						( severity IN ('High','Critical') AND status IN ('待處理','待處理(經複查仍有弱點)','豁免(待簽核)','誤判(待簽核)','已修補(待複檢)') AND scan_date >= DATE_SUB(NOW(), INTERVAL 1 MONTH) )
						OR 
						( severity IN ('Medium') AND status IN ('待處理','待處理(經複查仍有弱點)','豁免(待簽核)','誤判(待簽核)','已修補(待複檢)') AND scan_date >= DATE_SUB(NOW(), INTERVAL 2 MONTH) )
						OR status IN ('已修補','豁免','誤判')
					)"  ,  //done
					"false" => "AND (
						( severity IN ('High','Critical') AND status IN ('待處理','待處理(經複查仍有弱點)','豁免(待簽核)','誤判(待簽核)','已修補(待複檢)') AND scan_date >= DATE_SUB(NOW(), INTERVAL 1 MONTH) )
						OR 
						( severity IN ('Medium') AND status IN ('待處理','待處理(經複查仍有弱點)','豁免(待簽核)','誤判(待簽核)','已修補(待複檢)') AND scan_date >= DATE_SUB(NOW(), INTERVAL 2 MONTH) )
					)" //done
				],
				"false" => [ 
					"true" => "AND status IN ('已修補','豁免','誤判')", //done
					'false' => "AND status IN ('')"  //done 
				]
			]	
		];
		$status_condition = $status_map[$overdue_and_unfinish][$non_overdue_and_unfinish][$finish];
		//echo $status_condition."<br>";
		
		if( count($jsonObj) !=0 ){
			$condition = "";
			foreach($jsonObj as $val){
				$val['key']		= mysqli_real_escape_string($conn,$val['key']);
				$val['keyword'] = mysqli_real_escape_string($conn,$val['keyword']);
				if($val['keyword'] == "all"){
					$one_condition = "(".getFullTextSearchSQL($conn,$table,$val['key']).") "; 
				}else{
					$one_condition = $val['keyword']." LIKE '%".$val['key']."%' ";
				}
				$condition = $condition." AND ".$one_condition;
			}
			$condition = substr($condition,4).$status_condition;
		}else{
			//特殊字元跳脫(NUL (ASCII 0), \n, \r, \, ', ", and Control-Z)
			$key			 = mysqli_real_escape_string($conn,$key);
			$keyword	 = mysqli_real_escape_string($conn,$keyword);
			if($keyword == "all"){
				//FullText Seach
				$condition = "(".getFullTextSearchSQL($conn,$table,$key).") ".$status_condition; 
			}else{
				$condition = $keyword." LIKE '%".$key."%' ".$status_condition;
			}
		}
		//echo $condition."<br>";
		$order = "ORDER by scan_no DESC,system_name DESC,status DESC";
		$sql = "SELECT * FROM ".$table." WHERE ".$condition." ".$order;
		$result = mysqli_query($conn,$sql);
		if(!$result){
			echo"Error:".mysqli_error($conn);
			exit();
		}
		$rowcount = mysqli_num_rows($result);
		if($ap=='html'){
			if ($rowcount == 0){
				echo "很抱歉，該分類目前沒有資料！";
			}
			else{
				echo "該分類共搜尋到".$rowcount."筆資料！";
				//record number on each page & maxumun pages on pagination			
				$per = 10; 	
				$max_pages = 10;
				list($sql_subpage,$prev_page,$next_page,$lower_bound,$upper_bound,$Totalpages) = getPaginationSQL($sql,$per,$max_pages,$rowcount,$pages);
				$result = mysqli_query($conn,$sql_subpage);
				echo "<div class='ui relaxed divided list'>";
				while($row = mysqli_fetch_assoc($result)) {
					echo "<div class='item'>";
					echo "<div class='content'>";
						echo "<a>";
						if($table=="V_ip_and_url_scanResult"){
							echo "<span style='background:#f3c4c4'>".$row['type']."</span>&nbsp&nbsp";
						}
						echo $row['flow_id']."&nbsp&nbsp";
						echo str_replace("/臺南市政府/","",$row['ou'])."&nbsp&nbsp";
						echo "<span style='background:#fde087'>".$row['system_name']."</span>&nbsp&nbsp";
						echo $row['status']."&nbsp&nbsp";
						echo "<span style='background:#DDDDDD'>".$row['vitem_name']."</span>&nbsp&nbsp";
						echo $row['scan_no']."&nbsp&nbsp";
				
			
						echo "<i class='angle double down icon'></i>";
						echo "</a>";
						echo "<div class='description'>";
							echo "<ol>";
							if($table=="V_ip_and_url_scanResult"){
								echo "<li>弱點類別:".$row['type']."</li>";
							}
							echo "<li>流水號:".$row['flow_id']."</li>";
							echo "<li>弱點序號:".$row['vitem_id']."</li>";
							echo "<li>弱點名稱:".$row['vitem_name']."</li>";
							echo "<li>OID:".$row['OID']."</li>";
							echo "<li>單位:".str_replace("/臺南市政府/","",$row['ou'])."</li>";
							echo "<li>系統名稱:".$row['system_name']."</li>";
							echo "<li>IP:".$row['ip']."</li>";
							echo "<li>掃描日期:".date_format(new DateTime($row['scan_date']),'Y-m-d')."</li>";
							echo "<li>管理員:".$row['manager']."</li>";
							echo "<li>Email:".$row['email']."</li>";
							//urlscanResult's extra field 
							if($table=="urlscanResult" || $table=="V_ip_and_url_scanResult"){
								echo "<li>影響網址:<a href='".$row['affect_url']."' target='_blank'>".$row['affect_url']."</a></li>";
							}
							echo "<li>弱點詳細資訊:<a href='".$row['url']."' target='_blank'>".$row['url']."</a></li>";
							echo "<li>總類:".$row['category']."</li>";
							echo "<li>風險程度:".$row['severity']."</li>";
							echo "<li>弱點處理情形:".$row['status']."</li>";
							echo "<li>掃描期別:".$row['scan_no']."</li>";
							echo "</ol>";
						  
						echo "</div>";
						echo "</div>";
					echo "</div>";
				}
				echo "</div>";
				//The href-link of bottom pages
				echo "<div class='ui pagination menu'>";	
				echo "<a class='item test' href='javascript: void(0)' page='1' key='".$key."' keyword ='".$keyword."' type='".$type."' overdue_and_unfinish='".$overdue_and_unfinish."' non_overdue_and_unfinish ='".$non_overdue_and_unfinish."' finish ='".$finish."' >首頁</a>";
				echo "<a class='item test' href='javascript: void(0)' page='".$prev_page."' key='".$key."' keyword ='".$keyword."' type='".$type."' overdue_and_unfinish='".$overdue_and_unfinish."' non_overdue_and_unfinish='".$non_overdue_and_unfinish."' finish='".$finish."' > ← </a>";
				for ($j = $lower_bound; $j <= $upper_bound ;$j++){
					if($j == $pages){
						echo"<a class='active item bold' href='javascript: void(0)' page='".$j."' key='".$key."' keyword ='".$keyword."' type='".$type."' overdue_and_unfinish='".$overdue_and_unfinish."' non_overdue_and_unfinish='".$non_overdue_and_unfinish."' finish='".$finish."'>".$j."</a>";
					}else{
						echo"<a class='item test' href='javascript: void(0)' page='".$j."' key='".$key."' keyword ='".$keyword."' type='".$type."' overdue_and_unfinish='".$overdue_and_unfinish."' non_overdue_and_unfinish='".$non_overdue_and_unfinish."' finish='".$finish."'>".$j."</a>";
					}
				}
				echo"<a class='item test' href='javascript: void(0)' page='".$next_page."' key='".$key."' keyword ='".$keyword."' type='".$type."' overdue_and_unfinish='".$overdue_and_unfinish."' non_overdue_and_unfinish='".$non_overdue_and_unfinish."' finish='".$finish."' > → </a>";		
				//last page
				echo"<a class='item test' href='javascript: void(0)' page='".$Totalpages."' key='".$key."' keyword ='".$keyword."' type='".$type."' overdue_and_unfinish='".$overdue_and_unfinish."' non_overdue_and_unfinish='".$non_overdue_and_unfinish."' finish='".$finish."'>末頁</a>";
				echo "</div>";

				//The mobile href-link of bottom pages
				echo "<div class='ui pagination menu mobile'>";	
				echo "<a class='item test' href='javascript: void(0)' page='".$prev_page."' key='".$key."' keyword ='".$keyword."' type='".$type."' overdue_and_unfinish='".$overdue_and_unfinish."' non_overdue_and_unfinish='".$non_overdue_and_unfinish."' finish='".$finish."'> ← </a>";
				echo"<a class='active item bold' href='javascript: void(0)' page='".$pages."' key='".$key."' keyword ='".$keyword."' type='".$type."' overdue_and_unfinish='".$overdue_and_unfinish."' non_overdue_and_unfinish='".$non_overdue_and_unfinish."' finish='".$finish."'>(".$pages."/".$Totalpages.")</a>";
				echo"<a class='item test' href='javascript: void(0)' page='".$next_page."' key='".$key."' keyword ='".$keyword."' type='".$type."' overdue_and_unfinish='".$overdue_and_unfinish."' non_overdue_and_unfinish='".$non_overdue_and_unfinish."' finish='".$finish."'> → </a>";		
				echo "</div>";
			}
		}elseif($ap='csv'){
			$arrs=array();
			while($row = mysqli_fetch_row($result)) {
				foreach($row as $key => $val){
					$arr[$key] = $val;
				}
				array_push($arrs,$arr);	
			}
			//var_dump($arrs);
			array_to_csv_download($arrs,"export.csv",";"); 	
		}
		
		$conn->close();
		
	}else{
		echo "沒有輸入";
	}
	
?> 
