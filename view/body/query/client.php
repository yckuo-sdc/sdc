<div id="page" class="container">
	<div id="content">
		<div class="sub-content show">
			<div class="post client">
                <?=$route->createBreadcrumbs(' > ');?>
                <h2 class="ui dividing header">端點資安清單</h2>
				<div class="post_cell">
					<div class="ui secondary pointing menu">
						<a class="active item">DrIP</a>
						<a class="item">GCB</a>
						<a class="item">WSUS</a>
						<a class="item">AntiVirus</a>
						<a class="item">EDR</a>
					</div>
					<div class="ui noborder bottom attached segment">
                        <div class="tab-content drip show">
                            <form class="ui form" action="">
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
                                    <button type="button" id="show_all_btn" class="ui button" onclick="location.href='/query/client/'">顯示全部</button>
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
                            <i class='circle brown icon'></i>edr
                            <p></p>
                            <div class="record_content"></div> <!--End of record_content-->	
                        </div> <!--End of tabular_content-->	
                        <div class="tab-content gcb">
                        <form class="ui form" action="">
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
                                <button type="button" id="show_all_btn" class="ui button" onclick="location.href='/query/client/?tab=2'">顯示全部</button>
                            </div>
                        </div>
                        </form>
                        <div class="record_content"></div> <!--End of record_content-->	
                    </div> <!--End of tabular_content-->	
                    <div class="tab-content wsus">
                        <form class="ui form" action="">
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
                                <button type="button" id="show_all_btn" class="ui button" onclick="location.href='/query/client/?tab=3'">顯示全部</button>
                            </div>
                        </div>
                        </form>
                        <div class='header'>
                            排序：
                            <a href="" data-label='FullDomainName'>電腦名稱<i class='sort icon'></i></a>&nbsp&nbsp
                            <a href="" data-label='FullDomainName'>內網IP<i class='sort icon'></i></a>&nbsp&nbsp
                            <a href="" data-label='NotInstalled'>未安裝更新<i class='sort icon'></i></a>&nbsp&nbsp
                            <a href="" data-label='Failed'>安裝失敗更新<i class='sort icon'></i></a>&nbsp&nbsp
                            <a href="" data-label='OSDescription'>作業系統<i class='sort icon'></i></a>&nbsp&nbsp
                        </div>
                        <div class="record_content"></div> <!--End of record_content-->	
                    </div> <!--End of tabular_content-->	
                    <div class="tab-content antivirus">
                        <form class="ui form" action="">
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
                                <button type="button" id="show_all_btn" class="ui button" onclick="location.href='/query/client/?tab=4'">顯示全部</button>
                            </div>
                        </div>
                        </form>
                        <div class="record_content"></div> <!--End of record_content-->	
                    </div> <!--End of tabular_content-->	
                    <div class="tab-content edr">
                    <form class="ui form" action="">
                    <div class="fields">
                        <div class="field">
                            <label>種類</label>
                            <select name="keyword" id="keyword" class="ui fluid dropdown" required>
                            <option value="host_name"  selected>電腦名稱</option>
                            <option value="ip" >內部IP</option>
                            <option value="state" >監控狀態</option>
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
                            <button type="button" id="show_all_btn" class="ui button" onclick="location.href='/query/client/?tab=5'">顯示全部</button>
                        </div>
                    </div>
                    </form>
                    <div class='header'>
                        排序：
                        <a href="" data-label='host_name'>主機名稱<i class='sort icon'></i></a>&nbsp&nbsp
                        <a href="" data-label='state'>監控狀態<i class='sort icon'></i></a>&nbsp&nbsp
                        <a href="" data-label='os'>作業系統<i class='sort icon'></i></a>&nbsp&nbsp
                        <a href="" data-label='ip'>內網IP<i class='sort icon'></i></a>&nbsp&nbsp
                        <a href="" data-label='total_number'>總單數<i class='sort icon'></i></a>&nbsp&nbsp
                    </div>
                    <div class="record_content"></div> <!--End of record_content-->	
                </div> <!--End of tabular_content-->	
			</div> <!--End of attached_menu-->	
			</div><!--End of post_cell-->
			</div><!--End of post-->
		</div><!--End of sub-content-->
		<div style="clear: both;"></div>
	</div><!-- end #content -->
</div> <!--end #page-->
