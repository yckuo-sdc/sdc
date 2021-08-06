<?php
$sql = "SELECT ou, sum(total_VUL) AS total_VUL, sum(fixed_VUL) AS fixed_VUL FROM view_system_vuls GROUP BY ou ORDER BY ou";
$ou_vuls = $db->execute($sql,[]);
$rowcount = $db->getLastNumRows();

$sql = "SELECT COUNT(*) AS count FROM view_system_vuls";
$rowcount_scan = $db->execute($sql, [])[0]['count'];

$sql_details = "SELECT system_name, sum(total_VUL) AS total_VUL ,sum(fixed_VUL) AS fixed_VUL
FROM(
        SELECT system_name, '0' AS total_VUL, '0' AS fixed_VUL FROM scanTarget WHERE ou LIKE :ou1 
    UNION ALL
        SELECT system_name, count(system_name) AS total_VUL, sum(CASE WHEN status IN ('已修補','豁免','誤判') THEN 1 ELSE 0 END) AS fixed_VUL FROM scan_results WHERE ou LIKE :ou2 GROUP BY system_name ORDER BY system_name
)U 
GROUP BY system_name ORDER BY system_name";

$sql_targets = "SELECT * FROM scanTarget WHERE ou LIKE :ou";
?>

共有 <?=$rowcount?> 個單位, <?=$rowcount_scan?> 筆掃描設備(含歷史紀錄)！<br><br>
<div class='ui cards'>
    <?php foreach($ou_vuls as $ou_vul): ?>
        <?php $details = $db->execute($sql_details, [':ou1' => '/臺南市政府/' . $ou_vul['ou'], ':ou2' => '/臺南市政府/' . $ou_vul['ou'] ]); ?>
        <?php $targets = $db->execute($sql_targets, [':ou' => '/臺南市政府/' . $ou_vul['ou'] ]); ?>
        <div class='ou_block red card'>
            <div class='content'>
            	<div class='header'><i class='user circle icon'></i><?=$ou_vul['ou']?></div>
               	<div class="ui blue big label" style="width: 100%">
					<?=$ou_vul['total_VUL']?> 
					<div class="detail">弱點數</div>
				</div>
				<div class="ui green big label" style="width: 100%">
					<?=$ou_vul['fixed_VUL']?>
					<div class="detail">修補數</div>
				</div> 
               <!--<div style='background-color:#009efb; text-align:center; padding:2%; line-height:1.5'>
                    <h3><?=$ou_vul['total_VUL']?></h3>
                    <h5 style='margin: 2% 0%;'>vulnerabilities</h5>
                </div>
                <div style='background-color:#55ce63; text-align:center; padding:2%'>
                    <h3><?=$ou_vul['fixed_VUL']?></h3>
                    <h5 style='margin: 2% 0%;'>fixed items</h5>
                </div>-->
                <div class="ui list">
                    <?php foreach($details as $detail): ?>
                      <div class="item">
                        <div class="header"><?=$detail['system_name']?></div>
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
