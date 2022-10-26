<div id="page" class="container">
	<div id="content">
		<div class="sub-content show">
			<div class="post">
                <?=$route->createBreadcrumbs(' > ');?>
                <h2 class="ui dividing header">掃描資產(雲嘉嘉南)</h2>
				<div class="post_cell">
					<div class="ui secondary pointing menu">
						<a class="active item">Targets</a>
						<a class="item">Certificate warnings</a>
						<a class="item">Insecure http services</a>
					</div>
					<div class="ui noborder bottom attached segment">
						<div class="tab-content yonghua show">
                            <?php if($last_num_rows==0): ?>
                                <p>查無此筆紀錄</p>
                            <?php else: ?>
                                <p>共有 <?=$last_num_rows?> 筆資產(含 <?=$host_num?> 個掃描主機, <?=$url_num?> 筆掃描網站)！</p>
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
                                                <td rowspan=<?=$size?>><?=$Target['ou']?></td>
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
						</div> <!-- end of .tabular-->
						<div class="tab-content minjhih">
                            <?php if(empty($failure_num)): ?>
                                <p>查無此筆紀錄</p>
                            <?php else: ?>
                                <p>共有 <?=$failure_num?> 筆紀錄(含1個月內到期)！</p>
                            <?php endif ?>
                            <table class="ui celled table">
                                <thead>
                                    <tr>
                                        <th>ou</th>
                                        <th>system name</th>
                                        <th>manager</th>
                                        <th>url</th>
                                        <th>status</th>
                                        <th>valid from time</th>
                                        <th>valid to time</th>
                                        <th>date diff</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach($web_cert_failures as $web_cert_failure): ?>
                                    <?php
                                    $tr_class = "";
                                    $tr_class = $web_cert_failure['date_diff'] < 0 ? "warning" : $tr_class; 
                                    $tr_class = $web_cert_failure['status'] != "Success" ? "error" : $tr_class;
                                    ?>
                                    <tr class='<?=$tr_class?>'>
                                        <td><?=$web_cert_failure['ou']?></td>
                                        <td><?=$web_cert_failure['system_name']?></td>
                                        <td><?=$web_cert_failure['manager']?></td>
                                        <td>
                                            <a href="<?=$web_cert_failure['url']?>" target="_blank">
                                                <?=$web_cert_failure['url']?>
                                            </a>
                                        </td>
                                        <td><?=$web_cert_failure['status']?></td>
                                        <td><?=$web_cert_failure['valid_from_time']?></td>
                                        <td><?=$web_cert_failure['valid_to_time']?></td>
                                        <td><?=$web_cert_failure['date_diff']?></td>
                                    </tr>
                                <?php endforeach ?>
                                </tbody>
                            </table>
						</div> <!-- end of .tabular-->
						<div class="tab-content">
                            <?php if(empty($redirect_to_https_failure_num)): ?>
                                <p>查無此筆紀錄</p>
                            <?php else: ?>
                                <p>共有 <?=$redirect_to_https_failure_num?> 筆紀錄！</p>
                            <?php endif ?>
                            <table class="ui celled table">
                                <thead>
                                    <tr>
                                        <th>ou</th>
                                        <th>system name</th>
                                        <th>manager</th>
                                        <th>url</th>
                                        <th>status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach($redirect_to_https_failures as $redirect_to_https_failure): ?>
                                    <?php
                                    $tr_class = "";
                                    ?>
                                    <tr class='<?=$tr_class?>'>
                                        <td><?=$redirect_to_https_failure['ou']?></td>
                                        <td><?=$redirect_to_https_failure['system_name']?></td>
                                        <td><?=$redirect_to_https_failure['manager']?></td>
                                        <td>
                                            <a href="<?=$redirect_to_https_failure['url']?>" target="_blank">
                                                <?=$redirect_to_https_failure['url']?>
                                            </a>
                                        </td>
                                        <td><?=$redirect_to_https_failure['status']?></td>
                                    </tr>
                                <?php endforeach ?>
                                </tbody>
                            </table>
						</div> <!-- end of .tabular-->
					</div> <!-- end of .attached.segment-->
				</div><!--end of .post_cell-->
			</div><!--end of .post-->
		</div><!--end of .sub-content-->
		<div style="clear: both;">&nbsp;</div>
	</div><!-- end #content -->
</div> <!--end #page-->
