<!--query_contact-->
<div id="page" class="container">
<div id="content">
		<div class="sub-content show">
			<div class="post contact">
                <h2 class="ui dividing header">資安聯絡人</h2>
				<div class="post_cell">
					<form class="ui form" action="javascript:void(0)">
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
							<button type="button" id="show_all_btn" class="ui button" onclick="window.location.href='/query/contact/'">顯示全部</button>
						</div>
					</div>
					</form>
					<div class="record_content">
					<?php
					if($last_num_rows==0){
						echo "查無此筆紀錄";
					}else{
						echo "共有".$last_num_rows."筆資料！";
						echo "共有".$oid_num."個機關！";
					?>
					<div class='ui relaxed divided list'>
					<?php foreach($contacts->data as $contact){ ?>
							<div class='item'>
							<div class='content'>
								<a>
								<?=$contact['organization']?>&nbsp&nbsp
								<?php if( !empty($contact['rank'] )) ?><span style='color:#f80000'><?=$contact['rank']?></span>&nbsp&nbsp
								<?=$contact['person_name']?>&nbsp&nbsp
								<span style='background:#fde087'><?=$contact['person_type']?></span>&nbsp&nbsp
								<?=$contact['email']?>&nbsp&nbsp
								<span style='background:#DDDDDD'><?=$contact['tel']."#".$contact['ext']?></span>&nbsp&nbsp
								<i class='angle down icon'></i>
								</a>
								<div class='description'>
									<ol>
									<li>序號<?=$contact['CID']?></li>
									<li>OID<?=$contact['OID']?></li>
									<li>資安責任等級<?=$contact['rank']?></li>
									<li>機關名稱<?=$contact['organization']?></li>
									<li>單位名稱<?=$contact['unit']?></li>
									<li>姓名<?=$contact['person_name']?></li>
									<li>職稱<?=$contact['position']?></li>
									<li>資安聯絡人類型<?=$contact['person_type']?></li>
									<li>地址<?=$contact['address']?></li>
									<li>電話<?=$contact['tel']?></li>
									<li>分機<?=$contact['ext']?></li>
									<li>傳真<?=$contact['fax']?></li>
									<li>email<?=$contact['email']?></li>
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
