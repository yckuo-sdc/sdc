<!--tool-->
<?php 
if(isset($_GET['subpage'])) $subpage = $_GET['subpage'];
else						$subpage = 'nmap';
switch($subpage){
	case 'nmap': load_tool_nmap(); 		break;
	case 'ldap': load_tool_ldap(); 		break;
	case 'hydra': load_tool_hydra(); 		break;
}
function load_tool_nmap(){
	$db = Database::get();
	$table = "application_system"; // 設定你想查詢資料的資料表
	$condition = "1 = ?";
	$systems = $db->query($table, $condition, $order_by = "1", $fields = "*", $limit = "", [1]);
?>
<div id="page" class="container">
	<div id="content">
		<div class="sub-content show">
			<div class="post nmap">
				<div class="post_title">Nmap</div>
				<div class="post_cell">
					<div class="ui top attached tabular menu">
						<a class="active item">Nmap</a>
						<a class="item">Portscan Result</a>
					</div>
					<div class="ui bottom attached segment">
						<div class="tab-content nmap show">
							<form class="ui form" action="javascript:void(0)">
								<div class="field">
									<label>Host(IP or Domain name)</label>
									<div class="ui input">
										<?php $target = "localhost vision.tainan.gov.tw 10.7.102.4";?>
										<input type="text" name="target" value="<?php echo $target;?>" placeholder="<?php echo $target;?>">
									</div>
								</div>
								<div class="field">
									<label>Nmap</label>
									<button type="submit" id="nmap_btn" class="ui button">Scan</button>
									<div class="ui centered inline loader"></div>
								</div>
								<div class="ui message">
									<div class="header"><a href='https://nmap.org/nsedoc/' target='_blank'>Nmap Scripts</a></div>
									<ul class="list">
									  <li>nmap -sV --script ssl-cert,ssl-enum-ciphers  -p 443,465,993,995 &lthost&gt</li>
									  <li>nmap -sV --script ssl-enum-ciphers,ssl-cert -p 443 &lthost&gt</li>
									</ul>
							  	</div>
							</form>
							<div class="record_content"></div>
						</div> <!-- end of .tabular-->
						<div class="tab-content portscan">
						 <?php //select data form database
							echo "<table class='ui celled table'>";
							echo "<thead>";	
							echo "<tr>";
								echo "<th>系統名稱</th>";
								echo "<th>IP</th>";
								echo "<th>協定</th>";
								echo "<th>Port</th>";
								echo "<th>服務</th>";
								echo "<th>狀態</th>";
								echo "<th>Nmap結果</th>";
							echo "</tr>";
							echo "</thead>";	
							echo "<tbody>";	
							foreach($systems as $system){
								$SID = $system['SID'];
								$Name = $system['Name'];
								$IP = $system['IP'];
								$URL = $system['URL'];
								$Scan_Result = $system['Scan_Result'];
								$table = "portscanResult"; // 設定你想查詢資料的資料表
								$condition = "ScanTime = (SELECT MAX(ScanTime) FROM portscanResult WHERE SID = :SID_1) AND SID = :SID_2";
								$ports = $db->query($table, $condition, $order_by = "1", $fields = "*", $limit = "", [':SID_1'=>$SID, ':SID_2'=>$SID]);
								$size = $db->getLastNumRows();
								if($size == 0 ){
									echo "<tr>";
										echo "<td><a href='".$URL."' target='_blank'>".$Name."</a></td>";
										echo "<td>".$IP."</td>";
										echo "<td></td>";
										echo "<td></td>";
										echo "<td></td>";
										echo "<td></td>";
										echo "<td>".$Scan_Result."</td>";
									echo "</tr>";
								}else{
									foreach ($ports as $key=>$port){
										if($key == 0){
											echo "<tr>";
												echo "<td rowspan=".$size."><a href='".$URL."' target='_blank'>".$Name."</a></td>";
												echo "<td rowspan=".$size.">".$IP."</td>";
												echo "<td rowspan=".$size.">tcp</td>";
												echo "<td>".$port['PortNumber']."</td>";
												echo "<td>".$port['Service']."</td>";
												echo "<td>".$port['Status']."</td>";
												echo "<td rowspan=".$size.">".$Scan_Result."</td>";
											echo "</tr>";
										}else{
											echo "<tr>";
												echo "<td>".$port['PortNumber']."</td>";
												echo "<td>".$port['Service']."</td>";
												echo "<td>".$port['Status']."</td>";
											echo "</tr>";
										}	
									}
								}
							}
							echo "</tbody>";
							echo "</table>";
						?>
						</div> <!-- end of .tabular-->
					</div> <!-- end of .attached.segment-->
				</div> <!-- end of .post_cell-->
			</div> <!-- end of .post-->
		</div> <!-- end of .sub-content-->
		<div style="clear: both;">&nbsp;</div>
	</div>
<?php }							
function load_tool_ldap(){
?>
<div id="page" class="container">
	<div id="content">
		<div class="sub-content show">
			<div class="post ldap">
				<div class="post_title">LDAP-Search</div>
				<div class="post_cell">
					<form class="ui form" action="javascript:void(0)">
					<!--<div class="fields">-->
						<div class="field">
							<label>CN(=Login account or PC name)</label>
							<div class="ui input">
								<?php $target = "yckuo";?>
								<input type="text" class="target" value="<?php echo $target;?>" placeholder="<?php echo $target;?>">
							</div>
						</div>
						<div class="field">
							<label>LDAP</label>
							<div class="two fields">
								<div class="field">
									<button type="submit" id="ldap_search_btn" class="ui button">Search</button>
								</div>
								<div class="field">
									<button id="ldap_newuser_btn" class="ui button">New User</button>
								</div>
							</div>
							<div class="ui centered inline loader"></div>
						</div>
					<!--</div>-->	
					</form>

				<div class="record_content"></div>
			</div> <!-- end of .post_cell-->
			</div>
			<div class="post ldap_computer_tree">
				<div class="post_title">AD-Computer tree</div>
				<div class="post_cell">
					<div class="ui centered inline loader"></div>
					<div class="ldap_tree_content"></div>
				</div>
			</div>
		</div>
		<div style="clear: both;">&nbsp;</div>
	</div>
<?php }	
function load_tool_hydra(){
?>
<div id="page" class="container">
	<div id="content">
		<div class="sub-content show">
			<div class="post hydra">
				<div class="post_title">Hydra Passowrd Cracker</div>
				<div class="post_cell">
				<form class="ui form" action="javascript:void(0)">
 				<!--<div class="fields">-->
			    	<div class="field">
						<label>Target(IP or Domain name)</label>
						<div class="ui input">
							<?php $target = "192.168.100.127";?>
							<input type="text" name="target" value="<?php echo $target;?>" placeholder="<?php echo $target;?>">
						</div>
					</div>
			    	<div class="field">
						<label>Protocol(ssh,rdp,ftp,smb,http-post-form,...)</label>
						<div class="ui input">
							<?php $target = "ssh";?>
							<input type="text" name="protocol" value="<?php echo $target;?>" placeholder="<?php echo $target;?>">
						</div>
					</div>
			    	<div class="field">
						<label>Account</label>
						<div class="ui input">
							<?php $target = "admin";?>
							<input type="text" name="account" value="<?php echo $target;?>" placeholder="<?php echo $target;?>">
						</div>
					</div>
			    	<div class="inline fields">
						<label>Password mode</label>
						<div class='field'>	
							<div class='ui radio checkbox'>
								<input type='radio' name='one_pwd_mode' value='no' onchange="hydra_pwd_mode('no')" tabindex='0' checked>
									<label>Top 100 pwds</label>
							</div>
						</div>
						<div class='field'>	
							<div class='ui radio checkbox'>
								<input type='radio' name='one_pwd_mode' value='yes' onchange="hydra_pwd_mode('yes')" tabindex='0'>
									<label>One pwd</label>
							</div>
						</div>
					</div>
					<div class="field">
						<label>One Pwd</label>
							<div class='ui input'>
								<input type='text' name='self_password' value='' placeholder='One Pwd' disabled>
							</div>
					</div>

					<div class="field">
						<label>Hydra</label>
						<button type="submit" id="hydra_btn" class="ui button">BruteForce</button>
						<div class="ui centered inline loader"></div>
					</div>
					
					</form>

					<div class="record_content"></div>
			
				</div>
			</div>
			<div class="post ldap">
				<div class="post_title">Modal Test</div>
				<div class="post_cell">
					<button type="input" id="modal_btn" class="ui button">Modal Show</button>
					<div class="ui modal">
					  <i class="close icon"></i>
					  <div class="header">
						Modal Title
					  </div>
					  <div class="content">
						  A description can appear on the right
					  </div>
					  <div class="actions">
						<div class="ui cancel button">Cancel</div>
						<div class="ui approve button">OK</div>
					  </div>
					</div>
				</div>
			</div>
		</div>
		<div style="clear: both;">&nbsp;</div>
	</div>
<?php } 
?>	
	
	<!-- end #content -->

</div> <!--end #page-->
