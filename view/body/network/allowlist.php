<!--network_allowlist-->
<div id="page" class="container">
<div id="content">
		<div class="sub-content show">
			<div class="post">
                <h2 class="ui dividing header">應用程式核可清單(Client)</h2>
				<div class="post_cell">
                <?php if($status != 'success'){ ?>
                    很抱歉，該分類目前沒有資料！
                <?php }else{ ?>
					<table class='ui celled table'>
					<thead>
						<tr>
							<th>群組名稱</th>
							<th>成員</th>
							<th>應用程式</th>
						</tr>
					</thead>
					<tbody>
					<?php foreach($apps as $app){
                        $app_member = $app->members->member; 
						$size = count($app_member);
						$name = $app->{'@name'};
						foreach($app_member as $key => $member){ ?>
							<tr>
							<?php if($key == 0){ ?>
								<td rowspan=<?=$size?> ><?=$name?></td>
								<td rowspan=<?=$size?> ><?=$size?></td>
								<td><?=$member?></td>	
							<?php }else{ ?>
								<td><?=$member?></td>	
							<?php } ?>
							</tr>
						<?php } ?>
					<?php } ?>
					</tbody>
					</table>
                <?php } ?>
				</div><!--end of .post_cell-->
			</div><!--end of .post-->
		</div><!--end of .sub-content-->
		<div style="clear: both;"></div>
	</div><!-- end #content -->
</div> <!--end #page-->
