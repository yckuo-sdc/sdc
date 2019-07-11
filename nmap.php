<!--nmap.php-->
<div id="content">
		<div class="sub-content show">
			<div class="post">
				<form class="ui form" action="javascript:void(0)">
 				<!--<div class="fields">-->
			    	<div class="field">
						<label>Target</label>
						<div class="ui input">
							<?php $target = "localhost vision.tainan.gov.tw 10.7.102.4";?>
							<input type="text" id="target" value="<?php echo $target;?>" placeholder="<?php echo $target;?>">
						</div>
					</div>
					<div class="field">
						<label>Nmap</label>
						<button id="nmap_btn" class="ui button">Scan</button>
						<div class="ui centered inline loader"></div>
					</div>
				<!--</div>-->	
				</form>

			<div class="nmap_content"></div>

						
			</div>
		</div>
		<div style="clear: both;">&nbsp;</div>
	</div>
	
		<!-- end #content -->
		<div id="sidebar" class="info_sidebar">
			<ul>
				<li>
					<h2>Nmap</h2>
					<ul>
						<li class="active title"><a>Nmap</a></li>
					</ul>
				</li>
		
			</ul>
		</div>
		<!-- end #sidebar -->
