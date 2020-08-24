<!--info-->
<?php 
if(isset($_GET['subpage'])) $subpage = $_GET['subpage'];
else						$subpage = 'enews';

switch($subpage){
	case 'enews': load_info_enews(); break;
	case 'ranking': load_info_ranking(); break;
	case 'vul': load_info_vul(); break;
	case 'client': load_info_client(); break;
	case 'network': load_info_network(); break;
}
function load_info_enews(){
	$db = Database::get();
	$table = "tainangov_security_Incident"; // 設定你想查詢資料的資料表
	$db->query($table, $condition = "1", $order_by = "1", $fields = "*", $limit = "");
	$ncert_num = $db->getLastNumRows();
	$db->query($table, $condition = "Status LIKE '已結案'", $order_by = "1", $fields = "*", $limit = "");
	$done_ncert_num = $db->getLastNumRows();
                    
	$table = "security_event"; // 設定你想查詢資料的資料表
	$db->query($table, $condition = "1", $order_by = "1", $fields = "*", $limit = "");
	$event_num = $db->getLastNumRows();
	$db->query($table, $condition = "Status LIKE '已結案'", $order_by = "1", $fields = "*", $limit = "");
	$done_event_num = $db->getLastNumRows();
	$db->query($table, $condition = "Status LIKE '未完成'", $order_by = "1", $fields = "*", $limit = "");
	$undone_event_num = $db->getLastNumRows();
	$db->query($table, $condition = "Status LIKE '未完成' AND NOT(UnprocessedReason LIKE '')", $order_by = "1", $fields = "*", $limit = "");
	$excepted_event_num = $db->getLastNumRows();

	$date_from_week = date('Y-m-d',strtotime('monday this week'));  
	$date_to_week = date('Y-m-d',strtotime('sunday this week'));
	$date_from_month = date('Y-m-d',strtotime('first day of this month'));
	$date_to_month = date('Y-m-d',strtotime('last day of this month'));  
	$db->query($table, $condition = "OccurrenceTime BETWEEN '".$date_from_month."' AND '".$date_to_month."'", $order_by = "1", $fields = "*", $limit = "");
	$thisMonth_event_num = $db->getLastNumRows();
	$db->query($table, $condition = "OccurrenceTime BETWEEN '".$date_from_week."' AND '".$date_to_week."'", $order_by = "1", $fields = "*", $limit = "");
	$thisWeek_event_num = $db->getLastNumRows();
	$completion_rate = round($done_event_num / $event_num * 100,2)."%"; 

	$order_by = "EventID desc";
	$limit = "LIMIT 10";
	$last_10_events = $db->query($table, $condition = "1", $order_by , $fields = "*", $limit);
?>
<div id="page" class="container">
	<div id="content">
		<div class="sub-content show">
			<div class="post">
				<div class="post_title">資安事件列管(已結案/總數)</div>
				<div class="post_cell">
					<center>
						<div class="ui small statistic">
						  <div class="value"><?php echo $done_event_num." / ".$event_num ?>  </div>
						  <div class="label">本府事件</div>
						</div>
						<br>
						<div class="ui small statistic">
						  <div class="value"><?php echo $done_ncert_num." / ".$ncert_num ?>  </div>
						  <div class="label">技服通報</div>
						</div>
					</center>
			    </div>		
			</div>
			<div class="post">
				<div class="post_title">Enews</div>
					<div class="post_cell">
					<div class="post_table">
					<table>
					<tr>
						<th>項目</th>
						<th>數值</th>
					</tr>
					<tr>
						<td>列管數量</td>
						<td><?php echo $event_num ?></td>
					</tr>
					<tr>
						<td>已完成</td>
						<td><?php echo $done_event_num ?></td>
					</tr>
					<tr>
						<td>未完成</td>
						<td><?php echo $undone_event_num ?></td>
					</tr>
					<tr>
						<td>無法完成</td>
						<td><?php echo $excepted_event_num ?></td>
					</tr>
						<td>本月已發生</td>
						<td><?php echo $thisMonth_event_num ?></td>
					</tr>
					<tr>
						<td>本周已發生</td>
						<td><?php echo $thisWeek_event_num ?></td>
					</tr>
					<tr>
						<td>完成率</td>
						<td><?php echo $completion_rate ?></td>
					</tr>
					</table>
					</div>
					</div>
			</div>
			<div class="post">
				<div class="post_title">資安事件趨勢圖(最近3個月)</div>
				<div class="post_cell">
					<div id="chartA" class="chart"></div>	
				</div>
			</div>
			<div class="post">
				<div class="post_title">資安事件清單(最近10筆)</div>
				<div class="post_cell">
					<table class="ui very basic single line table">
						<thead>	
							<tr>
								<th>發現日期</th>
								<th>結案狀態</th>
								<th>資安事件類型</th>
								<th>位置</th>
								<th>設備IP</th>
								<th>所有人機關</th>
								<th>所有人姓名</th>
							</tr>
						</thead>
						<tbody>	
						<?php
						foreach($last_10_events as $event) {   
							echo "<tr>";
								echo "<td>".date_format(new DateTime($event['OccurrenceTime']),'Y-m-d')."</td>";
								echo "<td>".$event['Status']."</td>";
                        		echo "<td>".$event['EventTypeName']."</td>";
								echo "<td>".$event['Location']."</td>";
								echo "<td>".$event['IP']."</td>";
								echo "<td>".$event['AgencyName']."</td>";
								echo "<td>".$event['DeviceOwnerName']."</td>";
							echo "</tr>";
						}
						?>
						</tbody>
						</table>
				</div>
				<div class="see_more" style="text-align:right"><a href="/query/event/">See More...</a></div>
			</div>
			<div class="post">
				<div class="post_title">資安事件SOP</div>
				<div class="post_cell">
					<img class="image" src="/images/sop.png">
				</div>
			</div>
		</div>
		<div style="clear: both;">&nbsp;</div>
	</div><!-- end #content -->
</div> <!--end #page-->
<?php } 
function load_info_ranking(){
?>	
<div id="page" class="container">
	<div id="content">
		<div class="sub-content show">
			<div class="post">
				<div class="post_title">資安事件跨年度比較</div>
				<div class="post_cell">
				繪製長條圖時，長條柱或柱組中線須對齊項目刻度。相較之下，折線圖則是將數據代表之點對齊項目刻度。在數字大且接近時，兩者皆可使用波浪形省略符號，以擴大表現數據間的差距，增強理解和清晰度。
				</div>
				<div id="chartB" class="chart"></div>	
				<button id="show_chart_btn" class="ui button">Plot</button>
			</div>
			<div class="post">
				<div class="post_title">資安類型統計圖</div>
				<div class="post_cell">
					<div id="chartC" class="chart"></div>	
			    </div>		
			</div>
			<div class="post">
				<div class="post_title">Top 10 機關資安事件排序</div>
				<div class="post_cell">
					<div id="chartC-2" class="chart"></div>	
			    </div>		
			</div>
			<div class="post">
				<div class="post_title">Top 10 攻擊目標IP</div>
				<div class="post_cell">
					<div id="chartC-3" class="chart"></div>	
			    </div>		
			</div>
		</div>
		<div style="clear: both;">&nbsp;</div>
	</div><!-- end #content -->
<?php } 
function load_info_vul(){
	$db = Database::get();
	$sql = "SELECT ou,sum(total_VUL) as total_VUL,sum(fixed_VUL) as fixed_VUL,sum(fixed_VUL)*100.0 / NULLIF(SUM(total_VUL), 0) as total_completion,sum(total_high_VUL) as total_high_VUL, sum(fixed_high_VUL) as fixed_high_VUL,sum(fixed_high_VUL)*100.0 / NULLIF(SUM(total_high_VUL), 0) as high_completion,sum(total_VUL - overdue_high_VUL - overdue_medium_VUL) as non_overdue_VUL, sum(total_VUL - overdue_high_VUL - overdue_medium_VUL)*100.0 / NULLIF(SUM(total_VUL), 0) as non_overdue_completion	FROM V_VUL_tableau GROUP BY ou ORDER BY total_completion desc";
	$ou_vul = $db->execute($sql);
	$sql = "SELECT sum(total_VUL) as total_VUL,sum(fixed_VUL) as fixed_VUL,sum(fixed_VUL)*100.0 / NULLIF(SUM(total_VUL), 0) as total_completion,sum(total_high_VUL) as total_high_VUL, sum(fixed_high_VUL) as fixed_high_VUL, sum(fixed_high_VUL)*100.0 / NULLIF(SUM(total_high_VUL), 0) as high_completion ,sum(total_VUL - overdue_high_VUL - overdue_medium_VUL) as non_overdue_VUL, sum(total_VUL - overdue_high_VUL - overdue_medium_VUL)*100.0 / NULLIF(SUM(total_VUL), 0) as non_overdue_completion	FROM V_VUL_tableau";
	$total_vul = $db->execute($sql);
	$sql = "SELECT COUNT(DISTINCT ip) as host_num FROM scanTarget";
	$host_num = $db->execute($sql)[0]['host_num'];
	$sql = "SELECT SUM(CASE domain WHEN '' THEN 0 ELSE LENGTH(domain)-LENGTH(REPLACE(domain,';',''))+1 END) AS url_num FROM scanTarget";
	$url_num = $db->execute($sql)[0]['url_num'];
?>	
<div id="page" class="container">
	<div id="content">
		<div class="sub-content show">
			<div class="post">
				<div class="post_title">VUL Bar</div>
					<div class="post_cell">
						臺南市政府弱掃平台各單位弱點數量，高風險應於<font color="red">1</font>個月內修補完成，中風險應於<font color="red">2</font>個月內修補完成。<br>
						<div class="post_table">
						<table>
							<thead>
								<tr>
									<th>OU</th>
									<th>修補率 | 已修補數 | 總數</th>
									<th>未逾期率 | 未逾期未修補+已修補數 | 總數</th>
									<th>高風險(修補率 | 已修補數 | 總數)</th>
								</tr>
							</thead>
							<tbody>
							<?php
							foreach($ou_vul as $vul) {
							    //hide ou = 區公所
								if(strchr($vul['ou'],"區公所") == "區公所") echo "<tr style='opacity:0.5'>";
								else echo "<tr>";
									echo "<td data-label='OU'>".$vul['ou']."</td>";
									echo "<td data-label='Total-Risks'>";
									echo "<div class='ui teal progress yckuo' data-percent='".round($vul['total_completion'],0)."' data-total='100' id='example1'>";
						 			echo "<div class='bar'><div class='progress'></div></div>";
						  			echo "<div class='label'>".$vul['fixed_VUL']."/".$vul['total_VUL']."</div>";
									echo "</div>";
									echo "</td>";
									echo "<td data-label='Non-Overdue-Risks'>";
									echo "<div class='ui teal progress yckuo' data-percent='".round($vul['non_overdue_completion'],0)."' data-total='100' id='example1'>";
						 			echo "<div class='bar'><div class='progress'></div></div>";
						  			echo "<div class='label'>".$vul['non_overdue_VUL']."/".$vul['total_VUL']."</div>";
									echo "</div>";
									echo "</td>";
									echo "<td data-label='High-Risks'>";
									echo "<div class='ui teal progress yckuo' data-percent='".round($vul['high_completion'],0)."' data-total='100' id='example1'>";
						 			echo "<div class='bar'><div class='progress'></div></div>";
						  			echo "<div class='label'>".$vul['fixed_high_VUL']."/".$vul['total_high_VUL']."</div>";
									echo "</div>";
									echo "</td>";
								echo "</tr>";
							}
							$vul = $total_vul[0];
							echo "<tr style='color:#FF0000'>";
								echo "<td data-label='OU'>Total</td>";
								echo "<td data-label='Total-Risks'>";
								echo "<div class='ui teal progress yckuo' data-percent='".round($vul['total_completion'],0)."' data-total='100' id='example1'>";
								echo "<div class='bar'><div class='progress'></div></div>";
								echo "<div class='label'>".$vul['fixed_VUL']."/".$vul['total_VUL']."</div>";
								echo "</div>";
								echo "</td>";
								echo "<td data-label='Non-Overdue-Risks'>";
								echo "<div class='ui teal progress yckuo' data-percent='".round($vul['non_overdue_completion'],0)."' data-total='100' id='example1'>";
								echo "<div class='bar'><div class='progress'></div></div>";
								echo "<div class='label'>".$vul['non_overdue_VUL']."/".$vul['total_VUL']."</div>";
								echo "</div>";
								echo "</td>";
								echo "<td data-label='High-Risks'>";
								echo "<div class='ui teal progress yckuo' data-percent='".round($vul['high_completion'],0)."' data-total='100' id='example1'>";
								echo "<div class='bar'><div class='progress'></div></div>";
								echo "<div class='label'>".$vul['fixed_high_VUL']."/".$vul['total_high_VUL']."</div>";
								echo "</div>";
								echo "</td>";
							echo "</tr>";
							$fixed_high_VUL	= $vul['fixed_high_VUL'];
							$total_high_VUL = $vul['total_high_VUL'];
							$high_completion = $vul['high_completion'];
							?>
							</tbody>
						</table>	
						</div>
					</div>
			</div>
			<div class="post">
				<div class="post_title">High Severity Info</div>
				<div class="post_cell">
					<center>
					  <div class="ui small statistic">
						  <div class="value"><?php echo round($high_completion,0) ?> % </div>
						  <div class="label">修補率</div>
					  </div>
					  <br>
					  <div class="ui tiny statistic">
						  <div class="value"><?php echo $fixed_high_VUL ?> / <?php echo $total_high_VUL ?></div>
						  <div class="label">已修補數 / 總數</div>
					  </div>
					  <br>
					  <div class="ui tiny statistic">
						  <div class="value"><?php echo $host_num ?> / <?php echo $url_num ?></div>
						  <div class="label">總掃描主機 / 網站數</div>
					  </div>
					</center>
				</div>
			</div>
		</div>
		<div style="clear: both;">&nbsp;</div>
	</div><!-- end #content -->
</div> <!--end #page-->
<?php } 
function load_info_client(){
	$db = Database::get();
	$sql = "SELECT COUNT(*) AS total_num,SUM(ad) AS ad_num,SUM(gcb) AS gcb_num,SUM(wsus) AS wsus_num,SUM(antivirus) AS antivirus_num FROM drip_client_list";
	$device_num = $db->execute($sql)[0];
	$total_num = $device_num['total_num'];
	$ad_num = $device_num['ad_num'];
	$gcb_num = $device_num['gcb_num'];
	$wsus_num = $device_num['wsus_num'];
	$antivirus_num = $device_num['antivirus_num'];
	$total_rate = round($total_num/$total_num*100,2)."%"; 
	$ad_rate = round($ad_num/$total_num*100,2)."%"; 
	$gcb_rate = round($gcb_num/$total_num*100,2)."%"; 
	$wsus_rate = round($wsus_num/$total_num*100,2)."%"; 
	$antivirus_rate = round($antivirus_num/$total_num*100,2)."%"; 
	
	$table = "ad_comupter_list"; // 設定你想查詢資料的資料表
	$db->query($table, $condition = "1", $order_by = "1", $fields = "*", $limit = "");
	$ad_num = $db->getLastNumRows();
	
	$sql = "SELECT COUNT(ID) as total_count,SUM(CASE WHEN GsAll_2 = GsAll_1 THEN 1 ELSE 0 END) as pass_count FROM gcb_client_list";
	$gcb = $db->execute($sql)[0];
	$total_count = $gcb['total_count'];
	$pass_count = $gcb['pass_count'];
	$total_rate = ($total_count != 0) ? round($total_count/$total_count*100,2)."%" : 0; 
	$pass_rate = ($total_count != 0) ? round($pass_count/$total_count*100,2)."%" : 0; 
	
	$sql = "SELECT COUNT(TargetID) as total_count,SUM(CASE WHEN Failed LIKE '0' THEN 1 ELSE 0 END) as pass_count FROM wsus_computer_status";
	$wsus = $db->execute($sql)[0];
	$total_num = $wsus['total_count'];
	$pass_num = $wsus['pass_count'];
	$table = "wsus_computer_status"; // 設定你想查詢資料的資料表
	$db->query($table, $condition = "LastSyncTime > ADDDATE(NOW(), INTERVAL -1 WEEK)", $order_by = "1", $fields = "*", $limit = "");
	$sync_num = $db->getLastNumRows();
	$total_rate = round($total_num/$total_num*100,2)."%"; 
	$pass_rate = round($pass_num/$total_num*100,2)."%"; 
	$sync_rate = round($sync_num/$total_num*100,2)."%"; 
	
	$table = "antivirus_client_list"; // 設定你想查詢資料的資料表
	$db->query($table, $condition = "1", $order_by = "1", $fields = "*", $limit = "");
	$total_antivirus_num = $db->getLastNumRows();
	$db->query($table, $condition = "DLPState IN ('已停止','需要重新啟動','執行')", $order_by = "1", $fields = "*", $limit = "");
	$dlp_num = $db->getLastNumRows();
	$total_antivirus_rate = round($total_antivirus_num/$total_antivirus_num*100,2)."%"; 
	$dlp_rate = round($dlp_num/$total_antivirus_num*100,2)."%"; 
	$client_antivirus_num = $antivirus_num;
	$server_antivirus_num = $total_antivirus_num - $client_antivirus_num;
	$client_antivirus_rate = round($client_antivirus_num/$total_antivirus_num*100,2)."%"; 
	$server_antivirus_rate = round($server_antivirus_num/$total_antivirus_num*100,2)."%"; 
?>	
<div id="page" class="container">
	<div id="content">
		<div class="sub-content show">
			<div class="post">
				<div class="post_title">用戶端資安總表</div>
					<div class="post_cell">
					<div class="post_table">
					<table>
					<tr>
						<th>項目</th>
						<th>佈署率 | 數量</th>
					</tr>
					<tr>
						<td>用戶端總數</td>
						<td>
						<div class="ui teal progress yckuo" data-percent="<?php echo $total_rate?>" data-total="100" id="example1">
						  <div class="bar"><div class="progress"></div></div>
						  <div class="label"><?php echo $total_num ?></div>
						</div>
						</td>
					</tr>
					<tr>
						<td>ad安裝數</td>
						<td>
						<div class="ui teal progress yckuo" data-percent="<?php echo $ad_rate?>" data-total="100" id="example1">
						  <div class="bar"><div class="progress"></div></div>
						  <div class="label"><?php echo $ad_num ?></div>
						</div>
						</td>
					</tr>
					<tr>
						<td>gcb安裝數</td>
						<td>
						<div class="ui teal progress yckuo" data-percent="<?php echo $gcb_rate?>" data-total="100" id="example1">
						  <div class="bar"><div class="progress"></div></div>
						  <div class="label"><?php echo $gcb_num ?></div>
						</div>
						</td>
					</tr>
					<tr>
						<td>wsus安裝數</td>
						<td>
						<div class="ui teal progress yckuo" data-percent="<?php echo $wsus_rate?>" data-total="100" id="example1">
						  <div class="bar"><div class="progress"></div></div>
						  <div class="label"><?php echo $wsus_num ?></div>
						</div>
						</td>
					</tr>
					<tr>
						<td>antivirus安裝數</td>
						<td>
						<div class="ui teal progress yckuo" data-percent="<?php echo $antivirus_rate?>" data-total="100" id="example1">
						  <div class="bar"><div class="progress"></div></div>
						  <div class="label"><?php echo $antivirus_num ?></div>
						</div>
						</td>
					</tr>
					</table>
					</div>
				</div>
			</div> 
			<div class="post">
				<div class="post_title">網段使用IP統計圖</div>
				<div class="post_cell">
					<div id="chartE-1" class="chart"></div>	
				</div>
			</div> 
			<div class="post">
				<div class="post_title">AD統計</div>
				<div class="post_cell">
					<center>
						<div class="ui statistic">
						  <div class="value"><?php echo $ad_num ?>  </div>
						  <div class="label">電腦導入數</div>
						</div>
					</center>
			    </div>		
			</div>
			<div class="post">
				<div class="post_title">GCB總通過率</div>
				<div class="post_cell">
					<center>
						<div class="ui statistic">
						  <div class="value"><?php echo $pass_count." / ".$total_count ?>  </div>
						  <div class="label">通過數 / 總安裝數</div>
						</div>
					</center>
					<div id="chartE-2" class="chart"></div>	
				</div>
			</div>
			<div class="post">
				<div class="post_title">GCB作業系統統計圖</div>
				<div class="post_cell">
					<div id="chartE-3" class="chart"></div>	
			    </div>		
			</div>
			<div class="post">
				<div class="post_title">WSUS總通過率</div>
					<div class="post_cell">
					<div class="post_table">
					<table>
					<tr>
						<th>項目</th>
						<th>數值</th>
						<th>百分比</th>
					</tr>
					<tr>
						<td>用戶端總數</td>
						<td><?php echo $total_num ?></td>
						<td><?php echo $total_rate ?></td>
					</tr>
					<tr>
						<td>安裝成功數</td>
						<td><?php echo $pass_num ?></td>
						<td><?php echo $pass_rate ?></td>
					</tr>
					<tr>
						<td>1周內同步成功數</td>
						<td><?php echo $sync_num ?></td>
						<td><?php echo $sync_rate ?></td>
					</tr>
					</table>
					</div>
				</div>
			</div>
			<div class="post">
				<div class="post_title">AntiVirus總通過率</div>
					<div class="post_cell">
					<div class="post_table">
					<table>
					<tr>
						<th>項目</th>
						<th>數值</th>
						<th>百分比</th>
					</tr>
					<tr>
						<td>用戶端總數</td>
						<td><?php echo $total_antivirus_num ?></td>
						<td><?php echo $total_antivirus_rate ?></td>
					</tr>
					<tr>
						<td>DLP安裝成功數</td>
						<td><?php echo $dlp_num ?></td>
						<td><?php echo $dlp_rate ?></td>
					</tr>
					<tr>
						<td>Client安裝數</td>
						<td><?php echo $client_antivirus_num ?></td>
						<td><?php echo $client_antivirus_rate ?></td>
					</tr>
					<tr>
						<td>Server安裝數</td>
						<td><?php echo $server_antivirus_num ?></td>
						<td><?php echo $server_antivirus_rate ?></td>
					</tr>
					</table>
					</div>
				</div>
			</div>
		</div>
		<div style="clear: both;">&nbsp;</div>
	</div><!-- end #content -->
</div> <!--end #page-->
<?php } 
function load_info_network(){
?>	
<div id="page" class="container">
	<div id="content">
		<div class="sub-content show">
			<div class="post">
				<div class="post_title">Top 10 對外應用程式</div>
				<div class="post_cell">
					<div id="chartF" class="chart"></div>	
			    </div>		
			</div>
			<div class="post">
				<div class="post_title">Top 10 攻擊方式</div>
				<div class="post_cell">
					<div id="chartF-2" class="chart"></div>	
			    </div>		
			</div>
			<div class="post">
				<div class="post_title">Top 10 被阻擋應用程式</div>
				<div class="post_cell">
					<div id="chartF-3" class="chart"></div>	
			    </div>		
			</div>
			<div class="post">
				<div class="post_title">威脅日誌(最近10筆)</div>
				<div class="post_cell">
					<table class="ui very basic table">
					<?php 
						require_once("ajax/paloalto_api.php");
						require_once("ajax/paloalto_config.inc.php");
						$pa = new paloalto\api\PaloaltoAPI($host, $username, $password);
						$res = $pa->GetLogList($log_type = 'threat', $dir = 'backward', $nlogs = 10, $skip = 0, $query ='');
						$xml = simplexml_load_string($res) or die("Error: Cannot create object");
						$job = $xml->result->job;
						$xml_type = "op";
						$cmd = "<show><query><result><id>".$job."</id></result></query></show>";
						$res = $pa->GetXmlCmdResponse($xml_type, $cmd);
						$xml = simplexml_load_string($res) or die("Error: Cannot create object");
						$count = 0;
						echo "<thead>";	
						echo "<tr>";
							echo "<th>接收時間</th>";
							echo "<th>名稱</th>";
							echo "<th>類型</th>";
							echo "<th>來源IP</th>";
							echo "<th>目的IP</th>";
							echo "<th>目的port</th>";
							echo "<th>應用程式</th>";
						echo "</tr>";
						echo "</thead>";	
						echo "<tbody>";	
						foreach($xml->result->log->logs->entry as $log){
							echo "<tr>";
								echo "<td>".$log->receive_time."</td>";
                        		echo "<td>".$log->threatid."</td>";
								echo "<td>".$log->subtype."</td>";
                        		echo "<td>".$log->src."</td>";
								echo "<td>".$log->dst."</td>";
								echo "<td>".$log->dport."</td>";
								echo "<td>".$log->app."</td>";
							echo "</tr>";
						}
						echo "</tbody>";
						echo "</table>";
					?>
					<div class="see_more" style="text-align:right">
						<a href="/query/network/">See More...</a>
					</div>
				</div>
			</div>
			<div class="post">
				<div class="post_title">Top 10 目的地國家</div>
				<div class="post_cell">
					<table class="ui very basic single line table">
					<tr>
						<th>目的地國家</th>
						<th>位元組</th>
						<th>同時連線數</th>
					</tr>
					<?php
					$report_type = 'predefined';
					$report_name = 'top-destination-countries';	
					$res = $pa->GetReportList($report_type, $report_name);
					$xml = simplexml_load_string($res) or die("Error: Cannot create object");
					$max_count = 10;
					$max_bytes = 0;
					$max_sessions = 0;
					$count = 0;
					foreach($xml->result->entry as $log){
						if($count >= $max_count){
							break;
						}elseif($count == 0){
							$max_sessions = $log->sessions;
						}	
						if( ($log->bytes - $max_bytes) > 0){
							$max_bytes = $log->bytes;
						}
						$count = $count + 1;
					}
					$count = 0;
					foreach($xml->result->entry as $log){
						if($count >= $max_count){
							break;
						}
						$bytes_ratio = round($log->bytes*100/$max_bytes,0);
						$sessions_ratio = round($log->sessions*100/$max_sessions,0);
						$bytes_ratio = ($bytes_ratio != 0)? $bytes_ratio : 1;
						$sessions_ratio = ($sessions_ratio != 0)? $sessions_ratio : 1;
						$Gbytes = round($log->bytes/1024/1024/1024,1);
						$Ksessions = round($log->sessions/1000,1);
						echo "<tr>";
							echo "<td>".$log->dstloc."</td>";
							echo "<td>";
								echo "<div style='width:".$bytes_ratio."%;background:#78838c'>&nbsp</div>";	
								echo $Gbytes."G";
							echo "</td>";
							echo "<td>";
								echo "<div style='width:".$sessions_ratio."%;background:#78838c'>&nbsp</div>";	
								echo $Ksessions."k";
							echo "</td>";
						echo "</tr>";
						$count = $count + 1;
					}
					?>
					</table>
			    </div>		
			</div>
			<div class="post">
				<div class="post_title">市府網段區隔</div>
				<div class="post_cell">
					<img class="image" src="/images/network.png">
				</div>
			</div>
		<div style="clear: both;">&nbsp;</div>
	</div><!-- end #content -->
<?php } ?>	
		
		
