<!--tool.php-->
<?php 
	if(isset($_GET['subpage'])) $subpage = $_GET['subpage'];
	else						$subpage = 'nmap';
	switch($subpage){
		case 'nmap': load_tool_nmap(); 		break;
		case 'ldap': load_tool_ldap(); 		break;
		case 'hydra': load_tool_hydra(); 		break;
	}
?>
<?php
function load_tool_nmap(){
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
									<label>Target(IP or Domain name)</label>
									<div class="ui input">
										<?php $target = "localhost vision.tainan.gov.tw 10.7.102.4";?>
										<input type="text" class="target" value="<?php echo $target;?>" placeholder="<?php echo $target;?>">
									</div>
								</div>
								<div class="field">
									<label>Nmap</label>
									<button id="nmap_btn" class="ui button">Scan</button>
									<div class="ui centered inline loader"></div>
								</div>
							</form>
							<div class="record_content"></div>
						</div> <!-- end of .tabular-->
						<div class="tab-content portscan">
						 <?php //select data form database
							require("mysql_connect.inc.php");
							 //select row_number,and other field value
							$sql = "SELECT * FROM application_system";
							$result = mysqli_query($conn,$sql);
							$num_total_entry = mysqli_num_rows($result);
							
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
							while($row = mysqli_fetch_assoc($result)) {
								$SID 			= $row['SID'];
								$Name 			= $row['Name'];
								$IP 			= $row['IP'];
								$URL 			= $row['URL'];
								$Scan_Result 	= $row['Scan_Result'];
								$sql 	= "SELECT * FROM portscanResult WHERE ScanTime = (SELECT MAX(ScanTime) FROM portscanResult WHERE SID =".$SID.") AND SID =".$SID;
								$res 	= mysqli_query($conn,$sql);
								$size 	= mysqli_num_rows($res);
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
									$ports = array();
									while($port = mysqli_fetch_assoc($res)) {
										$ports[] = $port;
									}
									foreach ($ports as $key=>$port){
										if($key == 0){
											echo "<tr>";
												echo "<td rowspan=".$size."><a href='".$URL."' target='_blank'>".$Name."</a></td>";
												echo "<td rowspan=".$size.">".$IP."</td>";
												echo "<td rowspan=".$size.">tcp</td>";
												//echo "<td>".$port['Protocol']."</td>";
												echo "<td>".$port['PortNumber']."</td>";
												echo "<td>".$port['Service']."</td>";
												echo "<td>".$port['Status']."</td>";
												echo "<td rowspan=".$size.">".$Scan_Result."</td>";
											echo "</tr>";
										}else{
											echo "<tr>";
												//echo "<td>".$port['Protocol']."</td>";
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
							$conn->close();
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
									<button id="ldap_search_btn" class="ui button">Search</button>
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
			<!--<div class="post">
				<div class="post_title">OU不同</div>
				<div class="post_cell">
					 <?php //select data form database
						/*require("mysql_connect.inc.php");
						 //select row_number,and other field value
						$sql = "SELECT * FROM gcb_client_list WHERE Owner NOT IN('-') AND OrgName NOT IN('-')";
						$result = mysqli_query($conn,$sql);
						$num_total_entry = mysqli_num_rows($result);
						
						echo "<table class='ui celled table'>";
						echo "<thead>";	
						echo "<tr>";
							echo "<th>Name</th>";
							echo "<th>OrgName</th>";
							echo "<th>UserName</th>";
							echo "<th>Owner</th>";
						echo "</tr>";
						echo "</thead>";	
						echo "<tbody>";	
						while($row = mysqli_fetch_assoc($result)) {
							echo "<tr>";
								echo "<td>".$row['Name']."</td>";
								echo "<td>".$row['OrgName']."</td>";
								echo "<td>".$row['UserName']."</td>";
								echo "<td>".$row['Owner']."</td>";
							echo "</tr>";
						}
						echo "</tbody>";
						echo "</table>";
						$conn->close();*/
					?>
				</div>
			</div>-->
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
				<form class="ui form" action="javascript:void(0)">
 				<!--<div class="fields">-->
			    	<div class="field">
						<label>Target(IP or Domain name)</label>
						<div class="ui input">
							<?php $target = "192.168.100.127";?>
							<input type="text" class="target" value="<?php echo $target;?>" placeholder="<?php echo $target;?>">
						</div>
					</div>
			    	<div class="field">
						<label>Protocol(ssh,rdp,ftp,smb,http-post-form,...)</label>
						<div class="ui input">
							<?php $target = "ssh";?>
							<input type="text" id="protocol" value="<?php echo $target;?>" placeholder="<?php echo $target;?>">
						</div>
					</div>
			    	<div class="field">
						<label>Account(pwd= Top 100 pwds)</label>
						<div class="ui input">
							<?php $target = "admin";?>
							<input type="text" id="account" value="<?php echo $target;?>" placeholder="<?php echo $target;?>">
						</div>
					</div>
			    	<div class="inline fields">
						<label>One pwd mode</label>
						<div class='field'>	
							<div class='ui radio checkbox'>
								<input type='radio' name='one_pwd_mode' value='no' onchange="uncheck('self_password')" tabindex='0' checked>

									<label>No</label>
							</div>
						</div>
						<div class='field'>	
							<div class='ui radio checkbox'>
								<input type='radio' name='one_pwd_mode' value='yes' onchange="check('self_password')" tabindex='0'>
									<label>Yes</label>
							</div>
						</div>
					</div>
					<div class="field">
						<label>One Pwd</label>
							<div class='ui input'>
								<input type='text' id='self_password' name='self_password' value='' placeholder='One Pwd' >
							</div>
					</div>

					<div class="field">
						<label>Hydra</label>
						<button id="hydra_btn" class="ui button">BruteForce</button>
						<div class="ui centered inline loader"></div>
					</div>
				<!--</div>-->	
				</form>

			<div class="record_content"></div>
			</div>
		</div>
		<div style="clear: both;">&nbsp;</div>
	</div>
<?php } 
?>	
	
	<!-- end #content -->

</div> <!--end #page-->
