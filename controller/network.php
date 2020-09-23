<!--query-->
<?php 
if(isset($_GET['subpage'])) $subpage = $_GET['subpage'];
else						$subpage = 'search';

switch($subpage){
	case 'search': load_network_search(); break;
	case 'malware': load_network_malware(); break;
	case 'allowlist': load_network_allowlist(); break;
}

function load_network_search(){
	require 'libraries/PaloAltoAPI.php';
	$pa = new PaloAltoAPI();
	$db = Database::get();
	$table = "maliciousSite"; // 設定你想查詢資料的資料表
	$db->query($table, $condition = "Type LIKE 'domain'", $order_by ="1", $fields ="*", $limit = "");
	$total_dn_query_num = $db->getLastNumRows();
	$condition = "Type LIKE 'domain' and Action LIKE 'allow'";
	$order_by = "1";
	$DNs = $db->query($table, $condition, $order_by, $fields = "*", $limit = "");
	$allow_dn_query_num = $db->getLastNumRows();
	$last_dn_update = $db->query($table, $condition = "Type LIKE 'domain'", $order_by ="1", $fields ="*", $limit = "LIMIT 1")[0]['LastUpdate'];

	$db->query($table, $condition = "Type LIKE 'ip'", $order_by ="1", $fields ="*", $limit = "");
	$total_ip_query_num = $db->getLastNumRows();
	$condition = "Type LIKE 'ip' and Action LIKE 'allow'";
	$order_by = "1";
	$IPs = $db->query($table, $condition, $order_by, $fields = "*", $limit = "");
	$allow_ip_query_num = $db->getLastNumRows();
	$last_ip_update = $db->query($table, $condition = "Type LIKE 'ip'", $order_by ="1", $fields ="*", $limit = "LIMIT 1")[0]['LastUpdate'];
?>	
<div id="page" class="container">
<div id="content">
		<div class="sub-content show">
			<div class="post network">
				<div class="post_title">網路流量日誌(IPS)</div>
				<div class="post_cell">
					<div class="ui top attached tabular menu">
						<a class="active item">Yonghua</a>
						<a class="item">Minjhih</a>
						<a class="item">IDC</a>
						<a class="item">IntraYonghua</a>
					</div>
					<div class="ui bottom attached segment">
						<div class="tab-content yonghua show">
						<div class="query_content"></div>
						<form class="ui form" action="javascript:void(0)">
						<div class="fields">
							<div class="field">
								<label>種類</label>
								<select name="keyword" id="keyword" class="ui fluid dropdown" required>
								<option value="addr.src"  selected>來源IP</option>
								<option value="addr.dst" >目的IP</option>
								<option value="port.dst" >目的port</option>
								<option value="rule" >規則</option>
								<option value="app" >應用程式</option>
								<option value="action" >動作</option>
								</select>
							</div>
							<div class="field">
								<label>運算</label>
								<select name="operator" id="operator" class="ui fluid dropdown" required>
								<option value="="  selected>=</option>
								<option value="!=" >!=</option>
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
								<button id="show_all_btn" class="ui button" onclick="window.location.href='/network/search/'">顯示全部</button>
							</div>
						</div>
						</form>
						<div class="ui centered inline loader"></div>
						<div class="record_content"></div>
						</div> <!-- end of .tabular-->
						<div class="tab-content minjhih">
						<div class="query_content"></div>
						<form class="ui form" action="javascript:void(0)">
						<div class="fields">
							<div class="field">
								<label>種類</label>
								<select name="keyword" id="keyword" class="ui fluid dropdown" required>
								<option value="addr.src"  selected>來源IP</option>
								<option value="addr.dst" >目的IP</option>
								<option value="port.dst" >目的port</option>
								<option value="rule" >規則</option>
								<option value="app" >應用程式</option>
								<option value="action" >動作</option>
								</select>
							</div>
							<div class="field">
								<label>運算</label>
								<select name="operator" id="operator" class="ui fluid dropdown" required>
								<option value="="  selected>=</option>
								<option value="!=" >!=</option>
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
								<button id="show_all_btn" class="ui button" onclick="window.location.href='/network/search/'">顯示全部</button>
							</div>
						</div>
						</form>
						<div class="ui centered inline loader"></div>
						<div class="record_content"></div>
						</div> <!-- end of .tabular-->
						<div class="tab-content idc">
						<div class="query_content"></div>
						<form class="ui form" action="javascript:void(0)">
						<div class="fields">
							<div class="field">
								<label>種類</label>
								<select name="keyword" id="keyword" class="ui fluid dropdown" required>
								<option value="addr.src"  selected>來源IP</option>
								<option value="addr.dst" >目的IP</option>
								<option value="port.dst" >目的port</option>
								<option value="rule" >規則</option>
								<option value="app" >應用程式</option>
								<option value="action" >動作</option>
								</select>
							</div>
							<div class="field">
								<label>運算</label>
								<select name="operator" id="operator" class="ui fluid dropdown" required>
								<option value="="  selected>=</option>
								<option value="!=" >!=</option>
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
								<button id="show_all_btn" class="ui button" onclick="window.location.href='/network/search/'">顯示全部</button>
							</div>
						</div>
						</form>
						<div class="ui centered inline loader"></div>
						<div class="record_content"></div>
						</div> <!-- end of .tabular-->
						<div class="tab-content intrayonghua">
						<div class="query_content"></div>
						<form class="ui form" action="javascript:void(0)">
						<div class="fields">
							<div class="field">
								<label>種類</label>
								<select name="keyword" id="keyword" class="ui fluid dropdown" required>
								<option value="addr.src"  selected>來源IP</option>
								<option value="addr.dst">目的IP</option>
								<option value="port.dst">目的port</option>
								<option value="rule">規則</option>
								<option value="app">應用程式</option>
								<option value="action">動作</option>
								</select>
							</div>
							<div class="field">
								<label>運算</label>
								<select name="operator" id="operator" class="ui fluid dropdown" required>
								<option value="="  selected>=</option>
								<option value="!=" >!=</option>
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
								<button id="show_all_btn" class="ui button" onclick="window.location.href='/network/search/'">顯示全部</button>
							</div>
						</div>
						</form>
						<div class="ui centered inline loader"></div>
						<div class="record_content"></div>
						</div> <!-- end of .tabular-->
					</div> <!-- end of .attached.segment-->
				</div>
			</div>
		</div>
		<div style="clear: both;"></div>
	</div>
<?php } 
function load_network_malware(){
	require 'libraries/PaloAltoAPI.php';
	$pa = new PaloAltoAPI();
	$db = Database::get();
	$table = "maliciousSite"; // 設定你想查詢資料的資料表
	$db->query($table, $condition = "Type LIKE 'domain'", $order_by ="1", $fields ="*", $limit = "");
	$total_dn_query_num = $db->getLastNumRows();
	$condition = "Type LIKE 'domain' and Action LIKE 'allow'";
	$order_by = "1";
	$DNs = $db->query($table, $condition, $order_by, $fields = "*", $limit = "");
	$allow_dn_query_num = $db->getLastNumRows();
	$last_dn_update = $db->query($table, $condition = "Type LIKE 'domain'", $order_by ="1", $fields ="*", $limit = "LIMIT 1")[0]['LastUpdate'];

	$db->query($table, $condition = "Type LIKE 'ip'", $order_by ="1", $fields ="*", $limit = "");
	$total_ip_query_num = $db->getLastNumRows();
	$condition = "Type LIKE 'ip' and Action LIKE 'allow'";
	$order_by = "1";
	$IPs = $db->query($table, $condition, $order_by, $fields = "*", $limit = "");
	$allow_ip_query_num = $db->getLastNumRows();
	$last_ip_update = $db->query($table, $condition = "Type LIKE 'ip'", $order_by ="1", $fields ="*", $limit = "LIMIT 1")[0]['LastUpdate'];
?>	
<div id="page" class="container">
<div id="content">
		<div class="sub-content show">
			<div class="post isac">
				<div class="post_title">動態阻擋清單(Tainan-ISAC)</div>
				<div class="post_cell">
				<?php
				echo "<div class='dynamiclists'>";
					echo "<h4 class='ui header'>惡意中繼站阻擋結果(Domain)</h4>";
					echo "<p>嘗試連線".$total_dn_query_num."筆惡意中繼站，共有".$allow_dn_query_num."筆惡意中繼站未阻擋！".$last_dn_update."</p>";
					if($allow_dn_query_num != 0){
						echo "<div class='ui four stackable cards'>";
						foreach($DNs as $DN){
							echo "<div class='card'>";
							echo "<div class='content'>";
								echo "<div class='description'>".$DN['Name']."</div>";
							echo "</div>";
							echo "</div>";
						}		
						echo "</div>";
					}
					echo "<div class='ui divider'></div>";
				echo "</div>";
					
				echo "<div class='dynamiclists'>";
					echo "<h4 class='ui header'>惡意中繼站阻擋結果(IP)</h4>";
					echo "<p>嘗試連線".$total_ip_query_num."筆惡意中繼站，共有".$allow_ip_query_num."筆惡意中繼站未阻擋！".$last_ip_update."</p>";
					if($allow_ip_query_num != 0){
						echo "<div class='ui four stackable cards'>";
						foreach($IPs as $IP){
							echo "<div class='card'>";
							echo "<div class='content'>";
								echo "<div class='description'>".$IP['Name']."</div>";
							echo "</div>";
							echo "</div>";
						}		
						echo "</div>";
					}
					echo "<div class='ui divider'></div>";
				echo "</div>";

				$object_type = 'ExternalDynamicLists'; 
				$name = '';
				$res = $pa->GetObjectList($object_type, $name);
				$res = json_decode($res);
				$entries = $res->result->entry;
				foreach($entries as $entry){
					$name = $entry->{'@name'};
					$type = $entry->{'type'};
					$num_records = 1;
					$show_records = 10;
					foreach($type as $type_name => $val){
						$type = $type_name;
					}
					$xml_type = "op";
					$cmd = "<request><system><external-list><show><type><".$type."><name>".$name."</name><num-records>".$num_records."</num-records></".$type."></type></show></external-list></system></request>";
					$record = $pa->GetXmlCmdResponse($xml_type, $cmd);
					$xml = simplexml_load_string($record) or die("Error: Cannot create object");
					if($xml['status'] != 'success'){
						echo "很抱歉，該分類目前沒有資料！";
						break ;
					}
					$total_count = $xml->result['total-count'];
					$xml_type = "op";
					$cmd = "<request><system><external-list><show><type><".$type."><name>".$name."</name><num-records>".$total_count."</num-records></".$type."></type></show></external-list></system></request>";
					$record = $pa->GetXmlCmdResponse($xml_type, $cmd);
					$xml = simplexml_load_string($record) or die("Error: Cannot create object");
					$members = $xml->result->{'external-list'}->{'valid-members'}->member; 
					$i = 0;
					echo "<div class='dynamiclists'>";
					echo "<h4 class='ui header'><i class='icon caret right'></i>".$name."</h4>";
					echo "<p>該分類共搜尋到".$total_count."筆資料！</p>";
					echo "<div class='ui four stackable cards'>";
					foreach($members as $member){
						if($i < $show_records ){
							echo "<div class='card'>";
						}else{
							echo "<div class='remaining card'>";
						}		
						echo "<div class='content'>";
							echo "<div class='description'>".str_replace(".", ".", $member)."</div>";
						echo "</div>";
						echo "</div>";
						$i++;
					}
					echo "</div>";
					echo "<div class='ui divider'></div>";
					echo "</div>";
				}
				?>
				</div>
			</div>
		</div>
		<div style="clear: both;"></div>
	</div>
<?php } 
function load_network_allowlist(){
	require 'libraries/PaloAltoAPI.php';
	$pa = new PaloAltoAPI();
?>	
<div id="page" class="container">
<div id="content">
		<div class="sub-content show">
			<div class="post">
				<div class="post_title">Client應用程式核可清單</div>
				<div class="post_cell">
				<?php
				$object_type = 'ApplicationGroups'; 
				$name = '';
				$res = $pa->GetObjectList($object_type, $name);
				$res = json_decode($res);
				if($res->{'@status'} != 'success'){
					echo "很抱歉，該分類目前沒有資料！";
					return ;
				}
				echo "<table class='ui celled table'>";
				echo "<thead>";
					echo "<tr>";
						echo "<th>群組名稱</th>";
						echo "<th>成員</th>";
						echo "<th>應用程式</th>";
					echo "</tr>";
				echo "</thead>";
				echo "<tbody>";
				foreach($res->result->entry as $key => $entry){
					$size = count($entry->members->member);
					$name = $entry->{'@name'};
					foreach($entry->members->member as $key => $val){
						echo "<tr>";
						if($key == 0){
							echo "<td rowspan=".$size.">".$name."</td>";
							echo "<td rowspan=".$size.">".$size."</td>";
							echo "<td>".$val."</td>";	
						}else{
							echo "<td>".$val."</td>";	
						}
						echo "</tr>";
					}
				}
				echo "</tbody>";
				echo "</table>";
				?>
				</div>
			</div>
		</div>
		<div style="clear: both;"></div>
	</div>
<?php } 
?>	
<!-- end #content -->
</div> <!--end #page-->


