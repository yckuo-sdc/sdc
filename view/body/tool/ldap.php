<div id="page" class="container">
	<div id="content">
		<div class="sub-content show">
			<div class="post ldap">
                <?=$route->createBreadcrumbs(' > ');?>
                <h2 class="ui dividing header">LDAP</h2>
				<div class="post_title">LDAP Search</div>
				<div class="post_cell ldap">
					<form class="ui form" action="">
						<div class="fields">
                            <div class="field">
                                <label>欄位</label>
                                <select name="objectCategory" class="ui fluid dropdown" required>
                                    <option value="user" selected>user</option>
                                    <option value="computer">computer</option>
                                    <option value="ou">ou</option>
                                </select>			
                            </div>
                            <div class="field">
                                <label>Name</label>
                                <div class="ui input">
                                    <input type="text" name="target" value="<?=$target?>" placeholder="<?=$target?>">
                                </div>
                            </div>
                            <div class="field">
                                <button type="submit" id="ldap_search_btn" class="ui button">Search</button>
                            </div>
                            <div class="field">
                                <button type="button" id="ldap_newuser_btn" class="ui button">New User</button>
                            </div>
                            <div class="field">
                                <button type="button" id="ldap_newou_btn" class="ui button">New OU</button>
                            </div>
                            <div class="ui centered inline loader"></div>
                        </div>
					</form>

				<div class="record_content"></div>
                </div> <!-- end of .post_cell-->
				<div class="post_title"><i class='icon caret right'></i>Tainan LocalUsers in AD</div>
				<div class="post_cell ldap_tainanlocalusers">
                    <div class='ui centered grid'>
                        <div class='sixteen wide column'>
                            <div class="ldap_tree_content ldap_tainanlocalusers">
                                <div class="ui list">
                                    <div class="item hide">
                                        <i class="plus square outline icon" base="<?=$user_base?>" ou="<?=$user_ou?>" description="<?=$user_description?>"></i>
                                        <i class="folder icon"></i>
                                        <div class="content">
                                            <div class="header"><?=$user_ou?>(<?=$user_description?>)</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
				</div><!--end of .post_cell-->
				<div class="post_title"><i class='icon caret right'></i>Tainan Computers in AD</div>
				<div class="post_cell ldap_tainancomputers">
                    <div class='ui centered grid'>
                        <div class='sixteen wide column'>
                            <div class="ldap_tree_content ldap_tainancomputers">
                                <div class="ui list">
                                    <div class="item hide">
                                        <i class="plus square outline icon" base="<?=$computer_base?>" ou="<?=$computer_ou?>" description="<?=$computer_description?>"></i>
                                        <i class="folder icon"></i>
                                        <div class="content">
                                            <div class="header"><?=$computer_ou?>(<?=$computer_description?>)</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
				</div><!--end of .post_cell-->
                <?php if($_SESSION['level'] == 2):	?>
                    <div class="post_title"><i class='icon caret right'></i>Computers in AD</div>
                    <div class="post_cell ldap_computers">
                        <?= flash() ?>
                        <div class='ui centered grid'>
                            <div class='sixteen wide right aligned column'>
                                <button type="button" class="ui button edit_btn"><i class='ui edit icon'></i> Edit</button>
                            </div>
                            <div class='sixteen wide column' style='max-height: 40vh; overflow-x: hidden; overflow-y: auto;'>
                                <div class="ldap_tree_content ldap_defaultcomputers">
                                    <div class="ui list">
                                        <div class="item hide">
                                            <i class="plus square outline icon" base="<?=$default_computer_base?>" ou="<?=$default_computer_ou?>" description="<?=$default_computer_description?>"></i>
                                            <i class="folder icon"></i>
                                            <div class="content">
                                                <div class="header"><?=$default_computer_ou?>(<?=$default_computer_description?>)</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--<div class="ldap_tree_content"></div>-->
                            </div>
                        </div>
                        <div class="ui centered inline loader"></div>
                        <div class="ui modal">
                            <i class="close icon"></i>
                            <div class="header">電腦編輯</div>
                            <div class="content">
                                <form class="ui form" action="/ajax/do_ldap" method="get">
                                    <div class='fields'>
                                        <div class='six wide field'>
                                            <i class="desktop blue icon"></i> <span name="cn"></span>
                                            <input type="hidden" name="cn">
                                            <input type="hidden" name="type" value="changecomputer">
                                        </div>
                                        <div class='ten wide field'>
                                            <div class='ui toggle checkbox'>
                                                <input type='checkbox' name='isActive' value="true">
                                                <label>是否啟用</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class='inline fields'>
                                        <label for='isYonghua'>市政中心</label>
                                        <div class='field'>
                                            <div class='ui radio checkbox'>
                                                <input type='radio' name='isYonghua' value='true' checked='checked'>
                                                <label>永華</label>
                                            </div>
                                            <div class='ui radio checkbox'>
                                                <input type='radio' name='isYonghua' value='false'>
                                                <label>民治</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <label>移動單位</label>
                                        <input list="brow" name="moveOU" placeholder='請選擇ou' >
                                        <datalist id="brow" name="moveOU">
                                            <?php foreach($OUs as $ou): ?>
                                               <?php $ou['description'] = empty($ou['description']) ? "" : $ou['description']?> 
                                               <option value="<?=$ou['name']?>(<?=$ou['description']?>)"></option>
                                            <?php endforeach ?>
                                        </datalist>
                                    </div>
                                </form>
                            </div>
                            <div class="actions">
                                <div class="ui approve button">送出</div>
                                <div class="ui cancel button">取消</div>
                            </div>
                        </div>
                    </div><!--end of .post_cell-->
                <?php endif ?>
			</div><!--end of .post-->
		</div><!--end of .sub-content-->
		<div style="clear: both;">&nbsp;</div>
	</div><!-- end #content -->
</div> <!--end #page-->
