<!--vul-->
<?php 
if(isset($_GET['subpage'])) $subpage = $_GET['subpage'];
else						$subpage = 'overview';

switch($subpage){
	case 'overview': load_vul_overview(); break;
	case 'search': load_vul_search(); break;
	case 'target': load_vul_target(); break;
	case 'retrieve': load_vul_retrieve(); break;
}

function load_vul_overview(){
?>
<div id="page" class="container">
<div id="content">
		<div class="sub-content show">
			<div class="post vul_overview">
				<div class="post_title">整體數據</div>
				<div class="ui centered inline loader"></div>
				<div class="ou_vs_content"></div>
			</div>
		</div>
		<div style="clear: both;">&nbsp;</div>
	</div><!-- end #content -->
<?php } 
function load_vul_search(){
	$db = Database::get();
	$table = "ip_and_url_scanResult"; // 設定你想查詢資料的資料表
	$condition = "1";
	$order_by = "scan_no DESC,ou DESC,system_name DESC,status DESC";
	$db->query($table, $condition, $order_by, $fields = "*", $limit = "");
	$last_num_rows = $db->getLastNumRows();
	
	$page = isset($_GET['page']) ? $_GET['page'] : 1; 
	$page_parm = getPaginationParameter($page, $last_num_rows);
	
	$limit = "limit ".($start = $page_parm['start']).",".($offset = $page_parm['offset']);	
	$vuls = $db->query($table, $condition, $order_by, $fields = "*", $limit);
?>	
<div id="page" class="container">
	<div id="content">
		<div class="sub-content show">
			<div class="post ip_and_url_scanResult">
				<div class="post_title">弱點查詢</div>
				<div class="post_cell">
					<form class="ui form" action="javascript:void(0)">
					<div class="query_content"></div>
					<div class="fields">
						<div class="field">
							<label>種類</label>
							<select name="keyword" id="keyword" class="ui fluid dropdown" required>
							<option value="ou" selected>單位</option>
							<option value="ip">IP</option>
							<option value="system_name">系統名稱</option>
							<option value="scan_no">掃描期別</option>
							<option value="severity">風險程度</option>
							<option value="all">全部</option>
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
							<div class="ui checkbox">
							  <input type="checkbox" name="status[]" tabindex="0" checked>
							  <label>逾期待處理</label>
							</div>
							<div class="ui checkbox">
							  <input type="checkbox" name="status[]" tabindex="0" checked>
							  <label>未逾期待處理</label>
							</div>
							<div class="ui checkbox">
							  <input type="checkbox" name="status[]" tabindex="0" checked>
							  <label>已修補</label>
							</div>
						</div>
						<div class="field">
							<button id="search_btn" name="search_btn" class="ui button">搜尋</button>
						</div>
						 <div class="field">
							<button id="show_all_btn" class="ui button" onclick="window.location.href='/vul/search/'">顯示全部</button>
						</div>
						 <div class="field">
							<button id="export2csv_btn" class="ui button" >匯出</button>
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
					foreach($vuls as $vul){	
						echo "<div class='item'>";
						echo "<div class='content'>";
							echo "<a>";
							echo "<span style='background:#f3c4c4'>".$vul['type']."</span>&nbsp&nbsp";
							echo $vul['flow_id']."&nbsp&nbsp";
							echo str_replace("/臺南市政府/","",$vul['ou'])."&nbsp&nbsp";
							echo "<span style='background:#fde087'>".$vul['system_name']."</span>&nbsp&nbsp";
							echo $vul['status']."&nbsp&nbsp";
							echo "<span style='background:#DDDDDD'>".$vul['vitem_name']."</span>&nbsp&nbsp";
							echo $vul['scan_no']."&nbsp&nbsp";
					
				
							echo "<i class='angle double down icon'></i>";
							echo "</a>";
							echo "<div class='description'>";
							 
								echo "<ol>";
								echo "<li>弱點類別:".$vul['type']."</li>";
								echo "<li>流水號:".$vul['flow_id']."</li>";
								echo "<li>弱點序號:".$vul['vitem_id']."</li>";
								echo "<li>弱點名稱:".$vul['vitem_name']."</li>";
								echo "<li>OID:".$vul['OID']."</li>";
								echo "<li>單位:".str_replace("/臺南市政府/","",$vul['ou'])."</li>";
								echo "<li>系統名稱:".$vul['system_name']."</li>";
								echo "<li>IP:".$vul['ip']."</li>";
								echo "<li>掃描日期:".date_format(new DateTime($vul['scan_date']),'Y-m-d')."</li>";
								echo "<li>管理員:".$vul['manager']."</li>";
								echo "<li>Email:".$vul['email']."</li>";
								echo "<li>影響網址:<a href='".$vul['affect_url']."' target='_blank'>".$vul['affect_url']."</a></li>";
								echo "<li>弱點詳細資訊:<a href='".$vul['url']."' target='_blank'>".$vul['url']."</a></li>";
								echo "<li>總類:".$vul['category']."</li>";
								echo "<li>風險程度:".$vul['severity']."</li>";
								echo "<li>弱點處理情形:".$vul['status']."</li>";
								echo "<li>掃描期別:".$vul['scan_no']."</li>";
								echo "</ol>";
							  
							echo "</div>";
							echo "</div>";
						echo "</div>";
					}
					echo "</div>";
					/* Create Pagination Element*/ 
					echo pagination($prev_page = $page_parm['prev_page'], $next_page = $page_parm['next_page'], $lower_bound = $page_parm['lower_bound'], $upper_bound = $page_parm['upper_bound'], $total_page = $page_parm['total_page'], "vul", "search", 0, $page, "");
					}
					?>
					</div> <!-- end of #record_content-->
				</div> <!-- end of post_cell -->
			</div>
		</div>
		<div style="clear: both;">&nbsp;</div>
	</div><!-- end #content -->
<?php } 
function load_vul_target(){
	$db = Database::get();
	$table = "scanTarget"; // 設定你想查詢資料的資料表
	$condition = "1";
	$order_by = "ou";
	$scanTarget = $db->query($table, $condition, $order_by, $fields = "*", $limit = "");
	$last_num_rows = $db->getLastNumRows();
	
	$sql = "SELECT COUNT(DISTINCT ip) as host_num FROM scanTarget";
	$host_num = $db->execute($sql)[0]['host_num'];
	$sql = "SELECT SUM(CASE domain WHEN '' THEN 0 ELSE LENGTH(domain)-LENGTH(REPLACE(domain,';',''))+1 END) AS url_num FROM scanTarget";
	$url_num = $db->execute($sql)[0]['url_num'];
?>	
<div id="page" class="container">
	<div id="content">
		<div class="sub-content show">
			<div class="post">
				<div class="post_title">掃描資產</div>
				<div class="post_cell">
				<?php 
				if($last_num_rows==0){
					echo "<p>查無此筆紀錄</p>";
				}else{
					echo "<p>共有".$last_num_rows."筆資產";
					echo "(含".$host_num."個掃描主機,".$url_num."筆掃描網站)！</p>";
				?>
				<table class="ui celled table">
				  <thead>
					<th>ou</th>
					<th>ip</th>
					<th>hostname</th>
					<th>system_name</th>
					<th>domain</th>
					<th>manager</th>
					<th>email</th>
				  </tr></thead>
				  <tbody>
				<?php
				foreach($scanTarget as $Target){
					$system_names = explode(";",$Target['system_name']);
					$domains = explode(";",$Target['domain']);
					$size = count($domains);
					for($i=0;$i<$size;$i++){
						if($i==0){
							echo "<tr>";
							echo "<td rowspan=".$size.">".str_replace('/臺南市政府/','',$Target['ou'])."</td>";
							echo "<td rowspan=".$size.">".$Target['ip']."</td>";
							echo "<td rowspan=".$size.">".$Target['hostname']."</td>";
							echo "<td>".$system_names[$i]."</td>";
							echo "<td><a href='".$domains[$i]."' target='_blank'>".$domains[$i]."</a></td>";
							echo "<td rowspan=".$size.">".$Target['manager']."</td>";
							echo "<td rowspan=".$size.">".$Target['email']."</td>";
							echo "</tr>";
						}else{
							echo "<tr>";
							echo "<td>".$system_names[$i]."</td>";
							echo "<td><a href='".$domains[$i]."' target='_blank'>".$domains[$i]."</a></td>";
							echo "</tr>";
						}
					}
				}
			}		
				?>
					  </tbody>
					</table>
				</div>
			</div>
		</div>
		<div style="clear: both;">&nbsp;</div>
	</div><!-- end #content -->
<?php } 
function load_vul_retrieve(){
	require 'config/ChtSecurityAPI.php';
	$db = Database::get();
	$sql = "SELECT api_status.*,api_list.name,api_list.data_type FROM api_status,api_list WHERE api_status.id IN(SELECT MAX(id) FROM api_status WHERE api_id IN(SELECT id FROM api_list WHERE class ='弱掃平台') AND api_status.status=200 AND api_status.api_id = api_list.id GROUP BY api_id)";
	$vul_api = $db->execute($sql);
	
	$key = ChtSecurityAPI::KEY;
	$nowTime = date("Y-m-d H:i:s");
	$host_type = "ipscanResult";
	$web_type = "urlscanResult";
	$target_type = "scanTarget";
	$host_auth = hash("sha256",$host_type.$key.$nowTime);
	$web_auth = hash("sha256",$web_type.$key.$nowTime);
	$target_auth = hash("sha256",$target_type.$key.$nowTime);
	$host_url = "https://tainan-vsms.chtsecurity.com/cgi-bin/api/portal.pl?type=".$host_type."&nowTime=".$nowTime."&auth=".$host_auth;
	$web_url = "https://tainan-vsms.chtsecurity.com/cgi-bin/api/portal.pl?type=".$web_type."&nowTime=".$nowTime."&auth=".$web_auth;
	$target_url	= "https://tainan-vsms.chtsecurity.com/cgi-bin/api/portal.pl?type=".$target_type."&nowTime=".$nowTime."&auth=".$target_auth;
?>	
<div id="page" class="container">
<div id="content">
		<div class="sub-content show">
			<div class="post">
				<div class="post_title">Retrieve VUL</div>
				<form class="ui form" action="javascript:void(0)">
					<div class="field">
						<label>nowTime</label>
						<?php echo $nowTime; ?>
					</div>
					<div class="field">
						<label>host json-url</label>
						<?php echo "<a href='".$host_url."' target='_blank'>".$host_url."</a>"; ?>
					</div>
					<div class="field">
						<label>web json-url</label>
						<?php echo "<a href='".$web_url."' target='_blank'>".$web_url."</a>"; ?>
					</div>
					<div class="field">
						<label>target json-url</label>
						<?php echo "<a href='".$target_url."' target='_blank'>".$target_url."</a>"; ?>
					</div>
				</form>
				<pre></pre>
				<?php 
				foreach($vul_api as $api){
					echo $api['name'].": update ".$api['data_number']." records on ".$api['last_update']."<br>";
				}
				?>
				<button id="vs_btn" class="ui button">Retrieve VS</button>
				<div class="ui centered inline loader"></div>
				<div class="retrieve_vs_info"></div>
			</div>
		</div>
		<div style="clear: both;">&nbsp;</div>
	</div><!-- end #content -->
<?php } ?>	
</div> <!--end #page-->


