<!--query_ncert-->
<div id="page" class="container">
<div id="content">
		<div class="sub-content show">
			<div class="post ncert">
                <h2 class="ui dividing header">技服資安通報</h2>
				<div class="post_cell">
					<form class="ui form" action="javascript:void(0)">
					<div class="fields">
						<div class="field">
							<label>種類</label>
							<select name="keyword" id="keyword" class="ui fluid dropdown" required>
							<option value="PublicIP"  selected>IP/URL</option>
							<option value="Status" >結案狀態</option>
							<option value="Classification" >事故類型</option>
							<option value="OrganizationName" >機關名稱</option>
							<option value="NccstPT" >攻防演練(是/否)</option>
							<option value="OccurrenceTime" >發現日期</option>
							<option value="all" >全部</option>
							</select>
						</div>
						<div class="field">
							<label>關鍵字</label>
							<div class="ui input">
								<input type='text' name='key' id='key' placeholder="請輸入關鍵字">
							</div>
						</div>
						<div class="field">
							<button type="submit" id="search_btn" name="search_btn" class="ui button" >搜尋</button>
						</div>
						 <div class="field">
							<button type="button" id="show_all_btn" class="ui button" onclick="window.location.href='/query/ncert/'">顯示全部</button>
						</div>
					</div>
					</form>
					<div class="record_content">
					<?php
					if($last_num_rows==0){
						echo "查無此筆紀錄";
					}else{
						echo "共有".$last_num_rows."筆資料！";
					?>
					<div class='ui relaxed divided list'>
					<?php	foreach($incidents->data as $incident){ ?>
							<div class='item'>
							<div class='content'>
								<a>
                                <?php if($incident['Status']=="已結案") { ?><i class='check circle icon' style='color:green'></i>
                                <?php }else { ?><i class='exclamation circle icon'></i> <?php } ?>
								<?=date_format(new DateTime($incident['DiscoveryTime']),'Y-m-d') ?>&nbsp&nbsp
								<?=$incident['Status'] ?>&nbsp&nbsp
								<span style='background:#DDDDDD'><?=$incident['ImpactLevel'] ?></span>&nbsp&nbsp
								<?=$incident['Classification'] ?>&nbsp&nbsp
								<span style='background:#fde087'><?=$incident['PublicIP'] ?> </span>&nbsp&nbsp
								<?=$incident['OrganizationName'] ?>
								<i class='angle down icon'></i>
								</a>
								<div class='description'>
									<ol>
									<li>編號:<?=$incident['IncidentID'] ?></li>
									<li>結案狀態:<?=$incident['Status'] ?></li>
									<li>事件編號:<?=$incident['NccstID'] ?></li>
									<li>行政院攻防演練:<?=$incident['NccstPT'] ?></li>
									<li>攻防演練衝擊性:<?=$incident['NccstPTImpact'] ?></li>
									<li>機關名稱:<?=$incident['OrganizationName'] ?></li>
									<li>聯絡人:<?=$incident['ContactPerson'] ?></li>
									<li>電話:<?=$incident['Tel'] ?></li>
									<li>電子郵件<?=$incident['Email'] ?></li>
									<li>資安維護廠商<?=$incident['SponsorName'] ?></li>
									<li>對外IP或網址<?=$incident['PublicIP'] ?></li>
									<li>使用用途:<?=$incident['DeviceUsage'] ?></li>
									<li>作業系統:<?=$incident['OperatingSystem'] ?></li>
									<li>入侵網址:<?=$incident['IntrusionURL'] ?></li>
									<li>影響等級:<?=$incident['ImpactLevel'] ?></li>
									<li>事故分類:<?=$incident['Classification'] ?></li>
									<li>事故說明:<?=$incident['Explaination'] ?></li>
									<li>影響評估:<?=$incident['Evaluation'] ?></li>
									<li>應變措施:<?=$incident['Response'] ?></li>
									<li>解決辦法/結報內容:<?=$incident['Solution'] ?></li>
									<li>發現時間:<?=$incident['DiscoveryTime'] ?></li>
									<li>通報時間:<?=$incident['InformTime'] ?></li>
									<li>修復時間:<?=$incident['RepairTime'] ?></li>
									<li>審核機關審核時間:<?=$incident['TainanGovVerificationTime'] ?></li>
									<li>技服中心審核時間:<?=$incident['NccstVerificationTime'] ?></li>
									<li>通報結報時間:<?=$incident['FinishTime'] ?></li>
									<li>通報執行時間(時:分):<?=$incident['InformExecutionTime'] ?></li>
									<li>結案執行時間(時:分):<?=$incident['FinishExecutionTime'] ?></li>
									<li>中華SOC複測結果:<?=$incident['SOCConfirmation'] ?></li>
									<li>改善計畫提報日期:<?=$incident['ImprovementPlanTime'] ?></li>
									</ol>
								</div>
								</div>
							</div>
						<?php } ?>
						</div>
					<?php echo $Paginator->createLinks($links, 'ui pagination menu'); ?>
					<?php } ?>
					</div> <!--End of record_content-->	
				</div><!--End of post_cell-->
			</div><!--End of post-->
		</div><!--End of sub-content-->
		<div style="clear: both;"></div>
	</div><!-- end #content -->
</div> <!--end #page-->
