<!--about-->
<div id="page" class="container">
	<div id="content">
		<div class="sub-content show">
			<div class="post">
                <h2 class="ui dividing header">Data</h2>
				<!--<div class="post_title">Data Status</div>-->
                <div class="post_cell">
                    <table class="ui celled table">
                    <thead>
                        <tr>
                        <th>種類</th>
                        <th>名稱</th>
                        <th>資料格式</th>
                        <th>擷取數量</th>
                        <th>更新時間</th>
                        <th>網址</th>
                    </tr>
                    </thead>	
                    <tbody>	
                    <?php foreach($api_list as $api){ ?>
                        <tr>
                            <td><?=$api['class']?></td>
                            <td><?=$api['name']?></td>
                            <td><?=$api['data_type']?></td>
                            <td><?=$api['data_number']?></td>
                            <td><?=$api['last_update']?></td>
                            <td><a href='<?=$api['url']?>' target='_blank'><?=$api['url']?></a></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                    </table>
				</div><!--end of .post_cell-->
			</div><!--end of .post-->
		</div><!--end of .sub-content-->
		<div style="clear: both;">&nbsp;</div>
	</div><!-- end #content -->
</div> <!--end #page-->
