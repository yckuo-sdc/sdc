<!--info_network-->
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
                    <?php if($data == 'false') {
                        echo "很抱歉，該分類目前沒有資料！";
                    } else{ ?>
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
					<?php foreach($data['logs'] as $log){ ?>
						<tr>
							<td><?php echo $log->receive_time ?></td>
							<td><?php echo $log->threatid ?></td>
							<td><?php echo $log->subtype ?></td>
							<td><?php echo $log->src ?></td>
							<td><?php echo $log->dst ?></td>
							<td><?php echo $log->dport ?></td>
							<td><?php echo $log->app ?></td>
						</tr>
					<?php } ?>
					</tbody>
					</table>
				    <?php } ?>
                    <div class="see_more" style="text-align:right">
						<a href="/network/search/">See More...</a>
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
                    $count = 0;
                    foreach($country_xml->result->entry as $log){
                    if($count >= $max_count){
                        break;
                    }
                    $bytes_ratio = round($log->bytes*100/$max_bytes,0);
                    $sessions_ratio = round($log->sessions*100/$max_sessions,0);
                    $bytes_ratio = ($bytes_ratio != 0)? $bytes_ratio : 1;
                    $sessions_ratio = ($sessions_ratio != 0)? $sessions_ratio : 1;
                    $Gbytes = round($log->bytes/1024/1024/1024,1);
                    $Ksessions = round($log->sessions/1000,1);
                    $count = $count + 1;
					?>
						<tr>
							<td><?php echo $log->dstloc ?></td>
							<td>
								<div style='width:<?php echo $bytes_ratio ?>%; background:#78838c'>&nbsp</div>
								<?php echo $Gbytes."G"; ?>
							</td>
							<td>
								<div style='width:<?php echo $sessions_ratio ?>%; background:#78838c'>&nbsp</div>
								<?php echo $Ksessions."k"; ?>
							</td>
						</tr>
					<?php } ?>
					</table>
			    </div>		
			</div>
			<div class="post">
				<div class="post_title">市府網段區隔</div>
				<div class="post_cell">
					<img class="image" src="/images/network.png">
				</div>
			</div>
			<div class="post">
				<div class="post_title">ISAC平台更新防火牆範圍</div>
				<div class="post_cell">
					<object type="application/pdf" data="/upload/info/ISACFirewall.pdf" width="100%" height="700"></object>
				</div>
			</div>
		<div style="clear: both;">&nbsp;</div>
	</div><!-- end #content -->
</div> <!--end #page-->
