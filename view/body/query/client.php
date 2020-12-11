<!--query_client-->
<div id="page" class="container">
	<div id="content">
		<div class="sub-content show">
			<div class="post is_client_list">
                <h2 class="ui dividing header">用戶端資安清單</h2>
				<div class="post_cell">
					<div class="ui top attached tabular menu">
						<a class="active item">DrIP</a>
						<a class="item">GCB</a>
						<a class="item">WSUS</a>
						<a class="item">AntiVirus</a>
					</div>
					<div class="ui bottom attached segment">
					<div class="tab-content drip show">
						<form class="ui form" action="javascript:void(0)">
						<div class="query_content"></div>
						<div class="fields">
							<div class="field">
								<label>種類</label>
								<select name="keyword" id="keyword" class="ui fluid dropdown" required>
								<option value="ClientName"  selected>電腦名稱</option>
								<option value="IP" >內部IP</option>
								<option value="UserName" >使用者帳號</option>
								<option value="OrgName" >單位名稱</option>
								<option value="ad" >ad</option>
								<option value="gcb" >gcb</option>
								<option value="wsus" >wsus</option>
								<option value="antivirus" >antivirus</option>
								<option value="edr" >edr</option>
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
								<label>新增條件</label>
								<i class="large square icon plus"></i>
							</div>
							<div class="field">
								<button type="submit" id="search_btn" name="search_btn" class="ui button" >搜尋</button>
							</div>
							 <div class="field">
								<button type="button" id="show_all_btn" class="ui button" onclick="window.location.href='/query/client/?tab=1'">顯示全部</button>
							</div>
							 <div class="field">
								<button type="button" id="export2csv_btn" class="ui button">匯出</button>
							</div>
						</div>
						</form>
						<i class='circle yellow icon'></i>ad
						<i class='circle green icon'></i>gcb
						<i class='circle red icon'></i>wsus
						<i class='circle blue icon'></i>antivirus
						<i class='circle black icon'></i>edr
						<p></p>
						<div class="record_content">
						<?php
						if($drip_num_rows==0){
							echo "查無此筆紀錄";
						}else{
							echo "共有".$drip_num_rows."筆資料！";
						?>
						<div class='ui relaxed divided list'>
						<?php foreach($drip->data as $client) { ?>
							<div class='item'>
							<div class='content'>
								<a>
								<?php if($client['ad']==1) { ?> <i class='circle yellow icon'></i>
								<?php }else { ?> <i class='circle outline icon'></i> <?php } ?>
								<?php if($client['gcb']==1) { ?> <i class='circle green icon'></i>
								<?php }else { ?> <i class='circle outline icon'></i> <?php } ?>
								<?php if($client['wsus']==1) { ?> <i class='circle red icon'></i> 
								<?php }else { ?> <i class='circle outline icon'></i> <?php } ?>
								<?php if($client['antivirus']==1) { ?> <i class='circle blue icon'></i>  
								<?php }else { ?><i class='circle outline icon'></i> <?php } ?>
								<?php if($client['edr']==1) { ?> <i class='circle black icon'></i> 
								<?php }else { ?> <i class='circle outline icon'></i> <?php } ?>
								<?=$client['DetectorName']?>&nbsp&nbsp
								<span style='background:#fde087'><?=$client['IP']?></span>&nbsp&nbsp
								<?=$client['ClientName']?>&nbsp&nbsp
								<span style='background:#fbc5c5'><?=$client['OrgName']?></span>&nbsp&nbsp
								<?=$client['Owner']?>&nbsp&nbsp
								<?=$client['UserName']?>&nbsp&nbsp
								<i class='angle down icon'></i>
								</a>
								<div class='description'>
									<ol>
									<li>內網IP:<?=$client['IP']?></li>
									<li>MAC位址:<?=$client['MAC']?></li>
									<li>設備名稱:<?=$client['ClientName']?></li>
									<li>群組名稱:<?=$client['GroupName']?></li>
									<li>網卡製造商:<?=$client['NICProductor']?></li>
									<li>偵測器名稱:<?=$client['DetectorName']?></li>
									<li>偵測器IP:<?=$client['DetectorIP']?></li>
									<li>偵測器群組:<?=$client['DetectorGroup']?></li>
									<li>交換器名稱:<?=$client['SwitchName']?></li>
									<li>連接埠名稱:<?=$client['PortName']?></li>
									<li>最後上線時間:<?=$client['LastOnlineTime']?></li>
									<li>最後下線時間:<?=$client['LastOfflineTime']?></li>
									<li>IP封鎖原因:<?=$client['IP_BlockReason']?></li>
									<li>MAC封鎖原因:<?=$client['MAC_BlockReason']?></li>
									<li>備註ByIP:<?=$client['MemoByIP']?></li>
									<li>備註ByMac:<?=$client['MemoByMAC']?></li>
									<li>ad安裝:<?=$client['ad']?></li>
									<li>gcb安裝:<?=$client['gcb']?></li>
									<li>wsus安裝:<?=$client['wsus']?></li>
									<li>antivirus安裝:<?=$client['antivirus']?></li>
									<li>edr安裝:<?=$client['edr']?></li>
									<li>OrgName:<?=$client['OrgName']?></li>
									<li>Owner:<?=$client['Owner']?></li>
									<li>UserName:<?=$client['UserName']?></li>
									</ol>
									<?php if(isLogin() && $_SESSION['Level'] == 2){ ?>
										<button data-ip="<?=$client['IP']?>" id="block-btn" class="ui button">Block IP</button>
										<button data-ip="<?=$client['IP']?>" id="unblock-btn" class="ui button">UnBlock IP</button>
										<div class="ui centered inline loader"></div>
										<div class="block_IP_response"></div>
									<?php } ?>
								</div>
								</div>
							</div>
						<?php } ?>
						</div>
					<?php echo $drip_paginator->createLinks($links, 'ui pagination menu'); ?>
					<?php } ?>
					</div> <!--End of record_content-->	
				</div> <!--End of tabular_content-->	
				<div class="tab-content gcb">
				<form class="ui form" action="javascript:void(0)">
				<div class="fields">
					<div class="field">
						<label>種類</label>
						<select name="keyword" id="keyword" class="ui fluid dropdown" required>
						<option value="Name"  selected>電腦名稱</option>
						<option value="InternalIP" >內部IP</option>
						<option value="UserName" >使用者帳號</option>
						<option value="OrgName" >單位名稱</option>
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
						<button type="button" id="show_all_btn" class="ui button" onclick="window.location.href='/query/client/?tab=2'">顯示全部</button>
					</div>
				</div>
				</form>
				<div class="record_content">
				<?php
				if($gcb_num_rows==0){
					echo "查無此筆紀錄";
				}else{
					echo "共有".$gcb_num_rows."筆資料！";
				?>
				<div class='ui relaxed divided list'>
				<?php foreach($gcb->data as $client){ ?>
					<div class='item'>
					<div class='content'>
						<a>
						<?php if($client['IsOnline'] == "1"){ ?> <i class='circle green icon'></i>
						<?php }else { ?> <i class='circle outline icon'></i>  <?php } ?>
						<?php switch($client['GsStat']){
							case '0':
								$GsStat_str = "未套用";
								break;
							case '1':
								$GsStat_str = "已套用";
								break;
							case '-1':
								$GsStat_str = "套用失敗";
								break;
							case '2':
								$GsStat_str = "還原成功";
								break;
							case '-2':
								$GsStat_str = "未套用";
								break;
							default:
								$GsStat_str = "None";
								break;
						} ?>
						<?=$client['Name']?>&nbsp&nbsp
						<span style='background:#fde087'><?=$client['OrgName']?></span>&nbsp&nbsp
						<?=$client['UserName']?>&nbsp&nbsp
						<?=$client['Owner']?>&nbsp&nbsp
						<span style='background:#DDDDDD'><?=long2ip($client['InternalIP'])?></span>&nbsp&nbsp
						<?=$client['os_name']?>&nbsp&nbsp
						<i class='angle down icon'></i>
						</a>
						<div class='description'>
							<ol>
							<li><a href='/ajax/gcb_detail/?action=detail&id=<?=$client['ID']?>' target='_blank'>序號:<?=$client['ID']?>(用戶端資訊)&nbsp<i class='external alternate icon'></i></a></li>
							<li>外部IP:<?=long2ip($client['ExternalIP'])?></li>
							<li>內部IP:<?=long2ip($client['InternalIP'])?></li>
							<li>電腦名稱:<?=$client['Name']?></li>
							<li>單位名稱:<?=$client['OrgName']?></li>
							<li>使用者帳號:<?=$client['UserName']?></li>
							<li>使用者名稱:<?=$client['Owner']?></li>
							<li>OS:<?=$client['os_name']?></li>
							<li>IE:<?=$client['ie_name']?></li>
							<li>是否上線:<?=$client['IsOnline']?></li>
							<li>Gcb總通過數[未包含例外]:<?=$client['GsAll_0']?></li>
							<li>Gcb總通過數[包含例外]:<?=$client['GsAll_1']?></li>
							<li>Gcb總通過數[總數]:<?=$client['GsAll_2']?></li>
							<li>Gcb例外數量:<?=$client['GsExcTot']?></li>
							<li><a href='/ajax/gcb_detail/?action=gscan&id=<?=$client['GsID']?>' target='_blank'>Gcb掃描編號:<?=$client['GsID']?>(掃描結果資訊)&nbsp<i class='external alternate icon'></i></a></li>
							<li>Gcb派送編號:<?=$client['GsSetDeployID']?></li>
							<li>Gcb狀態:<?=$GsStat_str?></li>
							<li>Gcb回報時間:<?=$client['GsUpdatedAt']?></li>
							</ol>
						</div>
					</div>
					</div>
				<?php	} ?>
				</div>
				<?php echo $gcb_paginator->createLinks($links, 'ui pagination menu', $attrs = ['tab' => 2]); ?>
				<?php } ?>
				</div> <!--End of record_content-->	
			</div> <!--End of tabular_content-->	
			<div class="tab-content wsus">
				<form class="ui form" action="javascript:void(0)">

				<div class="fields">
					<div class="field">
						<label>種類</label>
						<select name="keyword" id="keyword" class="ui fluid dropdown" required>
						<option value="FullDomainName"  selected>電腦名稱</option>
						<option value="IPAddress" >內部IP</option>
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
						<button type="button" id="show_all_btn" class="ui button" onclick="window.location.href='/query/client/?tab=3'">顯示全部</button>
					</div>
				</div>
				</form>
				<div class="record_content">
				<?php
				if($wsus_num_rows==0){
					echo "查無此筆紀錄";
				}else{
					echo "共有".$wsus_num_rows."筆資料！";
				?>
				<div class='ui relaxed divided list'>
					<div class='item'>
						<div class='content'>
							<div class='header'>
								<a href='/query/client/?tab=3&sort=FullDomainName'>電腦名稱<i class='caret up icon'></i></a>&nbsp&nbsp
								<a href='/query/client/?tab=3&sort=IPAddress'>內網IP<i class='caret up icon'></i></a>&nbsp&nbsp
								<a href='/query/client/?tab=3&sort=NotInstalled'>未安裝更新<i class='caret up icon'></i></a>&nbsp&nbsp
								<a href='/query/client/?tab=3&sort=Failed'>安裝失敗更新<i class='caret up icon'></i></a>&nbsp&nbsp
								<a href='/query/client/?tab=3&sort=OSDescription'>作業系統<i class='caret up icon'></i></a>&nbsp&nbsp
							</div>
						</div>
					</div>
				<?php foreach($wsus->data as $index => $client) { ?>
					<div class='item'>
					<div class='content'>
						<a>
						<?=strtoupper(str_replace(".tainan.gov.tw","",$client['FullDomainName']))?>&nbsp&nbsp
						<span style='background:#fde087'><?=$client['IPAddress']?></span>&nbsp&nbsp
						<span style='background:#DDDDDD'><?=$client['NotInstalled']?></span>&nbsp&nbsp
						<span style='background:#fbc5c5'><?=$client['Failed']?></span>&nbsp&nbsp
						<?=$client['OSDescription']?>
						<i class='angle down icon'></i>
						</a>
						<div class='description'>
							<ol>
							<li>序號:<?=$client['TargetID']?></li>
							<li>電腦名稱:<?=strtoupper(str_replace(".tainan.gov.tw","",$client['FullDomainName']))?></li>
							<li>內網IP:<?=$client['IPAddress']?></li>
							<li>未知更新數量:<?=$client['Unknown']?></li>
							<li>未安裝更新數量:<?=$client['NotInstalled']?></li>
							<?php foreach($notinstalled_kb[$index] as $kb) { ?>
								<strong>KB<?=$kb['KBArticleID']?></strong>
							<?php }	?>
							<li>已下載更新數量:<?=$client['Downloaded']?></li>
							<li>已安裝更新數量:<?=$client['Installed']?></li>
							<li>安裝失敗更新數量:<?=$client['Failed']?></li>
							<?php foreach($failed_kb[$index] as $kb) { ?>
								<strong>KB<?=$kb['KBArticleID']?></strong>
							<?php } ?>	
							<li>已安裝待重開機更新數量:<?=$client['InstalledPendingReboot']?></li>
							<li>上次狀態回報日期:<?=dateConvert($client['LastReportedStatusTime'])?></li>
							<li>上次更新重開機日期:<?=dateConvert($client['LastReportedRebootTime'])?></li>
							<li>上次可用更新日期:<?=dateConvert($client['EffectiveLastDetectionTime'])?></li>
							<li>上次同步日期:<?=dateConvert($client['LastSyncTime'])?></li>
							<li>上次修改日期:<?=dateConvert($client['LastChangeTime'])?></li>
							<li>上次同步結果:<?=$client['LastSyncResult']?></li>
							<li>製造商:<?=$client['ComputerMake']?></li>
							<li>型號:<?=$client['ComputerModel']?></li>
							<li>作業系統:<?=$client['OSDescription']?></li>
							</ol>
							</div>
						</div>
						</div>
				<?php	} ?>
				</div>
				<?php echo $wsus_paginator->createLinks($links, 'ui pagination menu', $attrs = ['tab' => 3, 'sort' => $sort]); ?>
				<?php } ?>
				</div> <!--End of record_content-->	
			</div> <!--End of tabular_content-->	
			<div class="tab-content antivirus">
				<form class="ui form" action="javascript:void(0)">
				<div class="fields">
					<div class="field">
						<label>種類</label>
						<select name="keyword" id="keyword" class="ui fluid dropdown" required>
						<option value="ClientName"  selected>電腦名稱</option>
						<option value="IP" >內部IP</option>
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
						<button type="button" id="show_all_btn" class="ui button" onclick="window.location.href='/query/client/?tab=4'">顯示全部</button>
					</div>
				</div>
				</form>
				<div class="record_content">
				<?php //select data form database
				if($antivirus_num_rows==0){
					echo "查無此筆紀錄";
				}else{
					echo "共有".$antivirus_num_rows."筆資料！";
				?>
					<div class='ui relaxed divided list'>
					<?php foreach($antivirus->data as $client){ ?>
					<div class='item'>
					<div class='content'>
						<a>
						<?php if($client['ConnectionState'] == "線上"){ ?> <i class='circle green icon'></i>
						<?php } else{ ?> <i class='circle outline icon'></i> <?php } ?>
						<?=$client['ClientName']?>&nbsp&nbsp
						<span style='background:#fde087'><?=$client['IP']?></span>&nbsp&nbsp
						<span style='background:#fbc5c5'><?=$client['OS']?></span>&nbsp&nbsp
						<?=$client['VirusNum']?>&nbsp&nbsp
						<?=$client['SpywareNum']?>&nbsp&nbsp
						<span style='background:#DDDDDD'><?=$client['VirusPatternVersion']?></span>&nbsp&nbsp
						<?=$client['LogonUser']?>&nbsp&nbsp
						<i class='angle down icon'></i>
						</a>
						<div class='description'>
							<ol>
							<li>設備名稱:<?=$client['ClientName']?></li>
							<li>內網IP:<?=$client['IP']?></li>
							<li>網域階層:<?=$client['DomainLevel']?></li>
							<li>連線狀態:<?=$client['ConnectionState']?></li>
							<li>GUID:<?=$client['GUID']?></li>
							<li>掃描方式:<?=$client['ScanMethod']?></li>
							<li>DLP狀態:<?=$client['DLPState']?></li>
							<li>病毒數量:<?=$client['VirusNum']?></li>
							<li>間諜程式數量:<?=$client['SpywareNum']?></li>
							<li>作業系統:<?=$client['OS']?></li>
							<li>位元版本:<?=$client['BitVersion']?></li>
							<li>MAC位址:<?=$client['MAC']?></li>
							<li>設備版本:<?=$client['ClientVersion']?></li>
							<li>病毒碼版本:<?=$client['VirusPatternVersion']?></li>
							<li>登入使用者:<?=$client['LogonUser']?></li>
							</ol>
							</div>
						</div>
						</div>
				<?php	} ?>
				</div>
				<?php echo $antivirus_paginator->createLinks($links, 'ui pagination menu', $attrs = ['tab' => 4]); ?>
				<?php } ?>
				</div> <!--End of record_content-->	
			</div> <!--End of tabular_content-->	
			</div> <!--End of attached_menu-->	
			</div><!--End of post_cell-->
			</div><!--End of post-->
		</div><!--End of sub-content-->
		<div style="clear: both;"></div>
	</div><!-- end #content -->
</div> <!--end #page-->
