<!--query-->
<?php 
if(isset($_GET['subpage'])) $subpage = $_GET['subpage'];
else						$subpage = 'event';

switch($subpage){
	case 'event': load_query_event(); break;
	case 'ncert': load_query_ncert(); break;
	case 'contact': load_query_contact(); break;
	case 'client': load_query_client(); break;
	case 'network': load_query_ips(); break;
	case 'fetch': load_query_fetch(); break;
}

function load_query_event(){
	$db = Database::get();
	$table = "security_event"; // 設定你想查詢資料的資料表
	$condition = "1 = ?";
	$order_by = "EventID DESC,OccurrenceTime DESC";
	$db->query($table, $condition, $order_by, $fields = "*", $limit = "", [1]);
	$last_num_rows = $db->getLastNumRows();
	
	$page = isset($_GET['page']) ? $_GET['page'] : 1; 
	$page_parm = getPaginationParameter($page, $last_num_rows);
	
	$limit = "limit ".($start = $page_parm['start']).",".($offset = $page_parm['offset']);	
	$events = $db->query($table, $condition, $order_by, $fields = "*", $limit, [1]);
?>
<div id="page" class="container">
<div id="content">
		<div class="sub-content show">
			<div class="post security_event">
				<div class="post_title">本府資安事件查詢</div>
				<div class="post_cell">
				<form class="ui form" action="javascript:void(0)">
 				<div class="fields">
			    	<div class="field">
					    <label>種類</label>
						<select name="keyword" id="keyword" class="ui fluid dropdown" required>
						<option value="IP"  selected>設備IP</option>
						<option value="Status" >結案狀態</option>
						<option value="EventTypeName" >資安類型</option>
						<option value="DeviceTypeName" >設備類型</option>
						<option value="DeviceOwnerName" >所有人姓名</option>
						<option value="OccurrenceTime" >發現日期</option>
						<option value="all" >全部</option>
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
						<button id="show_all_btn" class="ui button" onclick="window.location.href='/query/event/'">顯示全部</button>
					</div>
				</div>
				</form>
				<div class="record_content">
                <?php
				if($last_num_rows==0){
					echo "查無此筆紀錄";
				}else{
					echo "共有".$last_num_rows."筆資料！";
					echo "<div class='ui relaxed divided list'>";
					foreach($events as $event){	
						echo "<div class='item'>";
						echo "<div class='content'>";
							echo "<a>";
							if($event['Status']=="已結案")echo "<i class='check circle icon' style='color:green'></i>";
							else echo "<i class='exclamation circle icon'></i>";
							echo date_format(new DateTime($event['OccurrenceTime']),'Y-m-d')."&nbsp&nbsp";
							echo $event['Status']."&nbsp&nbsp";
							echo "<span style='background:#fde087'>".$event['EventTypeName']."</span>&nbsp&nbsp";
							echo $event['Location']."&nbsp&nbsp";
							echo "<span style='background:#DDDDDD'>".$event['IP']."</span>&nbsp&nbsp";
							echo $event['DeviceOwnerName']."&nbsp&nbsp";
							echo $event['DeviceOwnerPhone']."&nbsp&nbsp";
							echo "<i class='angle down icon'></i>";
							echo "</a>";
							echo "<div class='description'>";
								echo "<ol>";
								echo "<li>序號:".$event['EventID']."</li>";
								echo "<li>結案狀態:".$event['Status']."</li>";
								echo "<li>發現日期:".date_format(new DateTime($event['OccurrenceTime']),'Y-m-d')."</li>";
								echo "<li>資安事件類型:".$event['EventTypeName']."</li>";
								echo "<li>位置:".$event['Location']."</li>";
								echo "<li>電腦IP:".$event['IP']."</li>";
								echo "<li>封鎖原因:".$event['BlockReason']."</li>";
								echo "<li>設備類型:".$event['DeviceTypeName']."</li>";
								echo "<li>電腦所有人姓名:".$event['DeviceOwnerName']."</li>";
								echo "<li>電腦所有人分機:".$event['DeviceOwnerPhone']."</li>";
								echo "<li>機關:".$event['AgencyName']."</li>";
								echo "<li>單位:".$event['UnitName']."</li>";
								echo "<li>處理日期(國眾):".$event['NetworkProcessContent']."</li>";
								echo "<li>處理日期(三佑科技):".$event['MaintainProcessContent']."</li>";
								echo "<li>處理日期(京稘或中華SOC):".$event['AntivirusProcessContent']."</li>";
								echo "<li>未能處理之原因及因應方式:".$event['UnprocessedReason']."</li>";
								echo "<li>備註:".$event['Remarks']."</li>";
								echo "</ol>";
							echo "</div>";
							echo "</div>";
						echo "</div>";
					}
				echo "</div>";
				/* Create Pagination Element*/ 
				echo pagination($prev_page = $page_parm['prev_page'], $next_page = $page_parm['next_page'], $lower_bound = $page_parm['lower_bound'], $upper_bound = $page_parm['upper_bound'], $total_page = $page_parm['total_page'], "query", "event", 0, $page, "");
				}
                ?>
				</div>
			</div>
			</div>
		</div>
		<div style="clear: both;"></div>
	</div>
<?php } 
function load_query_ncert(){
	$db = Database::get();
	$table = "tainangov_security_Incident"; // 設定你想查詢資料的資料表
	$condition = "1 = ?";
	$order_by = "IncidentID DESC";
	$db->query($table, $condition, $order_by, $fields = "*", $limit = "", [1]);
	$last_num_rows = $db->getLastNumRows();
	
	$page = isset($_GET['page']) ? $_GET['page'] : 1; 
	$page_parm = getPaginationParameter($page, $last_num_rows);
	
	$limit = "limit ".($start = $page_parm['start']).",".($offset = $page_parm['offset']);	
	$incidents = $db->query($table, $condition, $order_by, $fields = "*", $limit, [1]);
?>	
<div id="page" class="container">
<div id="content">
		<div class="sub-content show">
			<div class="post tainangov_security_Incident">
				<div class="post_title">技服資安通報查詢</div>
				<div class="post_cell">
					<form class="ui form" action="javascript:void(0)">
					<div class="fields">
						<div class="field">
							<label>種類</label>
							<select name="keyword" id="keyword" class="ui fluid dropdown" required>
							<option value="PublicIP"  selected>IP(網址)</option>
							<option value="Status" >結案狀態</option>
							<option value="Classification" >事故類型</option>
							<option value="OrganizationName" >機關名稱</option>
							<option value="NccstPT" >攻防演練(是/否)</option>
							<option value="OccurrenceTime" >發現日期</option>
							<option value="all" >全部</option>
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
							<button id="show_all_btn" class="ui button" onclick="window.location.href='/query/ncert/'">顯示全部</button>
						</div>
					</div>
					</form>
					<div class="record_content">
					<?php
					if($last_num_rows==0){
						echo "查無此筆紀錄";
					}else{
						echo "共有".$last_num_rows."筆資料！";
						echo "<div class='ui relaxed divided list'>";
						foreach($incidents as $incident){	
							echo "<div class='item'>";
							echo "<div class='content'>";
								echo "<a>";
								echo date_format(new DateTime($incident['OccurrenceTime']),'Y-m-d')."&nbsp&nbsp";
								echo $incident['Status']."&nbsp&nbsp";
								echo "<span style='background:#DDDDDD'>".$incident['ImpactLevel']."</span>&nbsp&nbsp";
								echo $incident['Classification']."&nbsp&nbsp";
								echo "<span style='background:#fde087'>".$incident['PublicIP']."</span>&nbsp&nbsp";
								echo $incident['OrganizationName'];
								echo "<i class='angle down icon'></i>";
								echo "</a>";
								
								echo "<div class='description'>";
									echo "<ol>";
									echo "<li>編號:".$incident['IncidentID']."</li>";
									echo "<li>結案狀態:".$incident['Status']."</li>";
									echo "<li>事件編號:".$incident['NccstID']."</li>";	
									echo "<li>行政院攻防演練:".$incident['NccstPT']."</li>";
									echo "<li>攻防演練衝擊性:".$incident['NccstPTImpact']."</li>";
									echo "<li>機關名稱:".$incident['OrganizationName']."</li>";
									echo "<li>聯絡人:".$incident['ContactPerson']."</li>";
									echo "<li>電話:".$incident['Tel']."</li>";
									echo "<li>電子郵件:".$incident['Email']."</li>";
									echo "<li>資安維護廠商:".$incident['SponsorName']."</li>";
									echo "<li>對外IP或網址:".$incident['PublicIP']."</li>";
									echo "<li>使用用途:".$incident['DeviceUsage']."</li>";
									echo "<li>作業系統:".$incident['OperatingSystem']."</li>";
									echo "<li>入侵網址:".$incident['IntrusionURL']."</li>";
									echo "<li>影響等級:".$incident['ImpactLevel']."</li>";
									echo "<li>事故分類:".$incident['Classification']."</li>";
									echo "<li>事故說明:".$incident['Explaination']."</li>";
									echo "<li>影響評估:".$incident['Evaluation']."</li>";
									echo "<li>應變措施:".$incident['Response']."</li>";
									echo "<li>解決辦法/結報內容:".$incident['Solution']."</li>";
									echo "<li>發生時間:".$incident['OccurrenceTime']."</li>";
									echo "<li>通報時間:".$incident['InformTime']."</li>";	
									echo "<li>修復時間:".$incident['RepairTime']."</li>";
									echo "<li>審核機關審核時間:".$incident['TainanGovVerificationTime']."</li>";
									echo "<li>技服中心審核時間:".$incident['NccstVerificationTime']."</li>";
									echo "<li>通報結報時間:".$incident['FinishTime']."</li>";	
									echo "<li>通報執行時間(時:分):".$incident['InformExecutionTime']."</li>";
									echo "<li>結案執行時間(時:分):".$incident['FinishExecutionTime']."</li>";
									echo "<li>中華SOC複測結果:".$incident['SOCConfirmation']."</li>";
									echo "<li>改善計畫提報日期:".$incident['ImprovementPlanTime']."</li>";	
									echo "</ol>";
								echo "</div>";
								echo "</div>";
							echo "</div>";
						}
						echo "</div>";
					/* Create Pagination Element*/ 
					echo pagination($prev_page = $page_parm['prev_page'], $next_page = $page_parm['next_page'], $lower_bound = $page_parm['lower_bound'], $upper_bound = $page_parm['upper_bound'], $total_page = $page_parm['total_page'], "query", "ncert", 0, $page, "");
					}
					?>
					</div> <!--End of record_content-->	
				</div><!--End of post_cell-->
			</div><!--End of post-->
		</div><!--End of sub-content-->
		<div style="clear: both;"></div>
	</div>
<?php } 
function load_query_contact(){
	$db = Database::get();
	$sql = "SELECT a.*, b.rank FROM( SELECT * FROM security_contact UNION SELECT * FROM security_contact_extra ORDER by OID asc,person_type asc )a LEFT JOIN security_rank AS b ON a.OID = b.OID";
	$db->execute($sql, []);
	$last_num_rows = $db->getLastNumRows();

	$table = "(SELECT * FROM security_contact UNION SELECT * FROM security_contact_extra)a";
	$condition = "1 = ? GROUP BY OID";
	$fields = "OID";
	$db->query($table, $condition, $order_by = "1" , $fields, $limit = "", [1]);
	$oid_num = $db->getLastNumRows();

	$page = isset($_GET['page']) ? $_GET['page'] : 1; 
	$page_parm = getPaginationParameter($page, $last_num_rows);
	
	$limit = "limit ".($start = $page_parm['start']).",".($offset = $page_parm['offset']);	
	$sql = $sql." limit ".$start.",".$offset;
	$contacts = $db->execute($sql, []);
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
							<select name="keyword" id="keyword" class="ui fluid dropdown" required>
							<option value="organization"  selected>機關名稱</option>
							<option value="rank" >機關資安等級</option>
							<option value="OID" >機關OID</option>
							<option value="person_name" >聯絡人姓名</option>
							<option value="person_type" >聯絡人類別</option>
							<option value="all" >全部</option>
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
							<button id="show_all_btn" class="ui button" onclick="window.location.href='/query/contact/'">顯示全部</button>
						</div>
					</div>
					</form>
					<div class="record_content">
					<?php
					if($last_num_rows==0){
						echo "查無此筆紀錄";
					}else{
						echo "共有".$last_num_rows."筆資料！";
						echo "共有".$oid_num."個機關！";
						echo "<div class='ui relaxed divided list'>";
						foreach($contacts as $contact){	
							echo "<div class='item'>";
							echo "<div class='content'>";
								echo "<a>";
								echo $contact['organization']."&nbsp&nbsp";
								if( !empty($contact['rank'] )) echo "<span style='color:#f80000'>".$contact['rank']."</span>&nbsp&nbsp";
								echo $contact['person_name']."&nbsp&nbsp";
								echo "<span style='background:#fde087'>".$contact['person_type']."</span>&nbsp&nbsp";
								echo $contact['email']."&nbsp&nbsp";
								echo "<span style='background:#DDDDDD'>".$contact['tel']."#".$contact['ext']."</span>&nbsp&nbsp";
								echo "<i class='angle down icon'></i>";
								echo "</a>";
								echo "<div class='description'>";
									echo "<ol>";
									echo "<li>序號:".$contact['CID']."</li>";
									echo "<li>OID:".$contact['OID']."</li>";
									echo "<li>資安責任等級:".$contact['rank']."</li>";
									echo "<li>機關名稱:".$contact['organization']."</li>";
									echo "<li>單位名稱:".$contact['unit']."</li>";
									echo "<li>姓名:".$contact['person_name']."</li>";
									echo "<li>職稱:".$contact['position']."</li>";
									echo "<li>資安聯絡人類型:".$contact['person_type']."</li>";
									echo "<li>地址:".$contact['address']."</li>";
									echo "<li>電話:".$contact['tel']."</li>";
									echo "<li>分機:".$contact['ext']."</li>";
									echo "<li>傳真:".$contact['fax']."</li>";
									echo "<li>email:".$contact['email']."</li>";
									echo "</ol>";
								echo "</div>";
								echo "</div>";
							echo "</div>";
						}
					
					echo "</div>";
											
					/* Create Pagination Element*/ 
					echo pagination($prev_page = $page_parm['prev_page'], $next_page = $page_parm['next_page'], $lower_bound = $page_parm['lower_bound'], $upper_bound = $page_parm['upper_bound'], $total_page = $page_parm['total_page'], "query", "contact", 0, $page, "");
					}
					?>
					</div> <!--End of record_content-->	
				</div><!--End of post_cell-->
			</div><!--End of post-->
		</div><!--End of sub-content-->
		<div style="clear: both;"></div>
	</div>
<?php } 
function load_query_client(){
	$db = Database::get();
	$table = "drip_client_list"; // 設定你想查詢資料的資料表
	$condition = "1 = ?";
	$order_by = "DetectorName,IP";
	$db->query($table, $condition, $order_by, $fields = "*", $limit = "", [1]);
	$last_num_rows = $db->getLastNumRows();
	
	$page = isset($_GET['page']) ? $_GET['page'] : 1; 
	$page_parm = getPaginationParameter($page, $last_num_rows);
	
	$limit = "limit ".($start = $page_parm['start']).",".($offset = $page_parm['offset']);	
	$IS_client = $db->query($table, $condition, $order_by, $fields = "*", $limit, [1]);
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
						<div class="query_content"></div>
						<div class="fields">
							<div class="field">
								<label>種類</label>
								<select name="keyword" id="keyword" class="ui fluid dropdown" required>
								<option value="ClientName"  selected>電腦名稱</option>
								<option value="IP" >內部IP</option>
								<option value="UserName" >使用者帳號</option>
								<option value="OrgName" >單位名稱</option>
								<option value="ad" >ad</option>
								<option value="gcb" >gcb</option>
								<option value="wsus" >wsus</option>
								<option value="antivirus" >antivirus</option>
								<option value="all" >全部</option>
								</select>
							</div>
							<div class="field">
								<label>關鍵字</label>
								<div class="ui input">
									<input type='text' name='key' id='key' placeholder="請輸入關鍵字">
								</div>
							</div>
							<div class="field">
								<label>新增條件</label>
								<i class="large square icon plus"></i>
							</div>
							<div class="field">
								<button id="search_btn" name="search_btn" class="ui button">搜尋</button>
							</div>
							 <div class="field">
								<button id="show_all_btn" class="ui button" onclick="window.location.href='/query/client/?tab=1'">顯示全部</button>
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
						<?php
						if($last_num_rows==0){
							echo "查無此筆紀錄";
						}else{
							echo "共有".$last_num_rows."筆資料！";
							echo "<div class='ui relaxed divided list'>";
							foreach($IS_client as $client){
								echo "<div class='item'>";
								echo "<div class='content'>";
									echo "<a>";
									if($client['ad']==1) echo "<i class='circle yellow icon'></i>";
									else echo "<i class='circle outline icon'></i>";
									if($client['gcb']==1) echo "<i class='circle green icon'></i>";
									else echo "<i class='circle outline icon'></i>";
									if($client['wsus']==1) echo "<i class='circle red icon'></i>";
									else echo "<i class='circle outline icon'></i>";
									if($client['antivirus']==1) echo "<i class='circle blue icon'></i>";
									else echo "<i class='circle outline icon'></i>";
									echo $client['DetectorName']."&nbsp&nbsp";
									echo "<span style='background:#fde087'>".$client['IP']."</span>&nbsp&nbsp";
									echo $client['ClientName']."&nbsp&nbsp";
									echo "<span style='background:#fbc5c5'>".$client['OrgName']."</span>&nbsp&nbsp";
									echo $client['Owner']."&nbsp&nbsp";
									echo $client['UserName']."&nbsp&nbsp";
									echo "<i class='angle down icon'></i>";
									echo "</a>";
								echo "<div class='description'>";
									echo "<ol>";
									echo "<li>內網IP:".$client['IP']."</li>";
									echo "<li>MAC位址:".$client['MAC']."</li>";
									echo "<li>設備名稱:".$client['ClientName']."</li>";
									echo "<li>群組名稱:".$client['GroupName']."</li>";
									echo "<li>網卡製造商:".$client['NICProductor']."</li>";
									echo "<li>偵測器名稱:".$client['DetectorName']."</li>";
									echo "<li>偵測器IP:".$client['DetectorIP']."</li>";
									echo "<li>偵測器群組:".$client['DetectorGroup']."</li>";
									echo "<li>交換器名稱:".$client['SwitchName']."</li>";
									echo "<li>連接埠名稱:".$client['PortName']."</li>";
									echo "<li>最後上線時間:".$client['LastOnlineTime']."</li>";
									echo "<li>最後下線時間:".$client['LastOfflineTime']."</li>";
									echo "<li>IP封鎖原因:".$client['IP_BlockReason']."</li>";
									echo "<li>MAC封鎖原因:".$client['MAC_BlockReason']."</li>";
									echo "<li>備註ByIP:".$client['MemoByIP']."</li>";
									echo "<li>備註ByMac:".$client['MemoByMAC']."</li>";
									echo "<li>ad安裝:".$client['ad']."</li>";
									echo "<li>gcb安裝:".$client['gcb']."</li>";
									echo "<li>wsus安裝:".$client['wsus']."</li>";
									echo "<li>antivirus安裝:".$client['antivirus']."</li>";
									echo "<li>OrgName:".$client['OrgName']."</li>";
									echo "<li>Owner:".$client['Owner']."</li>";
									echo "<li>UserName:".$client['UserName']."</li>";
									echo "</ol>";
									if(issetBySession("Level") && $_SESSION['Level'] == 2){
										echo "<button data-ip='".$client['IP']."' id='block-btn' class='ui button'>Block IP</button>";
										echo "<button data-ip='".$client['IP']."' id='unblock-btn' class='ui button'>UnBlock IP</button>";
										echo "<div class='ui centered inline loader'></div>";
										echo "<div class='block_IP_response'></div>";
									}
								echo "</div>";
								echo "</div>";
								echo "</div>";
							}		
							echo "</div>";
							/* Create Pagination Element*/ 
							echo pagination($prev_page = $page_parm['prev_page'], $next_page = $page_parm['next_page'], $lower_bound = $page_parm['lower_bound'], $upper_bound = $page_parm['upper_bound'], $total_page = $page_parm['total_page'], "query", "client", 1, $page, "");
						}
						?>
						</div> <!--End of record_content-->	
					</div> <!--End of tabular_content-->	
	<?php
	$db = Database::get();
	$sql = "SELECT a.*,b.name as os_name,c.name as ie_name FROM gcb_client_list as a LEFT JOIN gcb_os as b ON a.OSEnvID = b.id LEFT JOIN gcb_ie as c ON a.IEEnvID = c.id ORDER by a.ID asc,a.InternalIP asc";
	$db->execute($sql, []);
	$last_num_rows = $db->getLastNumRows();

	$page = isset($_GET['page']) ? $_GET['page'] : 1; 
	$page_parm = getPaginationParameter($page, $last_num_rows);

	$limit = "limit ".($start = $page_parm['start']).",".($offset = $page_parm['offset']);	
	$sql = $sql." limit ".$start.",".$offset;
	$gcb_client = $db->execute($sql, []);
	?>
						<div class="tab-content gcb_client_list show">
						<form class="ui form" action="javascript:void(0)">

						<div class="fields">
							<div class="field">
								<label>種類</label>
								<select name="keyword" id="keyword" class="ui fluid dropdown" required>
								<option value="Name"  selected>電腦名稱</option>
								<option value="InternalIP" >內部IP</option>
								<option value="UserName" >使用者帳號</option>
								<option value="OrgName" >單位名稱</option>
								<option value="all" >全部</option>
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
								<button id="show_all_btn" class="ui button" onclick="window.location.href='/query/client/?tab=2'">顯示全部</button>
							</div>
						</div>
						</form>
						<div class="record_content">
						<?php
						if($last_num_rows==0){
							echo "查無此筆紀錄";
						}else{
							echo "共有".$last_num_rows."筆資料！";
						echo "<div class='ui relaxed divided list'>";
						foreach($gcb_client as $client){
							echo "<div class='item'>";
							echo "<div class='content'>";
								echo "<a>";
								if($client['IsOnline'] == "1")		echo "<i class='circle green icon'></i>";
								else							echo "<i class='circle outline icon'></i>";
								switch($client['GsStat']){
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
								
								echo $client['Name']."&nbsp&nbsp";
								echo "<span style='background:#fde087'>".$client['OrgName']."</span>&nbsp&nbsp";
								echo $client['UserName']."&nbsp&nbsp";
								echo $client['Owner']."&nbsp&nbsp";
								echo "<span style='background:#DDDDDD'>".long2ip($client['InternalIP'])."</span>&nbsp&nbsp";
								echo $client['os_name']."&nbsp&nbsp";
								echo "<i class='angle down icon'></i>";
								echo "</a>";
								echo "<div class='description'>";
									echo "<ol>";
									echo "<li><a href='/ajax/gcb_detail.php?action=detail&id=".$client['ID']."' target='_blank'>序號:".$client['ID']."(用戶端資訊)&nbsp<i class='external alternate icon'></i></a></li>";
									echo "<li>外部IP:".long2ip($client['ExternalIP'])."</li>";
									echo "<li>內部IP:".long2ip($client['InternalIP'])."</li>";
									echo "<li>電腦名稱:".$client['Name']."</li>";
									echo "<li>單位名稱:".$client['OrgName']."</li>";
									echo "<li>使用者帳號:".$client['UserName']."</li>";
									echo "<li>使用者名稱:".$client['Owner']."</li>";
									echo "<li>OS:".$client['os_name']."</li>";
									echo "<li>IE:".$client['ie_name']."</li>";
									echo "<li>是否上線:".$client['IsOnline']."</li>";
									echo "<li>Gcb總通過數[未包含例外]:".$client['GsAll_0']."</li>";
									echo "<li>Gcb總通過數[包含例外]:".$client['GsAll_1']."</li>";
									echo "<li>Gcb總通過數[總數]:".$client['GsAll_2']."</li>";
									echo "<li>Gcb例外數量:".$client['GsExcTot']."</li>";
									echo "<li><a href='/ajax/gcb_detail.php?action=gscan&id=".$client['GsID']."' target='_blank'>Gcb掃描編號:".$client['GsID']."(掃描結果資訊)&nbsp<i class='external alternate icon'></i></a></li>";
									echo "<li>Gcb派送編號:".$client['GsSetDeployID']."</li>";
									echo "<li>Gcb狀態:".$GsStat_str."</li>";
									echo "<li>Gcb回報時間:".$client['GsUpdatedAt']."</li>";
									echo "</ol>";
								echo "</div>";
								echo "</div>";
							echo "</div>";
						}
						
						echo "</div>";
						/* Create Pagination Element*/ 
						echo pagination($prev_page = $page_parm['prev_page'], $next_page = $page_parm['next_page'], $lower_bound = $page_parm['lower_bound'], $upper_bound = $page_parm['upper_bound'], $total_page = $page_parm['total_page'], "query", "client", 2, $page, "");
						}
						?>
						</div> <!--End of record_content-->	
					</div> <!--End of tabular_content-->	
	<?php
	$sort = isset($_GET['sort'])?$_GET['sort']:'TargetID';	
	$db = Database::get();
	$table = "wsus_computer_status"; // 設定你想查詢資料的資料表
	$condition = "1 = ?";
	$order_by = $sort;
	$db->query($table, $condition, $order_by, $fields = "*", $limit = "", [1]);
	$last_num_rows = $db->getLastNumRows();

	$page = isset($_GET['page']) ? $_GET['page'] : 1; 
	$page_parm = getPaginationParameter($page, $last_num_rows);

	$limit = "limit ".($start = $page_parm['start']).",".($offset = $page_parm['offset']);	
	$wsus_client = $db->query($table, $condition, $order_by, $fields = "*", $limit, [1]);
	?>	
					<div class="tab-content wsus_client_list">
						<form class="ui form" action="javascript:void(0)">

						<div class="fields">
							<div class="field">
								<label>種類</label>
								<select name="keyword" id="keyword" class="ui fluid dropdown" required>
								<option value="FullDomainName"  selected>電腦名稱</option>
								<option value="IPAddress" >內部IP</option>
								<option value="all" >全部</option>
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
								<button id="show_all_btn" class="ui button" onclick="window.location.href='/query/client/?tab=3'">顯示全部</button>
							</div>
						</div>
						</form>
						<div class="record_content">
						<?php
						if($last_num_rows==0){
							echo "查無此筆紀錄";
						}else{
							echo "共有".$last_num_rows."筆資料！";
							echo "<div class='ui relaxed divided list'>";
								echo "<div class='item'>";
									echo "<div class='content'>";
										echo "<div class='header'>";
										echo "<a href='/query/client/?tab=3&sort=FullDomainName'>電腦名稱</a>&nbsp&nbsp";
										echo "<a href='/query/client/?tab=3&sort=IPAddress'>內網IP</a>&nbsp&nbsp";
										echo "<a href='/query/client/?tab=3&sort=NotInstalled'>未安裝更新</a>&nbsp&nbsp";
										echo "<a href='/query/client/?tab=3&sort=Failed'>安裝失敗更新</a>&nbsp&nbsp";
										echo "<a href='/query/client/?tab=3&sort=OSDescription'>作業系統</a>&nbsp&nbsp";
										echo "</div>";
									echo "</div>";
								echo "</div>";
						foreach($wsus_client as $client) {
							$table = "wsus_computer_updatestatus_kbid";
							$order_by = "ID";
							$condition = "TargetID = :TargetID AND UpdateState = :UpdateState";
							$data_array = [':TargetID'=>$client['TargetID'],':UpdateState'=>'NotInstalled'];
							$notinstalled_kb = $db->query($table, $condition, $order_by, $fields = "*", $limit = "",$data_array);
							$data_array = [':TargetID'=>$client['TargetID'],':UpdateState'=>'Failed'];
							$failed_kb = $db->query($table, $condition, $order_by, $fields = "*", $limit = "", $data_array);
							echo "<div class='item'>";
							echo "<div class='content'>";
								echo "<a>";
								echo strtoupper(str_replace(".tainan.gov.tw","",$client['FullDomainName']))."&nbsp&nbsp";
								echo "<span style='background:#fde087'>".$client['IPAddress']."</span>&nbsp&nbsp";
								echo "<span style='background:#DDDDDD'>".$client['NotInstalled']."</span>&nbsp&nbsp";
								echo "<span style='background:#fbc5c5'>".$client['Failed']."</span>&nbsp&nbsp";
								echo $client['OSDescription'];
								echo "<i class='angle down icon'></i>";
								echo "</a>";
								echo "<div class='description'>";
									echo "<ol>";
									echo "<li>序號:".$client['TargetID']."</li>";
									echo "<li>電腦名稱:".strtoupper(str_replace(".tainan.gov.tw","",$client['FullDomainName']))."</li>";
									echo "<li>內網IP:".$client['IPAddress']."</li>";
									echo "<li>未知更新數量:".$client['Unknown']."</li>";
									echo "<li>未安裝更新數量:".$client['NotInstalled']."</li>";
									foreach($notinstalled_kb as $kb) {
										echo "<strong>KB".$kb['KBArticleID']."</strong> ";	
									}	
									echo "<li>已下載更新數量:".$client['Downloaded']."</li>";
									echo "<li>已安裝更新數量:".$client['Installed']."</li>";
									echo "<li>安裝失敗更新數量:".$client['Failed']."</li>";
									foreach($failed_kb as $kb) {
										echo "<strong>KB".$kb['KBArticleID']."</strong> ";	
									}	
									echo "<li>已安裝待重開機更新數量:".$client['InstalledPendingReboot']."</li>";
									echo "<li>上次狀態回報日期:".dateConvert($client['LastReportedStatusTime'])."</li>";
									echo "<li>上次更新重開機日期:".dateConvert($client['LastReportedRebootTime'])."</li>";
									echo "<li>上次可用更新日期:".dateConvert($client['EffectiveLastDetectionTime'])."</li>";
									echo "<li>上次同步日期:".dateConvert($client['LastSyncTime'])."</li>";
									echo "<li>上次修改日期:".dateConvert($client['LastChangeTime'])."</li>";
									echo "<li>上次同步結果:".$client['LastSyncResult']."</li>";
									echo "<li>製造商:".$client['ComputerMake']."</li>";
									echo "<li>型號:".$client['ComputerModel']."</li>";
									echo "<li>作業系統:".$client['OSDescription']."</li>";
									echo "</ol>";
								echo "</div>";
								echo "</div>";
							echo "</div>";
						}
						echo "</div>";
						/* Create Pagination Element*/ 
						echo pagination($prev_page = $page_parm['prev_page'], $next_page = $page_parm['next_page'], $lower_bound = $page_parm['lower_bound'], $upper_bound = $page_parm['upper_bound'], $total_page = $page_parm['total_page'], "query", "client", 3, $page, $sort);
						}
						?>
						</div> <!--End of record_content-->	
					</div> <!--End of tabular_content-->	
	<?php
	$db = Database::get();
	$table = "antivirus_client_list"; // 設定你想查詢資料的資料表
	$condition = "1 = ?";
	$order_by = "DomainLevel, ClientName";
	$db->query($table, $condition, $order_by, $fields = "*", $limit = "", [1]);
	$last_num_rows = $db->getLastNumRows();

	$page = isset($_GET['page']) ? $_GET['page'] : 1; 
	$page_parm = getPaginationParameter($page, $last_num_rows);

	$limit = "limit ".($start = $page_parm['start']).",".($offset = $page_parm['offset']);	
	$antivirus_client = $db->query($table, $condition, $order_by, $fields = "*", $limit, [1]);
	?>	
					<div class="tab-content antivirus_client_list show">
						<form class="ui form" action="javascript:void(0)">

						<div class="fields">
							<div class="field">
								<label>種類</label>
								<select name="keyword" id="keyword" class="ui fluid dropdown" required>
								<option value="ClientName"  selected>電腦名稱</option>
								<option value="IP" >內部IP</option>
								<option value="all" >全部</option>
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
								<button id="show_all_btn" class="ui button" onclick="window.location.href='/query/client/?tab=4'">顯示全部</button>
							</div>
						</div>
						</form>
						<div class="record_content">
						<?php //select data form database
						if($last_num_rows==0){
							echo "查無此筆紀錄";
						}else{
							echo "共有".$last_num_rows."筆資料！";
							echo "<div class='ui relaxed divided list'>";
							foreach($antivirus_client as $client) {
								echo "<div class='item'>";
								echo "<div class='content'>";
									echo "<a>";
									if($client['ConnectionState'] == "線上")	echo "<i class='circle green icon'></i>";
									else									echo "<i class='circle outline icon'></i>";
									echo $client['ClientName']."&nbsp&nbsp";
									echo "<span style='background:#fde087'>".$client['IP']."</span>&nbsp&nbsp";
									echo "<span style='background:#fbc5c5'>".$client['OS']."</span>&nbsp&nbsp";
									echo $client['VirusNum']."&nbsp&nbsp";
									echo $client['SpywareNum']."&nbsp&nbsp";
									echo "<span style='background:#DDDDDD'>".$client['VirusPatternVersion']."</span>&nbsp&nbsp";
									echo $client['LogonUser']."&nbsp&nbsp";
									echo "<i class='angle down icon'></i>";
									echo "</a>";
								echo "<div class='description'>";
									echo "<ol>";
									echo "<li>設備名稱:".$client['ClientName']."</li>";
									echo "<li>內網IP:".$client['IP']."</li>";
									echo "<li>網域階層:".$client['DomainLevel']."</li>";
									echo "<li>連線狀態:".$client['ConnectionState']."</li>";
									echo "<li>GUID:".$client['GUID']."</li>";
									echo "<li>掃描方式:".$client['ScanMethod']."</li>";
									echo "<li>DLP狀態:".$client['DLPState']."</li>";
									echo "<li>病毒數量:".$client['VirusNum']."</li>";
									echo "<li>間諜程式數量:".$client['SpywareNum']."</li>";
									echo "<li>作業系統:".$client['OS']."</li>";
									echo "<li>位元版本:".$client['BitVersion']."</li>";
									echo "<li>MAC位址:".$client['MAC']."</li>";
									echo "<li>設備版本:".$client['ClientVersion']."</li>";
									echo "<li>病毒碼版本:".$client['VirusPatternVersion']."</li>";
									echo "<li>登入使用者:".$client['LogonUser']."</li>";
									echo "</ol>";
								echo "</div>";
								echo "</div>";
								echo "</div>";
							}		
							echo "</div>";
							/* Create Pagination Element*/ 
							echo pagination($prev_page = $page_parm['prev_page'], $next_page = $page_parm['next_page'], $lower_bound = $page_parm['lower_bound'], $upper_bound = $page_parm['upper_bound'], $total_page = $page_parm['total_page'], "query", "client", 4, $page, "");
						}
						?>
						</div> <!--End of record_content-->	
					</div> <!--End of tabular_content-->	
					</div> <!--End of attached_menu-->	
				</div><!--End of post_cell-->
			</div><!--End of post-->
		</div><!--End of sub-content-->
		<div style="clear: both;"></div>
	</div>
<?php } 
function load_query_fetch(){
?>	
<div id="page" class="container">
<div id="content">
		<div class="sub-content show">
			<div class="post">
				<div class="post_title">Fetch Google Sheets and GCB</div>
				<div class="post_cell">
					<button id="gs_event_btn" class="ui button self-btn">Fetch Event GS</button>
					<button id="gs_ncert_event_btn" class="ui button self-btn">Fetch Ncert GS</button>
					<button id="gcb_api_btn" class="ui button self-btn">Fetch GCB</button>
					<div class="retrieve_info"></div>
				</div>
			</div>
			<?php 
			// admin is given permission to edit this block	
			if(issetBySession("Level") && $_SESSION['Level'] == 2){
				//echo $_SESSION['Level'];
			?>
			<div class="post">
				<div class="post_title">Fetch Ncert</div>
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
		<div style="clear: both;"></div>
	</div>
<?php } 
?>	
<!-- end #content -->
</div> <!--end #page-->


