<div id="page" class="container">
    <div id="content">
		<div class="sub-content show">
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
                    </form>
                </div>
                <div class="actions">
                    <div class="ui approve button">送出</div>
                    <div class="ui cancel button">取消</div>
                </div>
            </div>
			<div class="post event">
                <h2 class="ui dividing header">市府資安事件</h2>
				<div class="post_cell">
				<form class="ui form" action="">
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
						<button type="button" id="show_all_btn" class="ui button" onclick="location.href='/query/event/'">顯示全部</button>
					</div>
				</div>
				</form>
				<?= flash() ?>
				<div class="record_content"></div> <!--End of record_content-->	
				</div><!--End of post_cell-->
			</div><!--End of post-->
		</div><!--End of sub-content-->
		<div style="clear: both;"></div>
	</div><!-- end #content -->
</div> <!--end #page-->
