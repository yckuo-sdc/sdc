<div id="page" class="container">
	<div id="content">
		<div class="sub-content show">
			<div class="post hydra">
                <?=$route->createBreadcrumbs(' > ');?>
                <h2 class="ui dividing header">Hydra</h2>
				<div class="post_title">Hydra Passowrd Cracker</div>
				<div class="post_cell">
				<form class="ui form" action="">
			    	<div class="field">
						<label>Target(IP or Domain name)</label>
						<div class="ui input">
							<?php $target = "192.168.100.127";?>
							<input type="text" name="target" value="<?php echo $target;?>" placeholder="<?php echo $target;?>">
						</div>
					</div>
			    	<div class="field">
						<label>Protocol(ssh, rdp, ftp, smb, http-post-form, etc.)</label>
						<div class="ui input">
							<?php $target = "ssh";?>
							<input type="text" name="protocol" value="<?php echo $target;?>" placeholder="<?php echo $target;?>">
						</div>
					</div>
			    	<div class="field">
						<label>Account</label>
						<div class="ui input">
							<?php $target = "admin";?>
							<input type="text" name="account" value="<?php echo $target;?>" placeholder="<?php echo $target;?>">
						</div>
					</div>
			    	<div class="inline fields">
						<label>Password mode</label>
						<div class='field'>	
							<div class='ui radio checkbox'>
								<input type='radio' name='one_pwd_mode' value='no' onchange="hydra_pwd_mode('no')" tabindex='0' checked>
									<label>Top 100 pwds</label>
							</div>
						</div>
						<div class='field'>	
							<div class='ui radio checkbox'>
								<input type='radio' name='one_pwd_mode' value='yes' onchange="hydra_pwd_mode('yes')" tabindex='0'>
									<label>One pwd</label>
							</div>
						</div>
					</div>
					<div class="field">
						<label>One Pwd</label>
							<div class='ui input'>
								<input type='text' name='self_password' value='' placeholder='One Pwd' disabled>
							</div>
					</div>

					<div class="field">
						<label>Hydra</label>
						<button type="submit" id="hydra_btn" class="ui button">BruteForce</button>
						<div class="ui centered inline loader"></div>
					</div>
					
					</form>

					<div class="record_content"></div>
			
				</div>
				<div class="post_title">Modal Test</div>
				<div class="post_cell">
					<button type="input" id="modal_btn" class="ui button">Modal Show</button>
					<div class="ui modal">
					  <i class="close icon"></i>
					  <div class="header">
						Modal Title
					  </div>
					  <div class="content">
						<form class="ui form" action="/do_modal" method="post">
						  <div class="field">
							<label>First Name</label>
							<input type="text" name="first-name" placeholder="First Name">
						  </div>
						  <div class="field">
							<label>Last Name</label>
							<input type="text" name="last-name" placeholder="Last Name">
						  </div>
						  <div class="field">
							<div class="ui checkbox">
							  <input type="checkbox">
							  <label>I agree to the Terms and Conditions</label>
							</div>
						  </div>
						  <button class="ui button" type="submit">Submit</button>
						</form>
					  </div>
					  <div class="actions">
						<div class="ui cancel button">Cancel</div>
						<div class="ui approve button">OK</div>
					  </div>
					</div>
				</div><!--end of .post_cell-->
			</div><!--end of .post-->
		</div><!--end of .sub-content-->
		<div style="clear: both;">&nbsp;</div>
	</div><!-- end #content -->
</div> <!--end #page-->
