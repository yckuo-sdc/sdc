<!--tool_nmap-->
<div id="page" class="container">
	<div id="content">
		<div class="sub-content show">
			<div class="post nslookup">
				<div class="post_title">Nslookup</div>
				<div class="post_cell">
					<form class="ui form" action="javascript:void(0)">
						<div class="field">
							<label>Domain name</label>
							<div class="ui input">
								<?php $target = "www.tainan.gov.tw 8.8.8.8"?>
								<input type="text" name="target" value="<?php echo $target;?>" placeholder="<?php echo $target;?>">
							</div>
						</div>
						<div class="field">
							<label>DNS</label>
							<button type="submit" id="nslookup_btn" class="ui button">Query</button>
							<div class="ui centered inline loader"></div>
						</div>
						<!--<div class="ui message">
							<div class="header"><a href='https://nmap.org/nsedoc/' target='_blank'>Nmap Scripts</a></div>
							<ul class="list">
							  <li></li>
							</ul>
						</div>-->
					</form>
					<div class="record_content"></div>
				</div> <!-- end .post_cell-->
			</div> <!-- end .post-->
		</div> <!-- end .sub-content-->
		<div style="clear: both;">&nbsp;</div>
	</div><!-- end #content -->
</div> <!--end #page-->
