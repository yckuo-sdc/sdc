<!--query_event-->
<div id="page" class="container">
<div id="content">
		<div class="sub-content show">
			<div class="post event">
				<div class="post_title">市府資安事件</div>
				<div class="post_cell">
				<form class="ui form" action="javascript:void(0)">
 				<div class="fields">
			    	<div class="field">
					    <label>種類</label>
						<select name="keyword" id="keyword" class="ui fluid dropdown" required>
						<option value="IP"  selected>設備IP</option>
						<option value="Status" >結案狀態</option>
						<option value="EventTypeName" >資安類型</option>
						<option value="DeviceTypeName" >設備類型</option>
						<option value="DeviceOwnerName" >所有人姓名</option>
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
						<button type="button" id="show_all_btn" class="ui button" onclick="window.location.href='/query/event/'">顯示全部</button>
					</div>
				</div>
				</form>
				<div class="ui modal">
					<i class="close icon"></i>
				  	<div class="header">事件編輯</div>
				  	<div class="content">
						<form class="ui form" action="/do_modal" method="post">
							<input type="hidden" name="EventID">
					  		<div class="field">
								<label>結案狀態</label>
								<select name="Status" class="ui fluid dropdown">
									<option value="已結案" selected>已結案</option>
									<option value="未完成">未完成</option>
								</select>			
							</div>
					  		<div class="field">
								<label>日期</label>
								<input type="date" name="OccurrenceTime" placeholder="日期">
					  		</div>
					  		<div class="field">
								<label>位置</label>
								<input type="text" name="Location" placeholder="位置">
					  		</div>
					  		<div class="field">
								<label>IP</label>
								<input type="text" name="IP" placeholder="IP">
					  		</div>
							<div class="field">
								<label>封鎖原因</label>
								<input type="text" name="BlockReason" placeholder="封鎖原因">
							 </div>	
							<div class="field">
								<label>設備類型</label>
								<input type="text" name="DeviceTypeName" placeholder="設備類型">
							 </div>	
							<div class="field">
								<label>電腦所有人姓名</label>
								<input type="text" name="DeviceOwnerName" placeholder="電腦所有人姓名">
							 </div>	
							<div class="field">
								<label>處理日期(京稘或中華SOC)</label>
								<textarea rows="2" name="AntivirusProcessContent"></textarea>
							 </div>	
							<!--<button class="ui button" type="submit" name="mysubmit">Submit</button>-->
						</form>
				  	</div>
				  	<div class="actions">
						<div class="ui approve button">送出</div>
						<div class="ui cancel button">取消</div>
				  	</div>
				</div>
				<?= flash() ?>
				<div class="record_content">
                <?php
				if($last_num_rows==0){
					echo "查無此筆紀錄";
				}else{
					echo "共有".$last_num_rows."筆資料！";
				?>
				<div class='ui relaxed divided list'>
				<?php foreach($events->data as $event){ ?>
						<div class='item'>
						<div class='content'>
							<a>
							<?php if($event['Status']=="已結案") { ?><i class='check circle icon' style='color:green'></i>
							<?php }else { ?><i class='exclamation circle icon'></i> <?php } ?>
							<?=date_format(new DateTime($event['OccurrenceTime']),'Y-m-d')?>&nbsp&nbsp
							<?=$event['Status']?>&nbsp&nbsp
							<span style='background:#fde087'><?=$event['EventTypeName']?></span>&nbsp&nbsp
							<?=$event['Location']?>&nbsp&nbsp
							<span style='background:#DDDDDD'><?=$event['IP']?></span>&nbsp&nbsp
							<?=$event['DeviceOwnerName']?>&nbsp&nbsp
							<?=$event['DeviceOwnerPhone']?>&nbsp&nbsp
							<i class='angle down icon'></i>
							</a>
							<div class='description'>
								<ol>
									<li>序號:<?=$event['EventID']?></li>
									<li>結案狀態:<?=$event['Status']?></li>
									<li>發現日期:<?=date_format(new DateTime($event['OccurrenceTime']),'Y-m-d')?></li>
									<li>資安事件類型:<?=$event['EventTypeName']?></li>
									<li>位置:<?=$event['Location']?></li>
									<li>電腦IP:<?=$event['IP']?></li>
									<li>封鎖原因:<?=$event['BlockReason']?></li>
									<li>設備類型:<?=$event['DeviceTypeName']?></li>
									<li>電腦所有人姓名:<?=$event['DeviceOwnerName']?></li>
									<li>電腦所有人分機:<?=$event['DeviceOwnerPhone']?></li>
									<li>機關:<?=$event['AgencyName']?></li>
									<li>單位:<?=$event['UnitName']?></li>
									<li>處理日期(國眾):<?=$event['NetworkProcessContent']?></li>
									<li>處理日期(三佑科技):<?=$event['MaintainProcessContent']?></li>
									<li>處理日期(京稘或中華SOC):<?=$event['AntivirusProcessContent']?></li>
									<li>未能處理之原因及因應方式:<?=$event['UnprocessedReason']?></li>
									<li>備註:<?=$event['Remarks']?></li>
								</ol>
								<button type="button" class="ui button edit" key="<?=$event['EventID']?>">Edit</button>
								<button type="button" class="ui button delete" key="<?=$event['EventID']?>">Delete</button>
							</div>
						</div>
						</div>
				<?php	} ?>
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
