<?php
$sql = "SELECT oid, ou, sum(total_VUL) AS total_VUL, sum(fixed_VUL) AS fixed_VUL FROM view_system_vuls GROUP BY oid, ou ORDER BY oid";
$ou_vuls = $db->execute($sql,[]);
$rowcount = $db->getLastNumRows();

$sql = "SELECT COUNT(*) AS count FROM view_system_vuls";
$rowcount_scan = $db->execute($sql, [])[0]['count'];

$sql_details = " SELECT system_name, 
count(system_name) AS total_VUL,
SUM(
	CASE
	WHEN status IN ('已修補','豁免','誤判')
	THEN 1 ELSE 0 END
) AS fixed_VUL,
(
	SELECT count(*) FROM scan_targets 
	WHERE INSTR(CONCAT(',', GROUP_CONCAT(distinct scan_results.ip), ','), CONCAT(',', ip, ','))
) > 0 AS living
FROM scan_results
WHERE oid LIKE :oid
GROUP BY system_name
ORDER BY living DESC, system_name ASC";

$sql_targets = "SELECT * FROM scan_targets WHERE oid LIKE :oid";
?>

共有 <?=$rowcount?> 個單位, <?=$rowcount_scan?> 筆掃描設備(含歷史紀錄)！<br><br>
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
					<?=$ou_vul['total_VUL']?> 
					<div class="detail">弱點數</div>
				</div>
				<div class="ui green big label" style="width: 100%">
					<?=$ou_vul['fixed_VUL']?>
					<div class="detail">修補數</div>
				</div> 
                <div class="ui list">
                    <?php foreach($details as $detail): ?>
						<?php 
						$render_system_name = empty($detail['living']) ? $detail['system_name'] . "&nbsp<div class='ui grey label'>已下架</div>" : $detail['system_name']; 
						?>
                        <div class="item">
                        	<div class="header"><?=$render_system_name?></div>
                        	<?=$detail['total_VUL']?>/<?=$detail['fixed_VUL']?>				
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
