<?php
	require("../login/function.php");
	if( (!empty($_GET['key']) && !empty($_GET['keyword']) && !empty($_GET['type']) ) || count(json_decode($_GET['jsonObj'],true)) !=0  ){
		//過濾特殊字元(')
		$key = $_GET['key'];
		$keyword = $_GET['keyword'];
		$type = $_GET['type'];
		$jsonObj = $_GET['jsonObj']; 
		if (!isset($_GET['page']))	$pages = 1; 
		else						$pages = $_GET['page']; 
		if (!isset($_GET['ap']))	$ap = 'html'; 
		else						$ap = $_GET['ap']; 
		
		$arr_jsonObj = json_decode($jsonObj,true);
		
		//connect database
        require("../mysql_connect.inc.php");
		 //特殊字元跳脫(NUL (ASCII 0), \n, \r, \, ', ", and Control-Z)
		$key = mysqli_real_escape_string($conn,$key);
		$keyword = mysqli_real_escape_string($conn,$keyword);
		$type = mysqli_real_escape_string($conn,$type);
		
		//table switch
		switch(true){
			case ($type == 'security_event'):
				$condition_table = "security_event";
				$table = "security_event";
			    $order = "ORDER by EventID DESC,OccurrenceTime DESC";	
				break;
			case ($type == 'tainangov_security_Incident'):
				$condition_table = "tainangov_security_Incident";
				$table = "tainangov_security_Incident";
			    $order = "ORDER by IncidentID DESC,OccurrenceTime DESC";	
				break;
			case ($type == 'security_contact'):
				$condition_table = "security_contact";
				$table = "(select * from security_contact union select * from security_contact_extra)a";
			    $order = "order by oid asc,person_type asc";	
				break;
			case ($type == 'gcb_client_list'):
				$condition_table = "gcb_client_list";
				$table = "(SELECT a.*,b.name as os_name,c.name as ie_name FROM gcb_client_list as a LEFT JOIN gcb_os as b ON a.OSEnvID = b.id LEFT JOIN gcb_ie as c ON a.IEEnvID = c.id)A";
				if($keyword == 'ExternalIP' or $keyword == 'InternalIP') $key = ip2long($key);
			    $order = "ORDER by ID ASC";	
				break;
			case ($type == 'wsus_client_list'):
				$condition_table = "wsus_computer_status";
				$table = "wsus_computer_status";
			    $order = "ORDER by TargetID ASC";	
				break;
			case ($type == 'antivirus_client_list'):
				$condition_table = "antivirus_client_list";
				$table = "antivirus_client_list";
			    $order = "ORDER by GUID ASC";	
				break;
			case ($type == 'drip_client_list'):
				$condition_table = "drip_client_list";
				$table = "drip_client_list";
			    $order = "ORDER by DetectorName ASC,IP ASC";	
				break;
		}

		//retrieve condition
		if( count($arr_jsonObj) !=0 ){
			$condition = "";
			foreach($arr_jsonObj as $val){
				$val['key']		= mysqli_real_escape_string($conn,$val['key']);
				$val['keyword'] = mysqli_real_escape_string($conn,$val['keyword']);
				if($val['keyword'] == "all"){
					$one_condition = "(".getFullTextSearchSQL($conn,$condition_table,$val['key']).") "; 
				}else{
					$one_condition = $val['keyword']." LIKE '%".$val['key']."%' ";
				}
				$condition = $condition." AND ".$one_condition;
			}
			$condition = substr($condition,4);
		}else{
			//特殊字元跳脫(NUL (ASCII 0), \n, \r, \, ', ", and Control-Z)
			$key			 = mysqli_real_escape_string($conn,$key);
			$keyword	 = mysqli_real_escape_string($conn,$keyword);
			if($keyword == "all"){
				//FullText Seach
				$condition = "(".getFullTextSearchSQL($conn,$condition_table,$key).") "; 
			}else{
				$condition = $keyword." LIKE '%".$key."%' ";
			}

		}
		//echo $condition."<br>";
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
			}else{
				header('Content-type: text/html; charset=utf-8');
				echo "該分類共搜尋到".$rowcount."筆資料！";
				//record number on each page & maxumun pages on pagination			
				$per = 10; 	
				$max_pages = 10;
				list($sql_subpage,$prev_page,$next_page,$lb,$ub,$Totalpages) = getPaginationSQL($sql,$per,$max_pages,$rowcount,$pages);
				$result = mysqli_query($conn,$sql_subpage);
				switch($type){
					case "security_event": 
					echo "<div class='ui relaxed divided list'>";
						echo "<div class='item'>";
							echo "<div class='content'>";
								echo "<a class='header'>";
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
										echo "<li>備註:".$row['Remarks']."</li>";
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
					case "gcb_client_list":
						echo "<div class='ui relaxed divided list'>";
							echo "<div class='item'>";
								echo "<div class='content'>";
									echo "<a class='header'>";
									echo "電腦名稱&nbsp&nbsp";
									echo "單位名稱&nbsp&nbsp";
									echo "使用者帳號&nbsp&nbsp";
									echo "內網IP&nbsp&nbsp";
									echo "作業系統&nbsp&nbsp";
									echo "<a>";
								echo "</div>";
							echo "</div>";

					while($row = mysqli_fetch_assoc($result)) {
						echo "<div class='item'>";
						echo "<div class='content'>";
							echo "<a>";
							if($row['IsOnline'] == "1")		echo "<i class='circle green icon'></i>";
							else							echo "<i class='circle outline icon'></i>";
							switch($row['GsStat']){
								case '0':
									$GsStat_str = "未套用";
									break;
								case '1':
									$GsStat_str = "已套用";
									break;
								case '-1':
									$GsStat_str = "套用失敗";
									break;
								case '2':
									$GsStat_str = "還原成功";
									break;
								case '-2':
									$GsStat_str = "未套用";
									break;
							}
							
							echo $row['Name']."&nbsp&nbsp";
							echo "<span style='background:#fde087'>".$row['OrgName']."</span>&nbsp&nbsp";
							echo $row['UserName']."&nbsp&nbsp";
							echo $row['Owner']."&nbsp&nbsp";
							echo "<span style='background:#DDDDDD'>".long2ip($row['InternalIP'])."</span>&nbsp&nbsp";
							echo $row['os_name']."&nbsp&nbsp";
							echo "<i class='angle double down icon'></i>";
							echo "</a>";
							echo "<div class='description'>";
								echo "<ol>";
								echo "<li><a href='ajax/gcb_detail.php?action=detail&id=".$row['ID']."' target='_blank'>序號:".$row['ID']."&nbsp<i class='external alternate icon'></i></a></li>";
								echo "<li>外部IP:".long2ip($row['ExternalIP'])."</li>";
								echo "<li>內部IP:".long2ip($row['InternalIP'])."</li>";
								echo "<li>電腦名稱:".$row['Name']."</li>";
								echo "<li>單位名稱:".$row['OrgName']."</li>";
								echo "<li>使用者帳號:".$row['UserName']."</li>";
								echo "<li>使用者名稱:".$row['Owner']."</li>";
								echo "<li>OS:".$row['os_name']."</li>";
								echo "<li>IE:".$row['ie_name']."</li>";
								echo "<li>是否上線:".$row['IsOnline']."</li>";
								echo "<li>Gcb總通過數[未包含例外]:".$row['GsAll_0']."</li>";
								echo "<li>Gcb總通過數[包含例外]:".$row['GsAll_1']."</li>";
								echo "<li>Gcb總通過數[總數]:".$row['GsAll_2']."</li>";
								echo "<li>Gcb例外數量:".$row['GsExcTot']."</li>";
								echo "<li><a href='ajax/gcb_detail.php?action=gscan&id=".$row['GsID']."' target='_blank'>Gcb掃描編號:".$row['GsID']."&nbsp<i class='external alternate icon'></i></a></li>";
								echo "<li>Gcb派送編號:".$row['GsSetDeployID']."</li>";
								echo "<li>Gcb狀態:".$GsStat_str."</li>";
								echo "<li>Gcb回報時間:".$row['GsUpdatedAt']."</li>";
								echo "</ol>";
							echo "</div>";
							echo "</div>";
						echo "</div>";
					}
					
					echo "</div>";
					break;
					case "wsus_client_list":
						echo "<div class='ui relaxed divided list'>";
					while($row = mysqli_fetch_assoc($result)) {
						echo "<div class='item'>";
						echo "<div class='content'>";
							echo "<a>";
							echo strtoupper(str_replace(".tainan.gov.tw","",$row['FullDomainName']))."&nbsp&nbsp";
							echo "<span style='background:#fde087'>".$row['IPAddress']."</span>&nbsp&nbsp";
							echo "<span style='background:#DDDDDD'>".$row['NotInstalled']."</span>&nbsp&nbsp";
							echo "<span style='background:#fbc5c5'>".$row['Failed']."</span>&nbsp&nbsp";
							echo $row['OSDescription'];
							echo "<i class='angle double down icon'></i>";
							echo "</a>";
							echo "<div class='description'>";
								echo "<ol>";
								echo "<li>序號:".$row['TargetID']."</li>";
								echo "<li>電腦名稱:".strtoupper(str_replace(".tainan.gov.tw","",$row['FullDomainName']))."</li>";
								echo "<li>內網IP:".$row['IPAddress']."</li>";
								echo "<li>未知更新數量:".$row['Unknown']."</li>";
								echo "<li>未安裝更新數量:".$row['NotInstalled']."</li>";
								$sql = "SELECT KBArticleID FROM wsus_computer_updatestatus_kbid WHERE TargetID = ".$row['TargetID']." AND UpdateState = 'NotInstalled' ORDER by ID asc";
								$result_NotInstalled = mysqli_query($conn,$sql);
								while($row_NotInstalled = mysqli_fetch_assoc($result_NotInstalled)) {
									echo "<strong>KB".$row_NotInstalled['KBArticleID']."</strong> ";	
								}	
								echo "<li>已下載更新數量:".$row['Downloaded']."</li>";
								echo "<li>已安裝更新數量:".$row['Installed']."</li>";
								echo "<li>安裝失敗更新數量:".$row['Failed']."</li>";
								$sql = "SELECT KBArticleID FROM wsus_computer_updatestatus_kbid WHERE TargetID = ".$row['TargetID']." AND UpdateState = 'Failed' ORDER by ID asc";
								$result_Failed = mysqli_query($conn,$sql);
								while($row_Failed = mysqli_fetch_assoc($result_Failed)) {
									echo "<strong>KB".$row_Failed['KBArticleID']."</strong> ";	
								}	
								echo "<li>已安裝待重開機更新數量:".$row['InstalledPendingReboot']."</li>";
								echo "<li>上次狀態回報日期:".dateConvert($row['LastReportedStatusTime'])."</li>";
								echo "<li>上次更新重開機日期:".dateConvert($row['LastReportedRebootTime'])."</li>";
								echo "<li>上次可用更新日期:".dateConvert($row['EffectiveLastDetectionTime'])."</li>";
								echo "<li>上次同步日期:".dateConvert($row['LastSyncTime'])."</li>";
								echo "<li>上次修改日期:".dateConvert($row['LastChangeTime'])."</li>";
								echo "<li>上次同步結果:".$row['LastSyncResult']."</li>";
								echo "<li>製造商:".$row['ComputerMake']."</li>";
								echo "<li>型號:".$row['ComputerModel']."</li>";
								echo "<li>作業系統:".$row['OSDescription']."</li>";
								echo "</ol>";
							echo "</div>";
							echo "</div>";
						echo "</div>";
					}
					echo "</div>";
					break;
					case "antivirus_client_list":
					echo "<div class='ui relaxed divided list'>";
					while($row = mysqli_fetch_assoc($result)) {
						echo "<div class='item'>";
						echo "<div class='content'>";
							echo "<a>";
							if($row['ConnectionState'] == "線上")	echo "<i class='circle green icon'></i>";
							else									echo "<i class='circle outline icon'></i>";
							echo $row['ClientName']."&nbsp&nbsp";
							echo "<span style='background:#fde087'>".$row['IP']."</span>&nbsp&nbsp";
							//echo "<span style='background:#DDDDDD'>".$row['ConnectionState']."</span>&nbsp&nbsp";
							echo "<span style='background:#fbc5c5'>".$row['OS']."</span>&nbsp&nbsp";
							echo $row['VirusNum']."&nbsp&nbsp";
							echo $row['SpywareNum']."&nbsp&nbsp";
							echo "<span style='background:#DDDDDD'>".$row['VirusPatternVersion']."</span>&nbsp&nbsp";
							echo $row['LogonUser']."&nbsp&nbsp";
							echo "<i class='angle double down icon'></i>";
							echo "</a>";
						echo "<div class='description'>";
							echo "<ol>";
							echo "<li>設備名稱:".$row['ClientName']."</li>";
							echo "<li>內網IP:".$row['IP']."</li>";
							echo "<li>網域階層:".$row['DomainLevel']."</li>";
							echo "<li>連線狀態:".$row['ConnectionState']."</li>";
							echo "<li>GUID:".$row['GUID']."</li>";
							echo "<li>掃描方式:".$row['ScanMethod']."</li>";
							echo "<li>DLP狀態:".$row['DLPState']."</li>";
							echo "<li>病毒數量:".$row['VirusNum']."</li>";
							echo "<li>間諜程式數量:".$row['SpywareNum']."</li>";
							echo "<li>作業系統:".$row['OS']."</li>";
							echo "<li>位元版本:".$row['BitVersion']."</li>";
							echo "<li>MAC位址:".$row['MAC']."</li>";
							echo "<li>設備版本:".$row['ClientVersion']."</li>";
							echo "<li>病毒碼版本:".$row['VirusPatternVersion']."</li>";
							echo "<li>登入使用者:".$row['LogonUser']."</li>";
							echo "</ol>";
						echo "</div>";
						echo "</div>";
						echo "</div>";
					}		
					echo "</div>";
					break;
					case "drip_client_list":
					echo "<div class='ui relaxed divided list'>";
					while($row = mysqli_fetch_assoc($result)) {
						echo "<div class='item'>";
						echo "<div class='content'>";
							echo "<a>";
							if($row['ad']==1) echo "<i class='circle yellow icon'></i>";
							else echo "<i class='circle outline icon'></i>";
							if($row['gcb']==1) echo "<i class='circle green icon'></i>";
							else echo "<i class='circle outline icon'></i>";
							if($row['wsus']==1) echo "<i class='circle red icon'></i>";
							else echo "<i class='circle outline icon'></i>";
							if($row['antivirus']==1) echo "<i class='circle blue icon'></i>";
							else echo "<i class='circle outline icon'></i>";
							echo $row['DetectorName']."&nbsp&nbsp";
							echo "<span style='background:#fde087'>".$row['IP']."</span>&nbsp&nbsp";
							//echo "<span style='background:#DDDDDD'>".$row['MAC']."</span>&nbsp&nbsp";
							echo $row['ClientName']."&nbsp&nbsp";
							echo "<span style='background:#fbc5c5'>".$row['OrgName']."</span>&nbsp&nbsp";
							echo $row['Owner']."&nbsp&nbsp";
							echo $row['UserName']."&nbsp&nbsp";
							echo "<i class='angle double down icon'></i>";
							echo "</a>";
						echo "<div class='description'>";
							echo "<ol>";
							echo "<li>內網IP:".$row['IP']."</li>";
							echo "<li>MAC位址:".$row['MAC']."</li>";
							echo "<li>設備名稱:".$row['ClientName']."</li>";
							echo "<li>群組名稱:".$row['GroupName']."</li>";
							echo "<li>網卡製造商:".$row['NICProductor']."</li>";
							echo "<li>偵測器名稱:".$row['DetectorName']."</li>";
							echo "<li>偵測器IP:".$row['DetectorIP']."</li>";
							echo "<li>偵測器群組:".$row['DetectorGroup']."</li>";
							echo "<li>交換器名稱:".$row['SwitchName']."</li>";
							echo "<li>連接埠名稱:".$row['PortName']."</li>";
							echo "<li>最後上線時間:".$row['LastOnlineTime']."</li>";
							echo "<li>最後下線時間:".$row['LastOfflineTime']."</li>";
							echo "<li>IP封鎖原因:".$row['IP_BlockReason']."</li>";
							echo "<li>MAC封鎖原因:".$row['MAC_BlockReason']."</li>";
							echo "<li>備註ByIP:".$row['MemoByIP']."</li>";
							echo "<li>備註ByMac:".$row['MemoByMAC']."</li>";
							echo "<li>ad安裝:".$row['ad']."</li>";
							echo "<li>gcb安裝:".$row['gcb']."</li>";
							echo "<li>wsus安裝:".$row['wsus']."</li>";
							echo "<li>antivirus安裝:".$row['antivirus']."</li>";
							echo "<li>OrgName:".$row['OrgName']."</li>";
							echo "<li>Owner:".$row['Owner']."</li>";
							echo "<li>UserName:".$row['UserName']."</li>";
							echo "</ol>";
						echo "</div>";
						echo "</div>";
						echo "</div>";
					}		
					echo "</div>";
					break;
				}
						
				//The href-link of bottom pages
				echo "<div class='ui pagination menu'>";	
				echo "<a class='item test' href='javascript: void(0)' page='1' key='".$key."' keyword ='".$keyword."' type='".$type."' jsonObj='".$jsonObj."'  >首頁</a>";
				echo "<a class='item test' href='javascript: void(0)' page='".$prev_page."' key='".$key."' keyword ='".$keyword."' type='".$type."' jsonObj='".$jsonObj."'  > ← </a>";
				for ($j = $lb; $j <= $ub ;$j++){
					if($j == $pages){
						echo"<a class='active item bold' href='javascript: void(0)' page='".$j."' key='".$key."' keyword ='".$keyword."' type='".$type."' jsonObj='".$jsonObj."' >".$j."</a>";
					}else{
						echo"<a class='item test' href='javascript: void(0)' page='".$j."' key='".$key."' keyword ='".$keyword."' type='".$type."' jsonObj='".$jsonObj."' >".$j."</a>";
					}
				}
				echo"<a class='item test' href='javascript: void(0)' page='".$next_page."' key='".$key."' keyword ='".$keyword."' type='".$type."' jsonObj='".$jsonObj."' > → </a>";		
				//last page
				echo"<a class='item test' href='javascript: void(0)' page='".$Totalpages."' key='".$key."' keyword ='".$keyword."' type='".$type."' jsonObj='".$jsonObj."' >末頁</a>";
				echo "</div>";

				//The mobile href-link of bottom pages
				echo "<div class='ui pagination menu mobile'>";	
				echo "<a class='item test' href='javascript: void(0)' page='".$prev_page."' key='".$key."' keyword ='".$keyword."' type='".$type."' jsonObj='".$jsonObj."' > ← </a>";
				echo"<a class='active item bold' href='javascript: void(0)' page='".$pages."' key='".$key."' keyword ='".$keyword."' type='".$type."' jsonObj='".$jsonObj."' >(".$pages."/".$Totalpages.")</a>";
				echo"<a class='item test' href='javascript: void(0)' page='".$next_page."' key='".$key."' keyword ='".$keyword."' type='".$type."' jsonObj='".$jsonObj."' > → </a>";		
				echo "</div>";
			}
		}elseif($ap='csv'){
			$arrs=array();
			switch($type){
				case 'security_event': 
					break;
				case 'tainangov_security_Incident': 
					break;
				case 'security_contact': 
					break;
				case 'gcb_client_list': 
					break;
				case 'wsus_client_list': 
					break;
				case 'antivirus_client_list': 
					break;
				case 'drip_client_list': 
					while($row = mysqli_fetch_row($result)) {
						foreach($row as $key => $val){
							$arr[$key] = $val;
						}
						array_push($arrs,$arr);	
					}
					//var_dump($arrs);
					array_to_csv_download($arrs,"export.csv",";"); 	
					break;
			}
		}
		$conn->close();
	}else{
		phpAlert("沒有輸入");
	}
	
?> 
