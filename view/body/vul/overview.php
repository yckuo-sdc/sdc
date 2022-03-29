<div id="page" class="container">
    <div id="content">
		<div class="sub-content show">
			<div class="post vul_overview">
                <?=$route->createBreadcrumbs(' > ');?>
                <h2 class="ui dividing header">整體數據(雲嘉嘉南)</h2>
				<div class="post_cell">
                    共有 <?=$rowcount?> 個單位(含歷史資料)!<br><br>
                    <div class='ui cards'>
                        <?php foreach($ou_vuls as $ou_vul): ?>
                            <?php $details = $db->execute($sql_details, [':oid' => $ou_vul['oid'] ]); ?>
                            <?php $targets = $db->execute($sql_targets, [':oid' => $ou_vul['oid'] ]); ?>
                            <div class='ou_block red card'>
                                <div class='content'>
                                    <div class='header'>
                                        <i class='user circle icon'></i><?=createBreadCrumbsWithOu($ou_vul['ou'])?>
                                    </div>
                                    <div class="ui blue big label" style="width: 100%">
                                        <?=$ou_vul['number']?> 
                                        <div class="detail">弱點數</div>
                                    </div>
                                    <div class="ui green big label" style="width: 100%">
                                        <?=$ou_vul['fixed_number']?>
                                        <div class="detail">修補數</div>
                                    </div> 
                                    <div class="ui list">
                                        <?php foreach($details as $detail): ?>
                                            <?php 
                                            $render_system_name = empty($detail['system_living']) ? $detail['system_name'] . "&nbsp<div class='ui grey label'>已下架</div>" : $detail['system_name']; 
                                            ?>
                                            <div class="item">
                                                <div class="header"><?=$render_system_name?></div>
                                                <?=$detail['number']?>/<?=$detail['fixed_number']?>				
                                            </div>
                                        <?php endforeach ?>
                                    </div>
                                    <a><div style='cursor:pointer'><i class='icon caret right'></i>掃描資產</div></a>
                                    <div class='description'>
                                        <ol>
                                        <?php foreach($targets as $target): ?> 
                                            <?php $system_names = explode(";", $target['system_name']); ?>
                                            <?php $domains = explode(";", $target['domain']); ?>
                                            <?php $size = count($domains); ?>
                                            <li>
                                                <?=$target['hostname']?>  |  <?=$target['ip']?> | 
                                                <?php for($i=0; $i<$size; $i++): ?>
                                                    <a href='<?=$domains[$i]?>' target='_blank'><?=$system_names[$i]?></a> 
                                                <?php endfor ?>
                                                 | <?=$target['manager']?> | <?=$target['email']?>
                                            </li>
                                            <div class='ui divider'></div>
                                        <?php endforeach ?>
                                        </ol> 
                                    </div><!--End of .description--> 
                                </div><!--End of .contents--> 
                            </div><!--End of .ou_block--> 
                        <?php endforeach ?>
                    </div><!--End of .ui.cards-->
				</div><!--end of .post_cell-->
			</div><!--end of .post-->
		</div><!--end of .sub-content-->
		<div style="clear: both;">&nbsp;</div>
	</div><!-- end #content -->
</div> <!--end #page-->
