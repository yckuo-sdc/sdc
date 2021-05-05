<div id="page" class="container">
	<div id="content">
		<div class="sub-content show">
			<div class="post">
                <?=$route->createBreadcrumbs(' > ');?>
                <h2 class="ui dividing header">Network</h2>
				<div id="topApp_post" class="post_title">Top 對外應用程式</div>
				<div class="post_cell">
					<div id="topApp_chart" class="chart"></div>
			    </div>		
				<div id="topAttack_post" class="post_title">Top 10 攻擊方式</div>
				<div class="post_cell">
					<div id="topAttack_chart" class="chart"></div>	
			    </div>		
				<div id="topDeny_post" class="post_title">Top 10 被阻擋應用程式</div>
				<div class="post_cell">
					<div id="topDeny_chart" class="chart"></div>	
			    </div>		
				<div id="topThreat_post" class="post_title">威脅日誌(最近10筆)</div>
				<div class="post_cell">
					<table class="ui very basic table">
					<thead>	
					<tr>
						<th>接收時間</th>
						<th>名稱</th>
						<th>類型</th>
						<th>來源IP</th>
						<th>目的IP</th>
						<th>目的port</th>
						<th>應用程式</th>
					</tr>
					</thead>	
					<tbody>	
					<?php foreach($threat_data['logs'] as $log): ?>
						<tr>
							<td><?php echo $log->receive_time ?></td>
							<td><?php echo $log->threatid ?></td>
							<td><?php echo $log->subtype ?></td>
							<td><?php echo $log->src ?></td>
							<td><?php echo $log->dst ?></td>
							<td><?php echo $log->dport ?></td>
							<td><?php echo $log->app ?></td>
						</tr>
					<?php endforeach ?>
					</tbody>
					</table>
                    <div class="see_more" style="text-align:right">
						<a href="/network/search/">See More...</a>
					</div>
				</div>
				<div id="topCountry_post" class="post_title">Top 10 目的地國家</div>
				<div class="post_cell">
					<table class="ui very basic single line table">
					<tr>
						<th>目的地國家</th>
						<th>同時連線數</th>
						<th>位元組</th>
					</tr>
					<?php foreach($entries as $entry):
                        $bytes_ratio = round($entry['bytes'] / $max_bytes, 2)*100 ;
                        $sessions_ratio = round($entry['sessions'] / $max_sessions, 2)*100;
					?>
						<tr>
							<td><?=$entry['dstloc']?></td>
							<td>
								<div style='width:<?=$sessions_ratio?>%; background:#78838c'>&nbsp</div>
                                <?=formatNumbers($entry['sessions'])?>
							</td>
							<td>
								<div style='width:<?=$bytes_ratio?>%; background:#78838c'>&nbsp</div>
                                <?=formatBytes($entry['bytes'])?>
							</td>
						</tr>
					<?php endforeach ?>
					</table>
			    </div>		
				<div class="post_title">市府網段區隔</div>
				<div class="post_cell">
					<img class="image" src="/images/network.png">
				</div>
				<div class="post_title">ISAC平台更新防火牆範圍</div>
				<div class="post_cell">
					<object type="application/pdf" data="/upload/info/ISACFirewall.pdf" width="100%" height="700"></object>
				</div><!--end of .post_cell-->
			</div><!--end of .post-->
		</div><!--end of .sub-content-->
		<div style="clear: both;">&nbsp;</div>
	</div><!-- end #content -->
</div> <!--end #page-->
