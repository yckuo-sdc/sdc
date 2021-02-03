<div id="page" class="container">
	<div id="content">
		<div class="sub-content show">
			<div class="post ip_and_url_scanResult">
                <h2 class="ui dividing header">弱點查詢</h2>
				<div class="post_cell">
					<form class="ui form" action="javascript:void(0)">
					<div class="query_content"></div>
					<div class="fields">
						<div class="field">
							<label>種類</label>
							<select name="keyword" id="keyword" class="ui fluid dropdown" required>
							<option value="ou" selected>單位</option>
							<option value="ip">IP</option>
							<option value="system_name">系統名稱</option>
							<option value="scan_no">掃描期別</option>
							<option value="severity">風險程度</option>
							<option value="all">全部</option>
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
							<div class="ui checkbox">
							  <input type="checkbox" name="status[]" tabindex="0" checked>
							  <label>逾期待處理</label>
							</div>
							<div class="ui checkbox">
							  <input type="checkbox" name="status[]" tabindex="0" checked>
							  <label>未逾期待處理</label>
							</div>
							<div class="ui checkbox">
							  <input type="checkbox" name="status[]" tabindex="0" checked>
							  <label>已修補</label>
							</div>
						</div>
						<div class="field">
							<button type="submit" id="search_btn" name="search_btn" class="ui button" >搜尋</button>
						</div>
						 <div class="field">
							<button type="button" id="show_all_btn" class="ui button" onclick="window.location.href='/vul/search/'">顯示全部</button>
						</div>
						 <div class="field">
							<button type="button" id="export2csv_btn" class="ui button" >匯出</button>
						</div>
					</div>
					</form>
					<div class="record_content">
					<?php if($last_num_rows==0): ?>
						查無此筆紀錄
					<?php else: ?>
						共有<?=$last_num_rows?>筆資料！
                        <div class='ui relaxed divided list'>
                        <?php foreach($vuls->data as $vul): ?>
                            <div class='item'>
                            <div class='content'>
                                <a>
                                <span style='background:#f3c4c4'><?=$vul['type']?></span>&nbsp&nbsp
                                <?=$vul['flow_id']?>&nbsp&nbsp
                                <?=str_replace("/臺南市政府/","",$vul['ou'])?>&nbsp&nbsp
                                <span style='background:#fde087'><?=$vul['system_name']?></span>&nbsp&nbsp
                                <?=$vul['status']?>&nbsp&nbsp
                                <span style='background:#DDDDDD'><?=$vul['vitem_name']?></span>&nbsp&nbsp
                                <?=$vul['scan_no']?>&nbsp&nbsp
                                <i class='angle down icon'></i>
                                </a>
                                <div class='description'>
                                    <ol>
                                    <li>弱點類別:<?=$vul['type']?></li>
                                    <li>流水號:<?=$vul['flow_id']?></li>
                                    <li>弱點序號:<?=$vul['vitem_id']?></li>
                                    <li>弱點名稱:<?=$vul['vitem_name']?></li>
                                    <li>OID:<?=$vul['OID']?></li>
                                    <li>單位:<?=str_replace("/臺南市政府/","",$vul['ou'])?></li>
                                    <li>系統名稱:<?=$vul['system_name']?></li>
                                    <li>IP:<?=$vul['ip']?></li>
                                    <li>掃描日期:<?=date_format(new DateTime($vul['scan_date']),'Y-m-d')?></li>
                                    <li>管理員:<?=$vul['manager']?></li>
                                    <li>Email:<?=$vul['email']?></li>
                                    <li>影響網址:<a href='<?=$vul['affect_url']?>' target='_blank'><?=$vul['affect_url']?></a></li>
                                    <li>弱點詳細資訊:<a href='<?=$vul['url']?>' target='_blank'?><?=$vul['url']?></a></li>
                                    <li>總類:<?=$vul['category']?><li>
                                    <li>風險程度:<?=$vul['severity']?></li>
                                    <li>弱點處理情形:<?=$vul['status']?></li>
                                    <li>掃描期別:<?=$vul['scan_no']?></li>
                                    </ol>
                                </div>
                            </div>
                            </div>
                        <?php endforeach ?>
                        </div>
                        <?=$Paginator->createLinks($links, 'ui pagination menu')?>
					<?php endif ?>
					</div> <!-- end of #record_content-->
				</div><!--end of .post_cell-->
			</div><!--end of .post-->
		</div><!--end of .sub-content-->
		<div style="clear: both;">&nbsp;</div>
	</div><!-- end #content -->
</div> <!--end #page-->
