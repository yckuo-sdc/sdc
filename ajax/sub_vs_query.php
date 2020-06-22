<?php
	
	header('Content-type: text/html; charset=utf-8');
	include("../login/function.php");

	if(!empty($_GET['key']) && !empty($_GET['keyword_type'])){
		//過濾特殊字元(')
		$key  		   = $_GET['key'];
		$keyword_type  = $_GET['keyword_type'];
		$type 		   = $_GET['type'];
		$unfinished    = $_GET['unfinished'];
		$finished 	   = $_GET['finished'];
		if (!isset($_GET['page']))	$pages = 1; 
		else						$pages = $_GET['page']; 
		if (!isset($_GET['ap']))	$ap = 'html'; 
		else						$ap = $_GET['ap']; 

		//connect database
        require("../mysql_connect.inc.php");
		 //特殊字元跳脫(NUL (ASCII 0), \n, \r, \, ', ", and Control-Z)
		$key			 = mysqli_real_escape_string($conn,$key);
		$keyword_type	 = mysqli_real_escape_string($conn,$keyword_type);
		$type			 = mysqli_real_escape_string($conn,$type);
		$unfinished 	 = mysqli_real_escape_string($conn,$unfinished);
		$finished 		 = mysqli_real_escape_string($conn,$finished);

		//ipscanResult or urlscanResult
		switch($type){
			case "ip_and_url_scanResult":
				$table = "V_ip_and_url_scanResult";
				break;
			case "ipscanResult":
				$table = "ipscanResult";
				break;
			case "urlscanResult":
				$table = "urlscanResult";
				break;
		}
		//unfinished + finished
	    switch(true){
			case ($unfinished == 'true' and $finished == 'true'):
				 $condition =  "";
				 break;
			case ($unfinished == 'true' and $finished == 'false'):
				 $condition =  "AND status IN ('待處理','待處理(經複查仍有弱點)','豁免(待簽核)','誤判(待簽核)','已修補(待複檢)')";
				 break;
			case ($unfinished == 'false' and $finished == 'true'):
				 $condition =  "AND status IN ('已修補','豁免','誤判')";
				 break;
			case ($unfinished == 'false' and $finished == 'false'):
				 $condition =  "AND status IN ('')";
				 break;
		}
	
		if($keyword_type == "all"){
			//FullText Seach
			$condition = "(".getFullTextSearchSQL($conn,$table,$key).") ".$condition; 
		}else{
			$condition = $keyword_type." LIKE '%".$key."%' ".$condition;
		}
		$order = "ORDER by scan_no DESC,system_name DESC,status DESC";
		$sql = "SELECT * FROM ".$table." WHERE ".$condition." ".$order;
		//echo $sql."<br>";
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
				if($table=="V_ip_and_url_scanResult"){
					/*$condition_host = "type LIKE '主機弱點' AND ".$condition;
					$condition_url 	= "type LIKE '網站弱點' AND ".$condition;
					$sql_host 		= "SELECT * FROM ".$table." WHERE ".$condition_host." ".$order;
					$sql_url 		= "SELECT * FROM ".$table." WHERE ".$condition_url." ".$order;
					$result_host 	= mysqli_query($conn,$sql_host);
					$result_url 	= mysqli_query($conn,$sql_url);
					$rowcount_host  = mysqli_num_rows($result_host);
					$rowcount_url  = mysqli_num_rows($result_url);
					echo "該分類共搜尋到".$rowcount_host."筆資料！";
					echo "該分類共搜尋到".$rowcount_url."筆資料！";
				 */}
					echo "<div class='ui relaxed divided list'>";
						echo "<div class='item'>";
							echo "<div class='content'>";
								echo "<a class='header'>";
								//echo "序號&nbsp";
								if($table=="V_ip_and_url_scanResult"){
									echo "弱點類別&nbsp&nbsp";
								}
								echo "流水號&nbsp&nbsp";
								echo "單位&nbsp&nbsp";
								echo "系統名稱&nbsp&nbsp";
								echo "處理狀態&nbsp&nbsp";
								echo "弱點名稱&nbsp&nbsp";
								echo "掃描期別&nbsp&nbsp";
								echo "<a>";
							echo "</div>";
						echo "</div>";

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
				echo "<a class='item test' href='javascript: void(0)' page='1' key='".$key."' keyword_type ='".$keyword_type."' type='".$type."' unfinished='".$unfinished."' finished='".$finished."' >首頁</a>";
				echo "<a class='item test' href='javascript: void(0)' page='".$prev_page."' key='".$key."' keyword_type ='".$keyword_type."' type='".$type."' unfinished='".$unfinished."' finished='".$finished."' > ← </a>";
				for ($j = $lower_bound; $j <= $upper_bound ;$j++){
					if($j == $pages){
						echo"<a class='active item bold' href='javascript: void(0)' page='".$j."' key='".$key."' keyword_type ='".$keyword_type."' type='".$type."' unfinished='".$unfinished."' finished='".$finished."'>".$j."</a>";
					}else{
						echo"<a class='item test' href='javascript: void(0)' page='".$j."' key='".$key."' keyword_type ='".$keyword_type."' type='".$type."' unfinished='".$unfinished."' finished='".$finished."'>".$j."</a>";
					}
				}
				echo"<a class='item test' href='javascript: void(0)' page='".$next_page."' key='".$key."' keyword_type ='".$keyword_type."' type='".$type."' unfinished='".$unfinished."' finished='".$finished."' > → </a>";		
				//last page
				echo"<a class='item test' href='javascript: void(0)' page='".$Totalpages."' key='".$key."' keyword_type ='".$keyword_type."' type='".$type."' unfinished='".$unfinished."' finished='".$finished."'>末頁</a>";
				echo "</div>";

				//The mobile href-link of bottom pages
				echo "<div class='ui pagination menu mobile'>";	
				echo "<a class='item test' href='javascript: void(0)' page='".$prev_page."' key='".$key."' keyword_type ='".$keyword_type."' type='".$type."' unfinished='".$unfinished."' finished='".$finished."'> ← </a>";
				echo"<a class='active item bold' href='javascript: void(0)' page='".$pages."' key='".$key."' keyword_type ='".$keyword_type."' type='".$type."' unfinished='".$unfinished."' finished='".$finished."'>(".$pages."/".$Totalpages.")</a>";
				echo"<a class='item test' href='javascript: void(0)' page='".$next_page."' key='".$key."' keyword_type ='".$keyword_type."' type='".$type."' unfinished='".$unfinished."' finished='".$finished."'> → </a>";		
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
		phpAlert("沒有輸入");
	}
	
?> 
