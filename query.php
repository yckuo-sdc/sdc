<!--query.php-->
<?php 
	if(isset($_GET['subpage'])) $subpage  = $_GET['subpage'];
	else						$subpage =1;
	$subpage  = $_GET['subpage'];
	switch($subpage){
		case 1:	load_query_event(); break;
		case 2:	load_query_ncert(); break;
		case 3:	load_query_contact(); break;
		case 4:	load_query_client(); break;
		case 5:	load_query_retrieve(); break;
	}
?>
<?php
function load_query_event(){
?>
<div id="page" class="container">
<div id="content">
		<div class="sub-content show">
			<div class="post security_event">
				<div class="post_title">府內資安事件查詢</div>
				<div class="post_cell">
				<form class="ui form" action="javascript:void(0)">

 				<div class="fields">
			    	<div class="field">
					    <label>種類</label>
						<select name="keyword_type" id="keyword_type" class="ui fluid dropdown" required>
                   		 <!--<option value="" class="keyword_paper active" selected>關鍵字種類</option>-->
						<option value="IP" class="keyword_paper active" selected>設備IP</option>
						<option value="Status" class="keyword_paper active">結案狀態</option>
						<option value="EventTypeName" class="keyword_paper active">資安類型</option>
						<option value="DeviceTypeName" class="keyword_paper active">設備類型</option>
						<option value="DeviceOwnerName" class="keyword_paper active">所有人姓名</option>
						<option value="OccurrenceTime" class="keyword_paper active">發現日期</option>
						<option value="all" class="keyword_paper active">全部</option>
						</select>
					</div>
				 	<div class="field">
					    <label>關鍵字</label>
						<div class="ui input">
							<input type='text' name='key' id='key' placeholder="請輸入關鍵字">
						</div>
					</div>
					<div class="field">
						<button id="search_btn" name="search_btn" class="ui button">搜尋</button>
					</div>
					 <div class="field">
						<button id="show_all_btn" class="ui button" onclick="window.location.href='index.php?mainpage=query&subpage=1'">顯示全部</button>
					</div>
				</div>
				</form>
				<div class="record_content">
                <?php //select data form database
                    require("mysql_connect.inc.php");
                    //------------pagination----------//
					$pages = isset($_GET['page'])?$_GET['page']:1;	
                    
                    //select row_number,and other field value
                    $sql = "SELECT * FROM security_event ORDER by EventID desc,OccurrenceTime desc";
                    $result = mysqli_query($conn,$sql);
                    $rowcount = mysqli_num_rows($result);

					//record number on each page			
					$per = 10; 	
					// maximum pages on pagination	
                    $max_pages = 10;

					list($sql_subpage,$prev_page,$next_page,$lower_bound,$upper_bound,$Totalpages) = getPaginationSQL($sql,$per,$max_pages,$rowcount,$pages);

					$result = mysqli_query($conn,$sql_subpage);
                                        
                    if($rowcount==0){
                        echo "查無此筆紀錄";
                    }else{
                        echo "共有".$rowcount."筆資料！";


						echo "<div class='ui relaxed divided list'>";
						/*	echo "<div class='item'>";
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
									echo "</a>";
								echo "</div>";
							echo "</div>";
						*/
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
					/* Create Pagination Element*/ 
					echo pagination($prev_page,$next_page,$lower_bound,$upper_bound,$Totalpages,"query",1,0,$pages,"");
					}
                    $conn->close();
                        
                ?>
				</div>
			</div>
			</div>
		</div>
		<div style="clear: both;">&nbsp;</div>
	</div>
<?php } 
function load_query_ncert(){
?>	
<div id="page" class="container">
<div id="content">
		<div class="sub-content show">
			<div class="post tainangov_security_Incident">
				<div class="post_title">資安通報查詢</div>
				<div class="post_cell">
					<form class="ui form" action="javascript:void(0)">

					<div class="fields">
						<div class="field">
							<label>種類</label>
							<select name="keyword_type" id="keyword_type" class="ui fluid dropdown" required>
							<option value="PublicIP" class="keyword_paper active" selected>對外IP(網址)</option>
							<option value="Status" class="keyword_paper active">結案狀態</option>
							<option value="Classification" class="keyword_paper active">事故類型</option>
							<option value="OrganizationName" class="keyword_paper active">機關名稱</option>
							<option value="NccstPT" class="keyword_paper active">攻防演練(是/否)</option>
							<option value="OccurrenceTime" class="keyword_paper active">發現日期</option>
							<option value="all" class="keyword_paper active">全部</option>
							</select>
						</div>
						<div class="field">
							<label>關鍵字</label>
							<div class="ui input">
								<input type='text' name='key' id='key' placeholder="請輸入關鍵字">
							</div>
						</div>
						<div class="field">
							<button id="search_btn" name="search_btn" class="ui button">搜尋</button>
						</div>
						 <div class="field">
							<button id="show_all_btn" class="ui button" onclick="window.location.href='index.php?mainpage=query&subpage=2'">顯示全部</button>
						</div>
					</div>
					</form>
					<div class="record_content">
					<?php //select data form database
						require("mysql_connect.inc.php");
						//------------pagination----------//
						$pages = isset($_GET['page'])?$_GET['page']:1;	
						
						//select row_number,and other field value
						//$sql = "SELECT * FROM security_contact ORDER by OID asc,person_type asc";
						// select security_contact from NCERT and Internal_Primary Unit from self-creation
						$sql = "SELECT * FROM tainangov_security_Incident ORDER by IncidentID desc";
							
						$result = mysqli_query($conn,$sql);
						$rowcount = mysqli_num_rows($result);
									
						$per = 10; 		
						$max_pages = 10;
						$Totalpages = ceil($rowcount / $per); 
						$lower_bound = ($pages <= $max_pages) ? 1 : $pages - $max_pages + 1;
						$upper_bound = ($pages <= $max_pages) ? min($max_pages,$Totalpages) : $pages;					
						$start = ($pages -1)*$per; //計算資料庫取資料範圍的開始值。
						if($pages == 1)					$offset = ($rowcount < $per) ? $rowcount : $per;
						elseif($pages == $Totalpages)	$offset = $rowcount - $start;
						else							$offset = $per;
									
						$prev_page = ($pages > 1) ? $pages -1 : 1;
						$next_page = ($pages < $Totalpages) ? $pages +1 : $Totalpages;	
						$sql_subpage = $sql." limit ".$start.",".$offset;
									
						$result = mysqli_query($conn,$sql_subpage);
											
						if($rowcount==0){
							echo "查無此筆紀錄";
						}else{
							echo "共有".$rowcount."筆資料！";


							echo "<div class='ui relaxed divided list'>";
								/*echo "<div class='item'>";
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
								*/
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
						/* Create Pagination Element*/ 
						echo pagination($prev_page,$next_page,$lower_bound,$upper_bound,$Totalpages,"query",2,0,$pages,"");
						}
						$conn->close();
					?>
					</div> <!--End of record_content-->	
				</div><!--End of post_cell-->
			</div><!--End of post-->
		</div><!--End of sub-content-->
		<div style="clear: both;">&nbsp;</div>
	</div>
<?php } 
function load_query_contact(){
?>	
<div id="page" class="container">
<div id="content">
		<div class="sub-content show">
			<div class="post security_contact">
				<div class="post_title">資安聯絡人</div>
				<div class="post_cell">
					<form class="ui form" action="javascript:void(0)">

					<div class="fields">
						<div class="field">
							<label>種類</label>
							<select name="keyword_type" id="keyword_type" class="ui fluid dropdown" required>
							 <!--<option value="" class="keyword_paper active" selected>關鍵字種類</option>-->
							<option value="organization" class="keyword_paper active" selected>機關名稱</option>
							<option value="OID" class="keyword_paper active">機關OID</option>
							<option value="person_name" class="keyword_paper active">聯絡人姓名</option>
							<option value="person_type" class="keyword_paper active">聯絡人類別</option>
							<option value="all" class="keyword_paper active">全部</option>
							</select>
						</div>
						<div class="field">
							<label>關鍵字</label>
							<div class="ui input">
								<input type='text' name='key' id='key' placeholder="請輸入關鍵字">
							</div>
						</div>
						<div class="field">
							<button id="search_btn" name="search_btn" class="ui button">搜尋</button>
						</div>
						 <div class="field">
							<button id="show_all_btn" class="ui button" onclick="window.location.href='index.php?mainpage=query&subpage=3'">顯示全部</button>
						</div>
					</div>
					</form>
					<div class="record_content">
					<?php //select data form database
						require("mysql_connect.inc.php");
						$pages = isset($_GET['page'])?$_GET['page']:1;	
						
						//select row_number,and other field value
						//$sql = "SELECT * FROM security_contact ORDER by OID asc,person_type asc";
						// select security_contact from NCERT and Internal_Primary Unit from self-creation
						$sql = "SELECT * FROM security_contact UNION SELECT * FROM security_contact_extra ORDER by OID asc,person_type asc";
							
						$result = mysqli_query($conn,$sql);
						$rowcount = mysqli_num_rows($result);
									
						$per = 10; 		
						$max_pages = 10;
						$Totalpages = ceil($rowcount / $per); 
						$lower_bound = ($pages <= $max_pages) ? 1 : $pages - $max_pages + 1;
						$upper_bound = ($pages <= $max_pages) ? min($max_pages,$Totalpages) : $pages;					
						$start = ($pages -1)*$per; //計算資料庫取資料範圍的開始值。
						if($pages == 1)					$offset = ($rowcount < $per) ? $rowcount : $per;
						elseif($pages == $Totalpages)	$offset = $rowcount - $start;
						else							$offset = $per;
									
						$prev_page = ($pages > 1) ? $pages -1 : 1;
						$next_page = ($pages < $Totalpages) ? $pages +1 : $Totalpages;	
						$sql_subpage = $sql." limit ".$start.",".$offset;
									
						$result = mysqli_query($conn,$sql_subpage);
											
						if($rowcount==0){
							echo "查無此筆紀錄";
						}else{
							echo "共有".$rowcount."筆資料！";


							echo "<div class='ui relaxed divided list'>";
								/*echo "<div class='item'>";
									echo "<div class='content'>";
										echo "<a class='header'>";
										//echo "序號&nbsp";
										echo "機關名稱&nbsp&nbsp";
										echo "姓名&nbsp&nbsp";
										echo "聯絡人類別&nbsp&nbsp";
										echo "信箱&nbsp&nbsp";
										echo "電話&nbsp&nbsp";
										echo "<a>";
									echo "</div>";
								echo "</div>";
								*/
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
									echo "<li>單位名稱:".$row['unit']."</li>";
									echo "<li>姓名:".$row['person_name']."</li>";
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
												
						/* Create Pagination Element*/ 
						echo pagination($prev_page,$next_page,$lower_bound,$upper_bound,$Totalpages,"query",3,0,$pages,"");
						}
						$conn->close();
					?>
					</div> <!--End of record_content-->	
				</div><!--End of post_cell-->
			</div><!--End of post-->
		</div><!--End of sub-content-->
		<div style="clear: both;">&nbsp;</div>
	</div>
<?php } 
function load_query_client(){
?>	
<div id="page" class="container">
<div id="content">
		<div class="sub-content show">
			<div class="post is_client_list">
				<div class="post_title">用戶端資安清單</div>
				<div class="post_cell">
					<div class="ui top attached tabular menu">
						<a class="active item">DrIP</a>
						<a class="item">GCB</a>
						<a class="item">WSUS</a>
						<a class="item">AntiVirus</a>
					</div>
					<div class="ui bottom attached segment">
					<div class="tab-content drip_client_list show">
						<form class="ui form" action="javascript:void(0)">
						<div class="fields">
							<div class="field">
								<label>種類</label>
								<select name="keyword_type" id="keyword_type" class="ui fluid dropdown" required>
								<option value="ClientName" class="keyword_paper active" selected>電腦名稱</option>
								<option value="IP" class="keyword_paper active">內部IP</option>
								<option value="UserName" class="keyword_paper active">使用者帳號</option>
								<option value="OrgName" class="keyword_paper active">單位名稱</option>
								<option value="ad" class="keyword_paper active">ad</option>
								<option value="gcb" class="keyword_paper active">gcb</option>
								<option value="wsus" class="keyword_paper active">wsus</option>
								<option value="antivirus" class="keyword_paper active">antivirus</option>
								<option value="all" class="keyword_paper active">全部</option>
								</select>
							</div>
							<div class="field">
								<label>關鍵字</label>
								<div class="ui input">
									<input type='text' name='key' id='key' placeholder="請輸入關鍵字">
								</div>
							</div>
							<div class="field">
								<button id="search_btn" name="search_btn" class="ui button">搜尋</button>
							</div>
							 <div class="field">
								<button id="show_all_btn" class="ui button" onclick="window.location.href='index.php?mainpage=query&subpage=4&tab=1'">顯示全部</button>
							</div>
							 <div class="field">
								<button id="export2csv_btn" class="ui button">匯出</button>
							</div>
						</div>
						</form>
							<i class='circle yellow icon'></i>ad
							<i class='circle green icon'></i>gcb
							<i class='circle red icon'></i>wsus
							<i class='circle blue icon'></i>antivirus
							<p></p>
						<div class="record_content">
						<?php //select data form database
							require("mysql_connect.inc.php");
							//------------pagination----------//
							$pages = isset($_GET['page'])?$_GET['page']:1;	
							
							//select row_number,and other field value
							$sql = "SELECT * FROM drip_client_list ORDER by DetectorName ASC,IP ASC";
							$result = mysqli_query($conn,$sql);
							$rowcount = mysqli_num_rows($result);
										
							$per = 10; 		
							$max_pages = 10;
							$Totalpages = ceil($rowcount / $per); 
							$lower_bound = ($pages <= $max_pages) ? 1 : $pages - $max_pages + 1;
							$upper_bound = ($pages <= $max_pages) ? min($max_pages,$Totalpages) : $pages;					
							$start = ($pages -1)*$per; //計算資料庫取資料範圍的開始值。
							if($pages == 1)					$offset = ($rowcount < $per) ? $rowcount : $per;
							elseif($pages == $Totalpages)	$offset = $rowcount - $start;
							else							$offset = $per;
										
							$prev_page = ($pages > 1) ? $pages -1 : 1;
							$next_page = ($pages < $Totalpages) ? $pages +1 : $Totalpages;	
							$sql_subpage = $sql." limit ".$start.",".$offset;
										
							$result = mysqli_query($conn,$sql_subpage);
												
							if($rowcount==0){
								echo "查無此筆紀錄";
							}else{
								echo "共有".$rowcount."筆資料！";
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
										if(issetBySession("Level") && $_SESSION['Level'] == 2){
											echo "<button data-ip='".$row['IP']."' id='block-btn' class='ui button'>Block IP</button>";
											echo "<button data-ip='".$row['IP']."' id='unblock-btn' class='ui button'>UnBlock IP</button>";
											echo "<div class='ui centered inline loader'></div>";
											echo "<div class='block_IP_response'></div>";
										}
									echo "</div>";
									echo "</div>";
									echo "</div>";
								}		
								echo "</div>";
								/* Create Pagination Element*/ 
								echo pagination($prev_page,$next_page,$lower_bound,$upper_bound,$Totalpages,"query",4,1,$pages,"");
							}
							$conn->close();
						?>
						</div> <!--End of record_content-->	
					</div> <!--End of tabular_content-->	
						<div class="tab-content gcb_client_list show">
						<form class="ui form" action="javascript:void(0)">

						<div class="fields">
							<div class="field">
								<label>種類</label>
								<select name="keyword_type" id="keyword_type" class="ui fluid dropdown" required>
								<option value="Name" class="keyword_paper active" selected>電腦名稱</option>
								<option value="InternalIP" class="keyword_paper active">內部IP</option>
								<option value="UserName" class="keyword_paper active">使用者帳號</option>
								<option value="OrgName" class="keyword_paper active">單位名稱</option>
								<option value="all" class="keyword_paper active">全部</option>
								</select>
							</div>
							<div class="field">
								<label>關鍵字</label>
								<div class="ui input">
									<input type='text' name='key' id='key' placeholder="請輸入關鍵字">
								</div>
							</div>
							<div class="field">
								<button id="search_btn" name="search_btn" class="ui button">搜尋</button>
							</div>
							 <div class="field">
								<button id="show_all_btn" class="ui button" onclick="window.location.href='index.php?mainpage=query&subpage=4&tab=2'">顯示全部</button>
							</div>
						</div>
						</form>
						<div class="record_content">
							

						<?php //select data form database
					

							require("mysql_connect.inc.php");
							//------------pagination----------//
							$pages = isset($_GET['page'])?$_GET['page']:1;	
							
							//select row_number,and other field value
							//$sql = "SELECT * FROM security_contact ORDER by OID asc,person_type asc";
							// select security_contact from NCERT and Internal_Primary Unit from self-creation
							//$sql = "SELECT a.*,b.name as os_name,c.name as ie_name FROM gcb_client_list as a,gcb_os as b,gcb_ie as c WHERE a.OSEnvID = b.id AND a.IEEnvID = c.id ORDER by a.ID asc,a.InternalIP asc";
							$sql = "SELECT a.*,b.name as os_name,c.name as ie_name FROM gcb_client_list as a LEFT JOIN gcb_os as b ON a.OSEnvID = b.id LEFT JOIN gcb_ie as c ON a.IEEnvID = c.id ORDER by a.ID asc,a.InternalIP asc";
							$result = mysqli_query($conn,$sql);
							$rowcount = mysqli_num_rows($result);
										
							$per = 10; 		
							$max_pages = 10;
							$Totalpages = ceil($rowcount / $per); 
							$lower_bound = ($pages <= $max_pages) ? 1 : $pages - $max_pages + 1;
							$upper_bound = ($pages <= $max_pages) ? min($max_pages,$Totalpages) : $pages;					
							$start = ($pages -1)*$per; //計算資料庫取資料範圍的開始值。
							if($pages == 1)					$offset = ($rowcount < $per) ? $rowcount : $per;
							elseif($pages == $Totalpages)	$offset = $rowcount - $start;
							else							$offset = $per;
										
							$prev_page = ($pages > 1) ? $pages -1 : 1;
							$next_page = ($pages < $Totalpages) ? $pages +1 : $Totalpages;	
							$sql_subpage = $sql." limit ".$start.",".$offset;
										
							$result = mysqli_query($conn,$sql_subpage);
												
							if($rowcount==0){
								echo "查無此筆紀錄";
							}else{
								echo "共有".$rowcount."筆資料！";


							echo "<div class='ui relaxed divided list'>";
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
										default:
											$GsStat_str = "None";
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
										echo "<li><a href='ajax/gcb_detail.php?action=detail&id=".$row['ID']."' target='_blank'>序號:".$row['ID']."(用戶端資訊)&nbsp<i class='external alternate icon'></i></a></li>";
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
										echo "<li><a href='ajax/gcb_detail.php?action=gscan&id=".$row['GsID']."' target='_blank'>Gcb掃描編號:".$row['GsID']."(掃描結果資訊)&nbsp<i class='external alternate icon'></i></a></li>";
										echo "<li>Gcb派送編號:".$row['GsSetDeployID']."</li>";
										echo "<li>Gcb狀態:".$GsStat_str."</li>";
										echo "<li>Gcb回報時間:".$row['GsUpdatedAt']."</li>";
										echo "</ol>";
									echo "</div>";
									echo "</div>";
								echo "</div>";
							}
							
							echo "</div>";
						    /* Create Pagination Element*/ 
							echo pagination($prev_page,$next_page,$lower_bound,$upper_bound,$Totalpages,"query",4,2,$pages,"");
							}
							$conn->close();
						?>
						</div> <!--End of record_content-->	
					</div> <!--End of tabular_content-->	
					<div class="tab-content wsus_client_list">
						<form class="ui form" action="javascript:void(0)">

						<div class="fields">
							<div class="field">
								<label>種類</label>
								<select name="keyword_type" id="keyword_type" class="ui fluid dropdown" required>
								<option value="FullDomainName" class="keyword_paper active" selected>電腦名稱</option>
								<option value="IPAddress" class="keyword_paper active">內部IP</option>
								<option value="all" class="keyword_paper active">全部</option>
								</select>
							</div>
							<div class="field">
								<label>關鍵字</label>
								<div class="ui input">
									<input type='text' name='key' id='key' placeholder="請輸入關鍵字">
								</div>
							</div>
							<div class="field">
								<button id="search_btn" name="search_btn" class="ui button">搜尋</button>
							</div>
							 <div class="field">
								<button id="show_all_btn" class="ui button" onclick="window.location.href='index.php?mainpage=query&subpage=4&tab=3'">顯示全部</button>
							</div>
						</div>
						</form>
						<div class="record_content">
						<?php //select data form database
							require("mysql_connect.inc.php");
							//------------pagination----------//
							$pages = isset($_GET['page'])?$_GET['page']:1;	
							$sort = isset($_GET['sort'])?$_GET['sort']:'TargetID';	
							
							//select row_number,and other field value
							$sql = "SELECT * FROM wsus_computer_status ORDER by $sort asc";
							$result = mysqli_query($conn,$sql);
							$rowcount = mysqli_num_rows($result);
										
							$per = 10; 		
							$max_pages = 10;
							$Totalpages = ceil($rowcount / $per); 
							$lower_bound = ($pages <= $max_pages) ? 1 : $pages - $max_pages + 1;
							$upper_bound = ($pages <= $max_pages) ? min($max_pages,$Totalpages) : $pages;					
							$start = ($pages -1)*$per; //計算資料庫取資料範圍的開始值。
							if($pages == 1)					$offset = ($rowcount < $per) ? $rowcount : $per;
							elseif($pages == $Totalpages)	$offset = $rowcount - $start;
							else							$offset = $per;
										
							$prev_page = ($pages > 1) ? $pages -1 : 1;
							$next_page = ($pages < $Totalpages) ? $pages +1 : $Totalpages;	
							$sql_subpage = $sql." limit ".$start.",".$offset;
										
							$result = mysqli_query($conn,$sql_subpage);
												
							if($rowcount==0){
								echo "查無此筆紀錄";
							}else{
								echo "共有".$rowcount."筆資料！";


								echo "<div class='ui relaxed divided list'>";
									echo "<div class='item'>";
										echo "<div class='content'>";
											echo "<div class='header'>";
											echo "變更排序：";
											echo "<a href='?mainpage=query&subpage=4&tab=2&sort=FullDomainName'>電腦名稱</a>&nbsp&nbsp";
											echo "<a href='?mainpage=query&subpage=4&tab=2&sort=IPAddress'>內網IP</a>&nbsp&nbsp";
											echo "<a href='?mainpage=query&subpage=4&tab=2&sort=NotInstalled'>未安裝更新</a>&nbsp&nbsp";
											echo "<a href='?mainpage=query&subpage=4&tab=2&sort=Failed'>安裝失敗更新</a>&nbsp&nbsp";
											echo "<a href='?mainpage=query&subpage=4&tab=2&sort=LastReportedStatusTime'>上次回報日期</a>&nbsp&nbsp";
											echo "</div>";
										echo "</div>";
									echo "</div>";

							while($row = mysqli_fetch_assoc($result)) {
								echo "<div class='item'>";
								echo "<div class='content'>";
									echo "<a>";
									echo strtoupper(str_replace(".tainan.gov.tw","",$row['FullDomainName']))."&nbsp&nbsp";
									echo "<span style='background:#fde087'>".$row['IPAddress']."</span>&nbsp&nbsp";
									echo "<span style='background:#DDDDDD'>".$row['NotInstalled']."</span>&nbsp&nbsp";
									echo "<span style='background:#fbc5c5'>".$row['Failed']."</span>&nbsp&nbsp";
									echo dateConvert($row['LastReportedStatusTime']);
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
										echo "</ol>";
									echo "</div>";
									echo "</div>";
								echo "</div>";
							}
							
							echo "</div>";
						    /* Create Pagination Element*/ 
							echo pagination($prev_page,$next_page,$lower_bound,$upper_bound,$Totalpages,"query",4,3,$pages,$sort);
							}
							$conn->close();
						?>
						</div> <!--End of record_content-->	
					</div> <!--End of tabular_content-->	
					<div class="tab-content antivirus_client_list show">
						<form class="ui form" action="javascript:void(0)">

						<div class="fields">
							<div class="field">
								<label>種類</label>
								<select name="keyword_type" id="keyword_type" class="ui fluid dropdown" required>
								<option value="ClientName" class="keyword_paper active" selected>電腦名稱</option>
								<option value="IP" class="keyword_paper active">內部IP</option>
								<option value="all" class="keyword_paper active">全部</option>
								</select>
							</div>
							<div class="field">
								<label>關鍵字</label>
								<div class="ui input">
									<input type='text' name='key' id='key' placeholder="請輸入關鍵字">
								</div>
							</div>
							<div class="field">
								<button id="search_btn" name="search_btn" class="ui button">搜尋</button>
							</div>
							 <div class="field">
								<button id="show_all_btn" class="ui button" onclick="window.location.href='index.php?mainpage=query&subpage=4&tab=4'">顯示全部</button>
							</div>
						</div>
						</form>
						<div class="record_content">
						<?php //select data form database
							require("mysql_connect.inc.php");
							//------------pagination----------//
							$pages = isset($_GET['page'])?$_GET['page']:1;	
							
							//select row_number,and other field value
							$sql = "SELECT * FROM antivirus_client_list ORDER by DomainLevel asc,ClientName asc";
							$result = mysqli_query($conn,$sql);
							$rowcount = mysqli_num_rows($result);
										
							$per = 10; 		
							$max_pages = 10;
							$Totalpages = ceil($rowcount / $per); 
							$lower_bound = ($pages <= $max_pages) ? 1 : $pages - $max_pages + 1;
							$upper_bound = ($pages <= $max_pages) ? min($max_pages,$Totalpages) : $pages;					
							$start = ($pages -1)*$per; //計算資料庫取資料範圍的開始值。
							if($pages == 1)					$offset = ($rowcount < $per) ? $rowcount : $per;
							elseif($pages == $Totalpages)	$offset = $rowcount - $start;
							else							$offset = $per;
										
							$prev_page = ($pages > 1) ? $pages -1 : 1;
							$next_page = ($pages < $Totalpages) ? $pages +1 : $Totalpages;	
							$sql_subpage = $sql." limit ".$start.",".$offset;
										
							$result = mysqli_query($conn,$sql_subpage);
												
							if($rowcount==0){
								echo "查無此筆紀錄";
							}else{
								echo "共有".$rowcount."筆資料！";
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
								/* Create Pagination Element*/ 
								echo pagination($prev_page,$next_page,$lower_bound,$upper_bound,$Totalpages,"query",4,4,$pages,"");
							}
							$conn->close();
						?>
						</div> <!--End of record_content-->	
					</div> <!--End of tabular_content-->	
					</div> <!--End of attached_menu-->	
				</div><!--End of post_cell-->
			</div><!--End of post-->
		</div><!--End of sub-content-->
		<div style="clear: both;">&nbsp;</div>
	</div>
<?php } 
function load_query_retrieve(){
?>	
<div id="page" class="container">
<div id="content">
		<div class="sub-content show">
			<div class="post">
				<div class="post_title">Retrieve from Google Sheets and GCB</div>
				<div class="post_cell">
					<button id="gs_event_btn" class="ui button self-btn">Retrieve Event GS</button>
					<button id="gs_ncert_incident_btn" class="ui button self-btn">Retrieve Ncert-Incident_GS</button>
					<button id="gcb_api_btn" class="ui button self-btn">Retrieve GCB</button>
					<div class="retrieve_info"></div>
				</div>
			</div>
			<?php 
				// admin is given permission to edit this block	
				if(issetBySession("Level") && $_SESSION['Level'] == 2){
					//echo $_SESSION['Level'];
			?>
			<div class="post">
				<div class="post_title">Retrieve from Ncert</div>
				<div class="post_cell">
					1.從Ncert下載資安人員列表，另存成csv檔且修改編碼為UTF-8。
					<br>2.上傳此csv檔可更新「資安聯絡人」資料，並顯示已更新數量。
					<p><p>
					<form id="upload_Form" action="ajax/upload_contact.php" method="post">
					<div class="ui action input">
						<input type="text" placeholder="File" readonly>
						<input type="file" name="fileToUpload" id="fileToUpload" style="display: none;">
						<div class="ui icon button"><i class="attach icon"></i></div>
					</div>
					<p><input type="submit" value="Submit" class="ui button" name="submit" style="margin-top:1em"/></p>
					</form>
					<div class="retrieve_ncert"></div>
				</div>
			</div>
			<?php } ?>
		</div>
		<div style="clear: both;">&nbsp;</div>
	</div>
<?php } 
?>	
	
	<!-- end #content -->
</div> <!--end #page-->


