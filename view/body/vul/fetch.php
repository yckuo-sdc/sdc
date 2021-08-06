<div id="page" class="container">
    <div id="content">
		<div class="sub-content show">
			<div class="post">
                <?=$route->createBreadcrumbs(' > ');?>
                <h2 class="ui dividing header">VUL 擷取</h2>
				<div class="post_cell">
                    <form class="ui form" action="javascript:void(0)">
                        <div class="field">
                            <label>nowTime</label>
                            <?php echo $nowTime; ?>
                        </div>
                        <div class="field">
                            <label>host json</label>
                            <?php echo "<a href='".$host_url."' target='_blank'>".$host_url."</a>"; ?>
                        </div>
                        <div class="field">
                            <label>web json</label>
                            <?php echo "<a href='".$web_url."' target='_blank'>".$web_url."</a>"; ?>
                        </div>
                        <div class="field">
                            <label>target json</label>
                            <?php echo "<a href='".$target_url."' target='_blank'>".$target_url."</a>"; ?>
                        </div>
                    </form>
                    <pre></pre>
                    <?php foreach($vul_api as $api): ?>
                        <?=$api['name']?>: update <?=$api['data_number']?> records on <?=$api['updated_at']?><br>
                    <?php endforeach ?>
                    <button id="vs_btn" class="ui button">Fetch VUL</button>
                    <div class="ui centered inline loader"></div>
                    <div class="fetch_status"></div>
				</div><!--end of .post_cell-->
			</div><!--end of .post-->
		</div><!--end of .sub-content-->
		<div style="clear: both;">&nbsp;</div>
	</div><!-- end #content -->
</div> <!--end #page-->
