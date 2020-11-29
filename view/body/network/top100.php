<div id="page" class="container">
<div id="content">
		<div class="sub-content show">
			<div class="post">
				<div class="post_title">Top 100流量排名(最近24小時)</div>
				<div class="post_cell">
                    上次更新：<?=$api['last_update']?>，更新頻率：1小時<br>
					<div class="ui secondary pointing menu">
						<a class="active item">Yonghua</a>
						<a class="item">Minjhih</a>
					</div>
					<div class="ui noborder bottom attached segment">
						<div class="tab-content yonghua show">
                        <table class='ui accordion celled striped table'>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>source</th>
                                <th>bytes</th>
                                <th>sessions</th>
                                <th>application</th>
                                <th>ou</th>
                                <th>name</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach($yh_entries as $entry){ ?>
                            <tr>
                                <td><?=$entry['id']?></td>
                                <td><?=$entry['src_ip']?></td>
                                <td>
                                    <div class="title">
                                        <i class="dropdown icon"></i>
                                        <?=formatBytes($entry['bytes'])?>
                                    </div>
                                    <div class="content">
                                        Sent：<?=formatBytes($entry['bytes_sent'])?><br>
                                        Received：<?=formatBytes($entry['bytes_received'])?>
                                    </div> 
                                </td>
                                <td><?=formatNumbers($entry['sessions'])?></td>
                                <td><?=$entry['app']?></td>
                                <td><?=$entry['ou']?></td>
                                <td>
                                    <?php if($entry['type'] == 'server') { ?>
                                    <i class="server icon"></i>
                                    <?php }elseif($entry['type'] == 'client'){ ?>
                                    <i class="desktop icon"></i>
                                    <?php } ?>
                                    <?=$entry['name']?>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                        </table>
						</div> <!-- end of .tabular-->
						<div class="tab-content minjhih">
                        <table class='ui accordion celled striped table'>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>source</th>
                                <th>bytes</th>
                                <th>sessions</th>
                                <th>application</th>
                                <th>ou</th>
                                <th>name</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach($mj_entries as $entry){ ?>
                            <tr>
                                <td><?=$entry['id']?></td>
                                <td><?=$entry['src_ip']?></td>
                                <td>
                                    <div class="title">
                                        <i class="dropdown icon"></i>
                                        <?=formatBytes($entry['bytes'])?>
                                    </div>
                                    <div class="content">
                                        Sent：<?=formatBytes($entry['bytes_sent'])?><br>
                                        Received：<?=formatBytes($entry['bytes_received'])?>
                                    </div> 
                                </td>
                                <td><?=formatNumbers($entry['sessions'])?></td>
                                <td><?=$entry['app']?></td>
                                <td><?=$entry['ou']?></td>
                                <td>
                                    <?php if($entry['type'] == 'server') { ?>
                                    <i class="server icon"></i>
                                    <?php }elseif($entry['type'] == 'client'){ ?>
                                    <i class="desktop icon"></i>
                                    <?php } ?>
                                    <?=$entry['name']?>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                        </table>
						</div> <!-- end of .tabular-->
					</div> <!-- end of .attached.segment-->
				</div><!--end of .post_cell-->
			</div><!--end of .post-->
		</div><!--end of .sub-content-->
		<div style="clear: both;"></div>
	</div><!-- end #content -->
</div> <!--end #page-->
