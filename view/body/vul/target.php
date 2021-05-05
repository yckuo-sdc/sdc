<div id="page" class="container">
	<div id="content">
		<div class="sub-content show">
			<div class="post">
                <?=$route->createBreadcrumbs(' > ');?>
                <h2 class="ui dividing header">掃描資產</h2>
				<div class="post_cell">
				<?php if($last_num_rows==0): ?>
					<p>查無此筆紀錄</p>
				<?php else: ?>
			        <p>共有<?=$last_num_rows?>筆資產(含<?=$host_num?>個掃描主機,<?=$url_num?>筆掃描網站)！</p>
                    <table class="ui celled table">
                        <thead>
                            <tr>
                            <th>ou</th>
                            <th>ip</th>
                            <th>host name</th>
                            <th>system name</th>
                            <th>domain</th>
                            <th>manager</th>
                            <th>email</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach($scanTarget as $Target): ?>
                            <?php
                                $system_names = explode(";", $Target['system_name']);
                                $domains = explode(";", $Target['domain']);
                                $size = count($domains);
                            ?>
                            <?php for($i=0;$i<$size;$i++): ?>
                                <?php if($i==0): ?>
                                    <tr>
                                    <td rowspan=<?=$size?>><?=str_replace('/臺南市政府/','',$Target['ou'])?></td>
                                    <td rowspan=<?=$size?>><?=$Target['ip']?></td>
                                    <td rowspan=<?=$size?>><?=$Target['hostname']?></td>
                                    <td><?=$system_names[$i]?></td>
                                    <td><a href='<?=$domains[$i]?>' target='_blank'><?=$domains[$i]?></a></td>
                                    <td rowspan=<?=$size?>><?=$Target['manager']?></td>
                                    <td rowspan=<?=$size?>><?=$Target['email']?></td>
                                    </tr>
                                <?php else: ?>
                                    <tr>
                                    <td><?=$system_names[$i]?></td>
                                    <td><a href='<?=$domains[$i]?>' target='_blank'><?=$domains[$i]?></a></td>
                                    </tr>
                                <?php endif	?>
                            <?php endfor ?>
                        <?php endforeach ?>
					    </tbody>
                    </table>
                <?php endif	?>
				</div><!--end of .post_cell-->
			</div><!--end of .post-->
		</div><!--end of .sub-content-->
		<div style="clear: both;">&nbsp;</div>
	</div><!-- end #content -->
</div> <!--end #page-->
