<!--tool_nmap-->
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
							<table class='ui celled table'>
							<thead>	
							<tr>
								<th>系統名稱</th>
								<th>IP</th>
								<th>協定</th>
								<th>Port</th>
								<th>服務</th>
								<th>狀態</th>
								<th>Nmap結果</th>
							</tr>
							</thead>
							<tbody>	
							<?php 
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
							?>
							</tbody>
							</table>
						</div> <!-- end .tabular-->
					</div> <!-- end .attached.segment-->
				</div> <!-- end .post_cell-->
			</div> <!-- end .post-->
		</div> <!-- end .sub-content-->
		<div style="clear: both;">&nbsp;</div>
	</div><!-- end #content -->
</div> <!--end #page-->
