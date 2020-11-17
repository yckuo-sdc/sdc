<!--info_enews-->
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
						<?php foreach($last_10_events as $event) {  ?>
							<tr>
								<td><?=date_format(new DateTime($event['OccurrenceTime']),'Y-m-d')?></td>
								<td><?=$event['Status']?></td>
                        		<td><?=$event['EventTypeName']?></td>
								<td><?=$event['Location']?></td>
								<td><?=$event['IP']?></td>
								<td><?=$event['AgencyName']?></td>
								<td><?=$event['DeviceOwnerName']?></td>
							</tr>
						<?php } ?>
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
			<div class="post">
				<div class="post_title">市府拋送SOC設備</div>
				<div class="post_cell">
					<img class="image" src="/images/soc.png">
				</div>
			</div>
		</div>
		<div style="clear: both;">&nbsp;</div>
	</div><!-- end #content -->
</div> <!--end #page-->
