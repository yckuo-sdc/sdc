<div id="page" class="container">
    <div id="content">
		<div class="sub-content show">
			<div class="post network">
                <h2 class="ui dividing header">網路流量日誌(IPS)</h2>
				<div class="post_cell">
					<div class="ui top attached tabular menu">
						<a class="active item">Yonghua</a>
						<a class="item">Minjhih</a>
						<a class="item">IDC</a>
						<a class="item">IntraYonghua</a>
					</div>
					<div class="ui bottom attached segment">
						<div class="tab-content yonghua show">
						<div class="query_content"></div>
						<form class="ui form" action="javascript:void(0)">
						<div class="fields">
							<div class="field">
								<label>種類</label>
								<select name="keyword" id="keyword" class="ui fluid dropdown" required>
								<option value="addr.src"  selected>來源IP</option>
								<option value="addr.dst" >目的IP</option>
								<option value="port.dst" >目的port</option>
								<option value="rule" >規則</option>
								<option value="app" >應用程式</option>
								<option value="action" >動作</option>
								</select>
							</div>
							<div class="field">
								<label>運算</label>
								<select name="operator" id="operator" class="ui fluid dropdown" required>
								<option value="="  selected>=</option>
								<option value="!=" >!=</option>
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
								<button type="button" id="show_all_btn" class="ui button" onclick="window.location.href='/network/search/'">顯示全部</button>
							</div>
						</div>
						</form>
						<div class="ui centered inline loader"></div>
						<div class="record_content"></div>
						</div> <!-- end of .tabular-->
						<div class="tab-content minjhih">
						<div class="query_content"></div>
						<form class="ui form" action="javascript:void(0)">
						<div class="fields">
							<div class="field">
								<label>種類</label>
								<select name="keyword" id="keyword" class="ui fluid dropdown" required>
								<option value="addr.src"  selected>來源IP</option>
								<option value="addr.dst" >目的IP</option>
								<option value="port.dst" >目的port</option>
								<option value="rule" >規則</option>
								<option value="app" >應用程式</option>
								<option value="action" >動作</option>
								</select>
							</div>
							<div class="field">
								<label>運算</label>
								<select name="operator" id="operator" class="ui fluid dropdown" required>
								<option value="="  selected>=</option>
								<option value="!=" >!=</option>
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
								<button type="button" id="show_all_btn" class="ui button" onclick="window.location.href='/network/search/?tab=2'">顯示全部</button>
							</div>
						</div>
						</form>
						<div class="ui centered inline loader"></div>
						<div class="record_content"></div>
						</div> <!-- end of .tabular-->
						<div class="tab-content idc">
						<div class="query_content"></div>
						<form class="ui form" action="javascript:void(0)">
						<div class="fields">
							<div class="field">
								<label>種類</label>
								<select name="keyword" id="keyword" class="ui fluid dropdown" required>
								<option value="addr.src"  selected>來源IP</option>
								<option value="addr.dst" >目的IP</option>
								<option value="port.dst" >目的port</option>
								<option value="rule" >規則</option>
								<option value="app" >應用程式</option>
								<option value="action" >動作</option>
								</select>
							</div>
							<div class="field">
								<label>運算</label>
								<select name="operator" id="operator" class="ui fluid dropdown" required>
								<option value="="  selected>=</option>
								<option value="!=" >!=</option>
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
								<button type="button" id="show_all_btn" class="ui button" onclick="window.location.href='/network/search/?tab=3'">顯示全部</button>
							</div>
						</div>
						</form>
						<div class="ui centered inline loader"></div>
						<div class="record_content"></div>
						</div> <!-- end of .tabular-->
						<div class="tab-content intrayonghua">
						<div class="query_content"></div>
						<form class="ui form" action="javascript:void(0)">
						<div class="fields">
							<div class="field">
								<label>種類</label>
								<select name="keyword" id="keyword" class="ui fluid dropdown" required>
								<option value="addr.src"  selected>來源IP</option>
								<option value="addr.dst">目的IP</option>
								<option value="port.dst">目的port</option>
								<option value="rule">規則</option>
								<option value="app">應用程式</option>
								<option value="action">動作</option>
								</select>
							</div>
							<div class="field">
								<label>運算</label>
								<select name="operator" id="operator" class="ui fluid dropdown" required>
								<option value="="  selected>=</option>
								<option value="!=" >!=</option>
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
								<button type="button" id="show_all_btn" class="ui button" onclick="window.location.href='/network/search/?tab=4'">顯示全部</button>
							</div>
						</div>
						</form>
						<div class="ui centered inline loader"></div>
						<div class="record_content"></div>
						</div> <!-- end of .tabular-->
					</div> <!-- end of .attached.segment-->
				</div><!--end of .post_cell-->
			</div><!--end of .post-->
		</div><!--end of .sub-content-->
		<div style="clear: both;"></div>
	</div><!-- end #content -->
</div> <!--end #page-->
