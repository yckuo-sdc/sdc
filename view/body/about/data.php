<div id="page" class="container">
	<div id="content">
		<div class="sub-content show">
			<div class="post">
                <?=$route->createBreadcrumbs(' > ')?>
                <h2 class="ui dividing header">Data</h2>
                <div class="post_title">自動更新
                    <div class="ui <?=$label_color?> label">
                        <?=$today_updated_num?> / <?=$automatic_apis_num?>
                    </div>
                </div>
                <div class="post_cell">
                    <table class="ui celled table">
                        <thead>
                            <tr>
                                <th>種類</th>
                                <th>名稱</th>
                                <th>資料格式</th>
                                <th>更新數量</th>
                                <th>更新頻率</th>
                                <th>更新時間</th>
                                <th>網址</th>
                            </tr>
                        </thead>	
                        <tbody>	
                        <?php foreach($automatic_apis as $api): ?>
                            <?php $tr_class = $api['today_updated'] == 0 ? "negative" : ""; ?>
                            <tr class="<?=$tr_class?>">
                                <td><?=$api['class']?></td>
                                <td><?=$api['name']?></td>
                                <td><?=$api['data_type']?></td>
                                <td><?=$api['data_number']?></td>
                                <td><?=$api['update_frequency']?></td>
                                <td><?=$api['updated_at']?></td>
                                <td><a href='<?=$api['url']?>' target='_blank'><?=$api['url']?></a></td>
                            </tr>
                        <?php endforeach ?>
                        </tbody>
                    </table>
				</div><!--end of .post_cell-->
                <div class="post_title">手動更新
                    <div class="ui grey label">
                        <?=$manual_apis_num?> / <?=$manual_apis_num?>
                    </div>
                </div>
                <div class="post_cell">
                    <table class="ui celled table">
                        <thead>
                            <tr>
                                <th>種類</th>
                                <th>名稱</th>
                                <th>資料格式</th>
                                <th>更新數量</th>
                                <th>更新頻率</th>
                                <th>更新時間</th>
                                <th>網址</th>
                            </tr>
                        </thead>	
                        <tbody>	
                        <?php foreach($manual_apis as $api): ?>
                            <tr>
                                <td><?=$api['class']?></td>
                                <td><?=$api['name']?></td>
                                <td><?=$api['data_type']?></td>
                                <td><?=$api['data_number']?></td>
                                <td><?=$api['update_frequency']?></td>
                                <td><?=$api['updated_at']?></td>
                                <td><a href='<?=$api['url']?>' target='_blank'><?=$api['url']?></a></td>
                            </tr>
                        <?php endforeach ?>
                        </tbody>
                    </table>
				</div><!--end of .post_cell-->
			</div><!--end of .post-->
		</div><!--end of .sub-content-->
		<div style="clear: both;">&nbsp;</div>
	</div><!-- end #content -->
</div> <!--end #page-->
