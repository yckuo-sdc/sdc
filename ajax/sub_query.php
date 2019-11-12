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
			case ($type == 'tainangov_security_Incident' and $keyword_type != 'all'):
				$table = "tainangov_security_Incident";
				$condition = $keyword_type." LIKE '%".$key."%'";
			    $order = "ORDER by IncidentID DESC,OccurrenceTime DESC";	
				break;
			case ($type == 'security_contact' and $keyword_type != 'all'):
				$table = "security_contact";
				$condition = $keyword_type." like '%".$key."%'";
				// select security_contact from ncert and internal_primary unit from self-creation
				$table = "(select * from security_contact union select * from security_contact_extra)a";
			    $order = "order by oid asc,person_type asc";	
				break;
			case ($type == 'security_event' and $keyword_type == 'all'):
				$table = "security_event";
				//fulltext seach
				$condition = getfulltextsearchsql($conn,$table,$key);
			    $order = "order by eventid desc,occurrencetime desc";	
				break;
			case ($type == 'tainangov_security_Incident' and $keyword_type == 'all'):
				$table = "tainangov_security_Incident";
				//fulltext seach
				$condition = getfulltextsearchsql($conn,$table,$key);
			    $order = "order by IncidentID desc,occurrencetime desc";	
				break;
			case ($type == 'security_contact' and $keyword_type == 'all'):
				$table = "security_contact";
				//FullText Seach
				$condition = getFullTextSearchSQL($conn,$table,$key);
				// select security_contact from NCERT and Internal_Primary Unit from self-creation
				$table = "(SELECT * FROM security_contact UNION SELECT * FROM security_contact_extra)A";
				$order = "ORDER by OID asc,person_type asc";
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
				case "tainangov_security_Incident": 
					echo "<div class='ui relaxed divided list'>";
						echo "<div class='item'>";
							echo "<div class='content'>";
								echo "<a class='header'>";
									echo "發現日期&nbsp&nbsp";
									echo "結案狀態&nbsp&nbsp";
									echo "影響等級";
									echo "資安事件類型&nbsp&nbsp";
									echo "對外IP(URL)&nbsp&nbsp";
									echo "機關&nbsp&nbsp";
								echo "</a>";
							echo "</div>";
						echo "</div>";

						while($row = mysqli_fetch_assoc($result)) {
							echo "<div class='item'>";
							echo "<div class='content'>";
								echo "<a>";
                        		echo date_format(new DateTime($row['OccurrenceTime']),'Y-m-d')."&nbsp&nbsp";
								echo $row['Status']."&nbsp&nbsp";
								echo "<span style='background:#DDDDDD'>".$row['ImpactLevel']."</span>&nbsp&nbsp";
								echo $row['Classification']."&nbsp&nbsp";
								echo "<span style='background:#fde087'>".$row['PublicIP']."</span>&nbsp&nbsp";
								echo $row['OrganizationName'];
								echo "<i class='angle double down icon'></i>";
								echo "</a>";
								
								echo "<div class='description'>";
									echo "<ol>";
									echo "<li>編號:".$row['IncidentID']."</li>";
									echo "<li>結案狀態:".$row['Status']."</li>";
									echo "<li>事件編號:".$row['NccstID']."</li>";	
									echo "<li>行政院攻防演練:".$row['NccstPT']."</li>";
									echo "<li>攻防演練衝擊性:".$row['NccstPTImpact']."</li>";
									echo "<li>機關名稱:".$row['OrganizationName']."</li>";
									echo "<li>聯絡人:".$row['ContactPerson']."</li>";
									echo "<li>電話:".$row['Tel']."</li>";
									echo "<li>電子郵件:".$row['Email']."</li>";
									echo "<li>資安維護廠商:".$row['SponsorName']."</li>";
									echo "<li>對外IP或網址:".$row['PublicIP']."</li>";
									echo "<li>使用用途:".$row['DeviceUsage']."</li>";
									echo "<li>作業系統:".$row['OperatingSystem']."</li>";
									echo "<li>入侵網址:".$row['IntrusionURL']."</li>";
									echo "<li>影響等級:".$row['ImpactLevel']."</li>";
									echo "<li>事故分類:".$row['Classification']."</li>";
									echo "<li>事故說明:".$row['Explaination']."</li>";
									echo "<li>影響評估:".$row['Evaluation']."</li>";
									echo "<li>應變措施:".$row['Response']."</li>";
									echo "<li>解決辦法/結報內容:".$row['Solution']."</li>";
									echo "<li>發生時間:".$row['OccurrenceTime']."</li>";
									echo "<li>通報時間:".$row['InformTime']."</li>";	
									echo "<li>修復時間:".$row['RepairTime']."</li>";
									echo "<li>審核機關審核時間:".$row['TainanGovVerificationTime']."</li>";
									echo "<li>技服中心審核時間:".$row['NccstVerificationTime']."</li>";
									echo "<li>通報結報時間:".$row['FinishTime']."</li>";	
									echo "<li>通報執行時間(時:分):".$row['InformExecutionTime']."</li>";
									echo "<li>結案執行時間(時:分):".$row['FinishExecutionTime']."</li>";
									echo "<li>中華SOC複測結果:".$row['SOCConfirmation']."</li>";
									echo "<li>改善計畫提報日期:".$row['ImprovementPlanTime']."</li>";	
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
