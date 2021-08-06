<div id="page" class="container">
	<div id="content">
		<div class="sub-content show">
			<div class="post nslookup">
                <?=$route->createBreadcrumbs(' > ');?>
                <h2 class="ui dividing header">Nslookup</h2>
				<div class="post_cell">
					<form class="ui form" action="">
						<div class="field">
							<label>Domain name</label>
							<div class="ui input">
								<input type="text" name="target" value="<?=$target?>" placeholder="<?=$target?>">
							</div>
						</div>
						<div class="field">
							<label>Server</label>
							<div class="ui input">
								<input type="text" name="server" value="<?=$server?>" placeholder="<?=$server?>">
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
