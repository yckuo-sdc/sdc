<div id="page" class="container">
	<div id="content">
		<div class="sub-content show">
			<div class="post">
                <h2 class="ui dividing header">Client</h2>
				<div id="client_post" class="post_title">用戶端資安總表</div>
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
				<div id="drip_post" class="post_title">網段使用IP統計圖</div>
				<div class="post_cell">
					<div id="drip_chart" class="chart"></div>	
				</div>
				<div id="ad_post" class="post_title">AD</div>
				<div class="post_cell">
					<center>
						<div class="ui statistic">
						  <div class="value"><?php echo $ad_computer_num ?>  </div>
						  <div class="label">電腦導入數</div>
						</div>
					</center>
			    </div>		
				<div id="gcbPass_post" class="post_title">GCB通過率</div>
				<div class="post_cell">
					<center>
						<div class="ui statistic">
						  <div class="value"><?php echo $pass_count." / ".$total_count ?>  </div>
						  <div class="label">通過數 / 總安裝數</div>
						</div>
					</center>
					<div id="gcbPass_chart" class="chart"></div>	
				</div>
				<div id="gcbOS_post" class="post_title">GCB作業系統統計圖</div>
				<div class="post_cell">
					<div id="gcbOS_chart" class="chart"></div>	
			    </div>		
				<div id="wsusPass_post" class="post_title">WSUS</div>
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
						<td><?php echo $total_wsus_num ?></td>
						<td><?php echo $total_wsus_rate ?></td>
					</tr>
					<tr>
						<td>安裝成功數</td>
						<td><?php echo $pass_wsus_num ?></td>
						<td><?php echo $pass_wsus_rate ?></td>
					</tr>
					<tr>
						<td>1周內同步成功數</td>
						<td><?php echo $sync_wsus_num ?></td>
						<td><?php echo $sync_wsus_rate ?></td>
					</tr>
					</table>
					</div>
				</div>
				<div id="avPass_post" class="post_title">AntiVirus</div>
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
				</div><!--end of .post_cell-->
			</div> 
		</div><!--end of .sub-content-->
		<div style="clear: both;">&nbsp;</div>
	</div><!-- end #content -->
</div> <!--end #page-->
