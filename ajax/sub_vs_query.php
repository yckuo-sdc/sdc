<?php
	
	header('Content-type: text/html; charset=utf-8');
	//alert message
	function phpAlert($msg) {
		echo '<script type="text/javascript">alert("' . $msg . '")</script>';
	}

	if(!empty($_GET['key']) && !empty($_GET['keyword_type'])){
		//過濾特殊字元(')
		$key  		   = $_GET['key'];
		$keyword_type  = $_GET['keyword_type'];
		$type 		   = $_GET['type'];
		$unfinished    = $_GET['unfinished'];
		$finished 	   = $_GET['finished'];

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
				 //$condition =  "AND (status LIKE '待處理(經複查仍有弱點)' OR status LIKE '待處理' OR status LIKE '豁免(待簽核)' OR status LIKE '誤判(待簽核)')";
				 $condition =  "AND status IN ('待處理','待處理(經複查仍有弱點','豁免(待簽核)','誤判(待簽核)','已修補(待複檢)')";
				 break;
			case ($unfinished == 'false' and $finished == 'true'):
				 //$condition =  "AND (status LIKE '已修補' OR status LIKE '豁免' OR status LIKE '誤判')";
				 $condition =  "AND status IN ('已修補','豁免','誤判')";
				 break;
			case ($unfinished == 'false' and $finished == 'false'):
				 $condition =  "AND status IN ('')";
				 break;
		}
	
		if($keyword_type == "all"){
			//echo $key;
			//FullText Seach excpet the EventID,OccurrenceTime
			//$sql = "SELECT * FROM security_event WHERE Status LIKE '%".$key."%' OR EventTypeName LIKE '%".$key."%' OR Location LIKE '%".$key."%' OR IP LIKE '%".$key."%' OR BlockReason LIKE '%".$key."%' OR DeviceTypeName LIKE '%".$key."%' OR DeviceOwnerName LIKE '%".$key."%' OR DeviceOwnerPhone LIKE '%".$key."%' OR AgencyName LIKE '%".$key."%' OR UnitName LIKE '%".$key."%' OR NetworkProcessContent LIKE '%".$key."%' OR MaintainProcessContent LIKE '%".$key."%' OR AntivirusProcessContent LIKE '%".$key."%' OR UnprocessedReason LIKE '%".$key."%' OR Remarks LIKE '%".$key."%' ORDER by EventID DESC,OccurrenceTime DESC";
		}else{
			$sql = "SELECT * FROM ".$table." WHERE ".$keyword_type." LIKE '%".$key."%' ".$condition." ORDER by scan_date  DESC";
		}
		$result = mysqli_query($conn,$sql);
		if(!$result){
			echo"Error:".mysqli_error($conn);
			exit();
		}
		$rowcount = mysqli_num_rows($result);
		if ($rowcount == 0){
			echo "很抱歉，該分類目前沒有資料！";
		}
		else{
			echo "該分類共搜尋到".$rowcount."筆資料！";
				echo "<div class='ui relaxed divided list'>";
					echo "<div class='item'>";
						echo "<div class='content'>";
							echo "<a class='header'>";
							//echo "序號&nbsp";
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
					echo str_replace("/臺南市政府/","",$row['ou'])."&nbsp&nbsp";
					echo "<span style='background:#fde087'>".$row['system_name']."</span>&nbsp&nbsp";
					echo $row['status']."&nbsp&nbsp";
					echo "<span style='background:#DDDDDD'>".$row['vitem_name']."</span>&nbsp&nbsp";
					echo $row['scan_no']."&nbsp&nbsp";
			
		
					echo "<i class='angle double down icon'></i>";
					echo "</a>";
					echo "<div class='description'>";
					 
						echo "<ol>";
						echo "<li>弱點序號:".$row['vitem_id']."</li>";
						echo "<li>弱點名稱:".$row['vitem_name']."</li>";
						echo "<li>單位:".str_replace("/臺南市政府/","",$row['ou'])."</li>";
						echo "<li>系統名稱:".$row['system_name']."</li>";
						echo "<li>IP:".$row['ip']."</li>";
						echo "<li>掃描日期:".date_format(new DateTime($row['scan_date']),'Y-m-d')."</li>";
						echo "<li>管理員:".$row['manager']."</li>";
						echo "<li>Email:".$row['email']."</li>";
						//urlscanResult's extra field 
						if($table=="urlscanResult"){
							echo "<li>影響網址:<a href='".$row['affect_url']."' target='_blank'>".$row['affect_url']."</a></li>";
						}
						echo "<li>弱點詳細資訊:<a href='".$row['url']."' target='_blank'>".$row['url']."</a></li>";
						echo "<li>總類:".$row['category']."</li>";
						echo "<li>風險程度:".$row['severity']."</li>";
						echo "<li>掃描期別:".$row['scan_no']."</li>";
						echo "</ol>";
					  
					echo "</div>";
					echo "</div>";
				echo "</div>";
			}
			echo "</div>";

		}
		
		$conn->close();
		
	}else{
		phpAlert("沒有輸入");
	}
	
?> 
