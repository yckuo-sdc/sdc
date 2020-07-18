<!--vulnerability.php-->
<?php 
	if(isset($_GET['subpage'])) $subpage = $_GET['subpage'];
	else						$subpage = 1;
	switch($subpage){
		case 1:	load_vul_overview(); break;
		case 2:	load_vul_search(); break;
		case 3:	load_vul_target(); break;
		case 4:	load_vul_retrieve(); break;
	}
?>
<?php
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
?>	
<div id="page" class="container">
	<div id="content">
		<div class="sub-content show">
			<div class="post ip_and_url_scanResult">
				<div class="post_title">弱點查詢</div>
				<div class="post_cell">
					<form class="ui form" action="javascript:void(0)">
					<!-- query with mutiple conditions -->
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
							<button id="show_all_btn" class="ui button" onclick="window.location.href='index.php?mainpage=vul&subpage=2'">顯示全部</button>
						</div>
						 <div class="field">
							<button id="export2csv_btn" class="ui button" >匯出</button>
						</div>
					</div>
					</form>
					

					
					<div class="record_content">
					<?php //select data form database
						require("mysql_connect.inc.php");
						//------------pagination----------//
						$pages=" ";
						if (!isset($_GET['page'])){ 
							$pages = 1; 
						}else{
							$pages = $_GET['page']; 
						}
						
						#Create View
						/*CREATE VIEW V_ip_and_url_scanResult AS
						//SELECT '主機弱點' as type,vitem_id,OID,ou,status,ip,system_name,flow_id,scan_no,'null' as affect_url,manager,email,vitem_name,url,category,severity,scan_date,is_duplicated FROM ipscanResult UNION ALL SELECT '網站弱點' as type,vitem_id,OID,ou,status,ip,system_name,flow_id,scan_no,affect_url,manager,email,vitem_name,url,category,severity,scan_date,is_duplicated FROM urlscanResult ORDER BY scan_no desc,ou desc,system_name desc,status desc */
						//select row_number,and other field value
						$sql = "SELECT * FROM V_ip_and_url_scanResult";
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
								echo "<span style='background:#f3c4c4'>".$row['type']."</span>&nbsp&nbsp";
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
									echo "<li>弱點類別:".$row['type']."</li>";
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
									echo "<li>影響網址:<a href='".$row['affect_url']."' target='_blank'>".$row['affect_url']."</a></li>";
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
						/* Create Pagination Element*/ 
						echo pagination($prev_page,$next_page,$lower_bound,$upper_bound,$Totalpages,"vul",2,0,$pages,"");
						}
						$conn->close();
					?>
					</div> <!-- end of #record_content-->
				</div> <!-- end of post_cell -->
			</div>
		</div>
		<div style="clear: both;">&nbsp;</div>
	</div><!-- end #content -->
<?php } 
function load_vul_target(){
?>	
<div id="page" class="container">
	<div id="content">
		<div class="sub-content show">
			<div class="post">
				<div class="post_title">Scan Target</div>
				<div class="post_cell">
				<?php 
				require("mysql_connect.inc.php");
				//select row_number,and other field value
				$sql = "SELECT COUNT(DISTINCT ip) as host_num FROM scanTarget";
				$result = mysqli_query($conn,$sql);
				$row = @mysqli_fetch_assoc($result);
				$host_num = $row['host_num'];
				$sql = "SELECT SUM(CASE domain WHEN '' THEN 0 ELSE LENGTH(domain)-LENGTH(REPLACE(domain,';',''))+1 END) AS url_num FROM scanTarget";
				$result = mysqli_query($conn,$sql);
				$row = @mysqli_fetch_assoc($result);
				$url_num = $row['url_num'];
				$sql = "SELECT * FROM scanTarget order by ou";
				$result = mysqli_query($conn,$sql);
				$rowcount = mysqli_num_rows($result);
				$result = mysqli_query($conn,$sql);
				if($rowcount==0){
					echo "<p>查無此筆紀錄</p>";
				}else{
					echo "<p>共有".$rowcount."筆資產";
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
					while($row = mysqli_fetch_assoc($result)) {
						$system_names = explode(";",$row['system_name']);
						$domains = explode(";",$row['domain']);
						$size = count($domains);
						for($i=0;$i<$size;$i++){
							if($i==0){
								echo "<tr>";
								echo "<td rowspan=".$size.">".str_replace('/臺南市政府/','',$row['ou'])."</td>";
								echo "<td rowspan=".$size.">".$row['ip']."</td>";
								echo "<td rowspan=".$size.">".$row['hostname']."</td>";
								echo "<td>".$system_names[$i]."</td>";
								echo "<td><a href='".$domains[$i]."' target='_blank'>".$domains[$i]."</a></td>";
								echo "<td rowspan=".$size.">".$row['manager']."</td>";
								echo "<td rowspan=".$size.">".$row['email']."</td>";
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
				$conn->close();
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
?>	
<div id="page" class="container">
<div id="content">
		<div class="sub-content show">
			<div class="post">
				<div class="post_title">Retrieve VS</div>
				<form class="ui form" action="javascript:void(0)">
				<?php
					require("chtsecurity.inc.php");
					date_default_timezone_set("Asia/Taipei");
					$nowTime		= date("Y-m-d H:i:s");
					$host_type		= "ipscanResult";
					$web_type		= "urlscanResult";
					$target_type	= "scanTarget";
					$host_auth		= hash("sha256",$host_type.$chtsecurity_key.$nowTime);
					$web_auth		= hash("sha256",$web_type.$chtsecurity_key.$nowTime);
					$target_auth	= hash("sha256",$target_type.$chtsecurity_key.$nowTime);
					$host_url	= "https://tainan-vsms.chtsecurity.com/cgi-bin/api/portal.pl?type=".$host_type."&nowTime=".$nowTime."&auth=".$host_auth;
					$web_url	= "https://tainan-vsms.chtsecurity.com/cgi-bin/api/portal.pl?type=".$web_type."&nowTime=".$nowTime."&auth=".$web_auth;
					$target_url	= "https://tainan-vsms.chtsecurity.com/cgi-bin/api/portal.pl?type=".$target_type."&nowTime=".$nowTime."&auth=".$target_auth;
				?>
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
                //select data form database
                require("mysql_connect.inc.php");
				$sql = "SELECT api_status.*,api_list.name,api_list.data_type FROM api_status,api_list WHERE api_status.id IN(SELECT MAX(id) FROM api_status WHERE api_id IN(SELECT id FROM api_list WHERE class ='弱掃平台') AND api_status.status=200 AND api_status.api_id = api_list.id GROUP BY api_id)";
				$result = mysqli_query($conn,$sql);
				while($row = mysqli_fetch_assoc($result)) {
					echo $row['name'].": update ".$row['data_number']." records on ".$row['last_update']."<br>";
				}
				$conn->close();
				?>
				<button id="vs_btn" class="ui button">Retrieve VS</button>
				<div class="ui centered inline loader"></div>
				<div class="retrieve_vs_info"></div>

						
			</div>
		</div>
		<div style="clear: both;">&nbsp;</div>
	</div><!-- end #content -->
<?php } 
?>	
	
</div> <!--end #page-->


