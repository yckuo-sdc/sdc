<div id="page" class="container">
	<div id="content">
		<div class="sub-content show">
			<div class="post">
                <?=$route->createBreadcrumbs(' > ');?>
                <h2 class="ui dividing header">Dashboard</h2>
				<div class="ui grid stackable padded">
			  		<div class="four wide computer eight wide tablet sixteen wide mobile column">
						<div class="ui fluid card">
							<div class="content">
								<div class="ui right floated header red">
                                    <i class="list icon"></i>
								</div>
                                <div class="header">
                                    <div class="ui red header">
                                        <?=$dashboad_number_array['event']?>
                                    </div>
                                </div>
								<div class="meta">
							  	    資安事件	
								</div>
								<div class="description">
                                    資安事件自動 / 人工錄案, 依 SOP 執行控制措施
								</div>
							</div>
							 <div class="extra content">
                                <a href="/query/event/">				
                                    <div class="ui two buttons">
									    <div class="ui red button">More Info</div>
								    </div>
					            </a>		    
                             </div>
						 </div> 
					</div>
				  	<div class="four wide computer eight wide tablet sixteen wide mobile column">
						<div class="ui fluid card">
							<div class="content">
								<div class="ui right floated header green">
									<i class="bug icon"></i>
								</div>
								<div class="header">
									<div class="ui header green">
                                        <?=$dashboad_number_array['vul']?>
                                    </div>
								</div>
								<div class="meta"> 弱點數 </div>
								<div class="description">
                                    tccy-vsms 定期掃描主機或網站資產, 並請管理者依時程修補弱點
								</div>
							</div>
						  	<div class="extra content">
                                <a href="/info/vul/">				
                                    <div class="ui two buttons">
                                      <div class="ui green button">More Info</div>
                                    </div>
                                </a>
							</div> 
						</div>
					</div>
					<div class="four wide computer eight wide tablet sixteen wide mobile column">
						<div class="ui fluid card">
							<div class="content">
								<div class="ui right floated header teal">
                                    <i class="desktop icon"></i>
								</div>
								<div class="header">
									<div class="ui teal header">
                                        <?=$dashboad_number_array['client']?>
                                    </div>
								</div>
								<div class="meta"> 端點設備 </div>
								<div class="description">
                                    檢視 Endpoints, 統計防護軟體部屬情形
								</div>
							</div>
							<div class="extra content">
                                <a href="/info/client/">				
                                    <div class="ui two buttons">
                                        <div class="ui teal button">More Info</div>
                                    </div>
                                </a>
							</div>
						</div>
					</div>
					<div class="four wide computer eight wide tablet sixteen wide mobile column">
						<div class="ui fluid card">
							<div class="content">
								<div class="ui right floated header purple">
									<i class="user secret icon"></i>
								</div>
								<div class="header">
									<div class="ui purple header">
                                        <?=$dashboad_number_array['c2']?>
                                    </div>
								</div>
								<div class="meta"> 惡意中繼站 </div>
								<div class="description">
                                    彙整 C2 情資，並驗證資安設備阻擋結果
								</div>
							</div>
							<div class="extra content">
                                <a href="/network/malware/">				
                                    <div class="ui two buttons">
                                      <div class="ui purple button">More Info</div>
                                    </div>
                                </a>
							</div>
						</div>
					</div>
					<div class="four wide computer eight wide tablet sixteen wide mobile column">
						<div class="ui fluid card">
							<div class="content">
								<div class="ui right floated header blue">
                                    <i class="server icon"></i>
								</div>
								<div class="header">
									<div class="ui blue header">
                                        <?=$dashboad_number_array['server']?>
                                    </div>
								</div>
								<div class="meta"> 伺服器 </div>
								<div class="description">
                                    查詢 Servers 基本資訊, 提供防護軟體部屬情形
								</div>
							</div>
							<div class="extra content">
                                <a href="/query/server/">				
                                    <div class="ui two buttons">
                                        <div class="ui blue button">More Info</div>
                                    </div>
                                </a>
							</div>
						</div>
					</div>
					<div class="four wide computer eight wide tablet sixteen wide mobile column">
						<div class="ui fluid card">
							<div class="content">
								<div class="ui right floated header pink">
                                    <i class="database icon"></i>
								</div>
								<div class="header">
									<div class="ui pink header">
                                        <?=$dashboad_number_array['api']?>
                                    </div>
								</div>
								<div class="meta"> Data collection </div>
								<div class="description">
                                    Extract data from web service and load into database
								</div>
							</div>
							<div class="extra content">
                                <a href="/about/data/">				
                                    <div class="ui two buttons">
                                      <div class="ui pink button">More Info</div>
                                    </div>
                                </a>
							</div>
						</div>
					</div>
				</div><!--end .ui.grid --> 
                <div id="actionItem_post" class="post_title">資安事件列管(已結案/總數)</div>
				<div class="post_cell">
                    <div class="ui grid stackable padded">
                        <div class="four wide computer eight wide tablet sixteen wide mobile column">
						<div class="ui small statistic">
						  <div class="value"><?=$done_event_num." / ".$event_num?></div>
						  <div class="label">市府事件</div>
						</div>
                        </div>
                        <div class="four wide computer eight wide tablet sixteen wide mobile column">
						<div class="ui small statistic <?=$blink_color_class?>">
						  <div class="value"><?=$done_ncert_num." / ".$ncert_num." ".$blink_label?></div>
						  <div class="label">技服通報</div>
						</div>
                        </div>
                    </div>		
			    </div>		
				<div id="enews_post" class="post_title">市府事件總表</div>
                <div class="post_cell">
                    <table class="ui selectable celled table">
                        <thead>
                            <tr>
                                <th>指標</th>
                                <th>數值</th>
                            </tr>
                        </thead>
                        <tbody>
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
                        </tbody>
                    </table>
                </div>
				<div class="post_title">資安事件趨勢圖(最近3個月)</div>
				<div class="post_cell">
					<div id="chartA" class="chart"></div>	
				</div>
				<div id="lastEvent_post" class="post_title">資安事件清單(最近10筆)</div>
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
						<?php foreach($last_10_events as $event): ?>
							<tr>
								<td><?=date_format(new DateTime($event['OccurrenceTime']),'Y-m-d')?></td>
								<td><?=$event['Status']?></td>
                        		<td><?=$event['EventTypeName']?></td>
								<td><?=$event['Location']?></td>
								<td><?=$event['IP']?></td>
								<td><?=$event['AgencyName']?></td>
								<td><?=$event['DeviceOwnerName']?></td>
							</tr>
						<?php endforeach ?>
						</tbody>
						</table>
				</div>
				<div class="see_more" style="text-align:right"><a href="/query/event/">See More...</a></div>
				<div class="post_title">資安事件 SOP</div>
				<div class="post_cell">
					<img class="image" src="/images/sop.png">
				</div>
				<div class="post_title">市府拋送 SOC 設備</div>
				<div class="post_cell">
					<img class="image" src="/images/soc.png">
				</div><!--end of .post_cell-->
			</div>
		</div><!--end of .sub-content-->
		<div style="clear: both;">&nbsp;</div>
	</div><!-- end #content -->
</div> <!--end #page-->
