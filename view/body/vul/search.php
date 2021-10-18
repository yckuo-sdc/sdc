<div id="page" class="container">
	<div id="content">
		<div class="sub-content show">
			<div class="post scanResult">
                <?=$route->createBreadcrumbs(' > ');?>
                <h2 class="ui dividing header">弱點查詢</h2>
				<div class="post_cell">
					<form class="ui form" action="">
					<div class="query_content"></div>
					<div class="fields">
						<div class="field">
							<label>欄位</label>
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
							<button type="button" id="show_all_btn" class="ui button" onclick="location.href='/vul/search/'">顯示全部</button>
						</div>
						 <div class="field">
							<button type="button" id="export2csv_btn" class="ui button" >匯出</button>
						</div>
					</div>
					</form>
					<div class="record_content"></div> <!-- end of #record_content-->
				</div><!--end of .post_cell-->
			</div><!--end of .post-->
		</div><!--end of .sub-content-->
		<div style="clear: both;">&nbsp;</div>
	</div><!-- end #content -->
</div> <!--end #page-->
