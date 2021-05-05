<div id="page" class="container">
    <div id="content">
		<div class="sub-content show">
			<div class="post contact">
                <?=$route->createBreadcrumbs(' > ');?>
                <h2 class="ui dividing header">資安聯絡人</h2>
				<div class="post_cell">
					<form class="ui form" action="">
					<div class="fields">
						<div class="field">
							<label>種類</label>
							<select name="keyword" id="keyword" class="ui fluid dropdown" required>
							<option value="organization"  selected>機關名稱</option>
							<option value="rank" >機關資安等級</option>
							<option value="OID" >機關OID</option>
							<option value="person_name" >聯絡人姓名</option>
							<option value="person_type" >聯絡人類別</option>
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
							<button type="button" id="show_all_btn" class="ui button" onclick="location.href='/query/contact/'">顯示全部</button>
						</div>
					</div>
					</form>
					<div class="record_content"></div> <!--End of record_content-->	
				</div><!--End of post_cell-->
			</div><!--End of post-->
		</div><!--End of sub-content-->
		<div style="clear: both;"></div>
	</div><!-- end #content -->
</div> <!--end #page-->
