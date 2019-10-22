<?php
	
	header('Content-type: text/html; charset=utf-8');
	include("../login/function.php");
	//alert message
	//function phpAlert($msg) {
	//	echo '<script type="text/javascript">alert("' . $msg . '")</script>';
	//}

	if(!empty($_GET['key']) && !empty($_GET['keyword_type']) && !empty($_GET['type']) ){
		//過濾特殊字元(')
		$key  		   = $_GET['key'];
		$keyword_type  = $_GET['keyword_type'];
		$type  		   = $_GET['type'];
		
		//connect database
        require("../mysql_connect.inc.php");
		 //特殊字元跳脫(NUL (ASCII 0), \n, \r, \, ', ", and Control-Z)
		$key			 = mysqli_real_escape_string($conn,$key);
		$keyword_type	 = mysqli_real_escape_string($conn,$keyword_type);
		$type	 		 = mysqli_real_escape_string($conn,$type);
		
		//event or contact & fulltext or single search
		switch(true){
			case ($type == 'security_event' and $keyword_type != 'all'):
				$table = "security_event";
				$condition = $keyword_type." LIKE '%".$key."%'";
			    $order = "ORDER by EventID DESC,OccurrenceTime DESC";	
				break;
			case ($type == 'security_contact' and $keyword_type != 'all'):
				$table = "security_contact";
				$condition = $keyword_type." LIKE '%".$key."%'";
				// select security_contact from NCERT and Internal_Primary Unit from self-creation
				$table = "(SELECT * FROM security_contact UNION SELECT * FROM security_contact_extra)A";
			    $order = "ORDER by OID asc,person_type asc";	
				break;
			case ($type == 'security_event' and $keyword_type == 'all'):
				$table = "security_event";
				//FullText Seach
				$condition = getFullTextSearchSQL($conn,$table,$key);
			    $order = "ORDER by EventID DESC,OccurrenceTime DESC";	
				//$condition = "MATCH(Status,EventTypeName,Location,IP,BlockReason,DeviceTypeName,DeviceOwnerName,DeviceOwnerPhone,AgencyName,UnitName,NetworkProcessContent,MaintainProcessContent,AntivirusProcessContent,UnprocessedReason,Remarks) AGAINST ('+".$key."' IN BOOLEAN MODE)";
				//$condition = "Status LIKE '%".$key."%' OR EventTypeName LIKE '%".$key."%' OR Location LIKE '%".$key."%' OR IP LIKE '%".$key."%' OR BlockReason LIKE '%".$key."%' OR DeviceTypeName LIKE '%".$key."%' OR DeviceOwnerName LIKE '%".$key."%' OR DeviceOwnerPhone LIKE '%".$key."%' OR AgencyName LIKE '%".$key."%' OR UnitName LIKE '%".$key."%' OR NetworkProcessContent LIKE '%".$key."%' OR MaintainProcessContent LIKE '%".$key."%' OR AntivirusProcessContent LIKE '%".$key."%' OR UnprocessedReason LIKE '%".$key."%' OR Remarks LIKE '%".$key."%'";
				break;
			case ($type == 'security_contact' and $keyword_type == 'all'):
				$table = "security_contact";
				//FullText Seach
				$condition = getFullTextSearchSQL($conn,$table,$key);
				// select security_contact from NCERT and Internal_Primary Unit from self-creation
				$table = "(SELECT * FROM security_contact UNION SELECT * FROM security_contact_extra)A";
				$order = "ORDER by OID asc,person_type asc";
				//$condition = "OID LIKE '%".$key."%' OR organization LIKE '%".$key."%' OR person_name LIKE '%".$key."%' OR unit LIKE '%".$key."%' OR position LIKE '%".$key."%' OR person_type LIKE '%".$key."%' OR address LIKE '%".$key."%' OR tel LIKE '%".$key."%' OR ext LIKE '%".$key."%' OR fax LIKE '%".$key."%' OR email LIKE '%".$key."%'";
				break;
		}
		$sql = "SELECT * FROM ".$table." WHERE ".$condition." ".$order;
		//echo $sql."<br>";
		$result = mysqli_query($conn,$sql);
		if(!$result){
			echo"Error:".mysqli_error($conn);
			echo "kkc";
			exit();
		}
		$rowcount = mysqli_num_rows($result);
		if ($rowcount == 0){
			echo "很抱歉，該分類目前沒有資料！";
		}
		else{
			echo "該分類共搜尋到".$rowcount."筆資料！";

			switch($type){
				case "security_event": 
				echo "<div class='ui relaxed divided list'>";
					echo "<div class='item'>";
						echo "<div class='content'>";
							echo "<a class='header'>";
							//echo "序號&nbsp";
							echo "發現日期&nbsp&nbsp";
							echo "結案狀態&nbsp&nbsp";
							echo "資安事件類型&nbsp&nbsp";
							echo "位置&nbsp&nbsp";
							echo "設備IP&nbsp&nbsp";
							echo "姓名&nbsp&nbsp";
							echo "分機&nbsp&nbsp";
							echo "<a>";
						echo "</div>";
					echo "</div>";
						while($row = mysqli_fetch_assoc($result)) {
							echo "<div class='item'>";
							echo "<div class='content'>";
								echo "<a>";
								//echo $row['EventID']."&nbsp&nbsp"";
								if($row['Status']=="已結案")echo "<i class='check circle icon' style='color:green'></i>";
								else echo "<i class='exclamation circle icon'></i>";
								echo date_format(new DateTime($row['OccurrenceTime']),'Y-m-d')."&nbsp&nbsp";
								echo $row['Status']."&nbsp&nbsp";
								echo "<span style='background:#fde087'>".$row['EventTypeName']."</span>&nbsp&nbsp";
								echo $row['Location']."&nbsp&nbsp";
								echo "<span style='background:#DDDDDD'>".$row['IP']."</span>&nbsp&nbsp";
								echo $row['DeviceOwnerName']."&nbsp&nbsp";
								echo $row['DeviceOwnerPhone']."&nbsp&nbsp";
								echo "<i class='angle double down icon'></i>";
								echo "</a>";
								echo "<div class='description'>";
									echo "<ol>";
									echo "<li>序號:".$row['EventID']."</li>";
									echo "<li>結案狀態:".$row['Status']."</li>";
									echo "<li>發現日期:".date_format(new DateTime($row['OccurrenceTime']),'Y-m-d')."</li>";
									echo "<li>資安事件類型:".$row['EventTypeName']."</li>";
									echo "<li>位置:".$row['Location']."</li>";
									echo "<li>電腦IP:".$row['IP']."</li>";
									echo "<li>封鎖原因:".$row['BlockReason']."</li>";
									echo "<li>設備類型:".$row['DeviceTypeName']."</li>";
									echo "<li>電腦所有人姓名:".$row['DeviceOwnerName']."</li>";
									echo "<li>電腦所有人分機:".$row['DeviceOwnerPhone']."</li>";
									echo "<li>機關:".$row['AgencyName']."</li>";
									echo "<li>單位:".$row['UnitName']."</li>";
									echo "<li>處理日期(國眾):".$row['NetworkProcessContent']."</li>";
									echo "<li>處理日期(三佑科技):".$row['MaintainProcessContent']."</li>";
									echo "<li>處理日期(京稘或中華SOC):".$row['AntivirusProcessContent']."</li>";
									echo "<li>未能處理之原因及因應方式:".$row['UnprocessedReason']."</li>";
									echo "</ol>";
								echo "</div>";
								echo "</div>";
							echo "</div>";

						}
					echo "</div>";
					break;
				case "security_contact":
				echo "<div class='ui relaxed divided list'>";
					echo "<div class='item'>";
						echo "<div class='content'>";
							echo "<a class='header'>";
							echo "機關名稱&nbsp&nbsp";
							echo "姓名&nbsp&nbsp";
							echo "聯絡人類別&nbsp&nbsp";
							echo "信箱&nbsp&nbsp";
							echo "電話&nbsp&nbsp";
							echo "<a>";
						echo "</div>";
					echo "</div>";

					while($row = mysqli_fetch_assoc($result)) {
						echo "<div class='item'>";
						echo "<div class='content'>";
							echo "<a>";
							echo $row['organization']."&nbsp&nbsp";
							echo $row['person_name']."&nbsp&nbsp";
							echo "<span style='background:#fde087'>".$row['person_type']."</span>&nbsp&nbsp";
							echo $row['email']."&nbsp&nbsp";
							echo "<span style='background:#DDDDDD'>".$row['tel']."#".$row['ext']."</span>&nbsp&nbsp";
							echo "<i class='angle double down icon'></i>";
							echo "</a>";
							echo "<div class='description'>";
								echo "<ol>";
								echo "<li>序號:".$row['CID']."</li>";
								echo "<li>OID:".$row['OID']."</li>";
								echo "<li>機關名稱:".$row['organization']."</li>";
								echo "<li>姓名:".$row['person_name']."</li>";
								echo "<li>單位名稱:".$row['unit']."</li>";
								echo "<li>職稱:".$row['position']."</li>";
								echo "<li>資安聯絡人類型:".$row['person_type']."</li>";
								echo "<li>地址:".$row['address']."</li>";
								echo "<li>電話:".$row['tel']."</li>";
								echo "<li>分機:".$row['ext']."</li>";
								echo "<li>傳真:".$row['fax']."</li>";
								echo "<li>email:".$row['email']."</li>";
								echo "</ol>";
							echo "</div>";
						echo "</div>";
						echo "</div>";
					}
				echo "</div>";
				break;
			}
		}
		$conn->close();
	}else{
		phpAlert("沒有輸入");
	}
	
?> 
