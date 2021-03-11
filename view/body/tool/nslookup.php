<div id="page" class="container">
	<div id="content">
		<div class="sub-content show">
			<div class="post nslookup">
                <h2 class="ui dividing header">Nslookup</h2>
				<div class="post_cell">
					<form class="ui form" action="">
						<div class="field">
							<label>Domain name</label>
							<div class="ui input">
								<?php $target = "www.tainan.gov.tw 8.8.8.8"?>
								<input type="text" name="target" value="<?php echo $target;?>" placeholder="<?php echo $target;?>">
							</div>
						</div>
						<div class="field">
							<label>DNS</label>
							<input type="submit" id="nslookup_btn" class="ui button" value="Query">
							<div class="ui centered inline loader"></div>
						</div>
					</form>
					<div class="record_content"></div>
				</div> <!-- end .post_cell-->
			</div> <!-- end .post-->
		</div> <!-- end .sub-content-->
		<div style="clear: both;">&nbsp;</div>
	</div><!-- end #content -->
</div> <!--end #page-->
