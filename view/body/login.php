 <style type="text/css">
    body {
		background-color: #DADADA;
	}
	body > .grid {
		height: 100%;
	}
	.image {
		margin-top: -100px;
	}
	 .column {
		max-width: 450px;
	}

	.field.left{
		text-align:left;
	}	

	.ui.sdc-blue.header {
		color: #4798b3;
	}

	.ui.sdc-blue.button{
		background-color: #4798b3;
		color: #FFFFFF;
	}

	.ui.sdc-blue.button:hover {
    	background-color: #117692;
    	color: #FFFFFF;
		text-shadow: none; 
	}
 </style>

	<div class="ui middle aligned center aligned grid">
	  <div class="column">
		<h2 class="ui sdc-blue image header">
		  <div class="content">
			<i class="shield icon"></i>Log-in to your account
		  </div>
		</h2>
		<?php
		if(isset($_SESSION["error"])){
			foreach($_SESSION["error"] as $error){
				echo "<div class='ui error message' style='display:block'>".$error."</div>";
			}
		}
		?>
		<form class="ui large form" method="post" action="/do_login">
		  <div class="ui stacked segment">
			<div class="inline fields">
    			<label for="verification">Auth</label>
				<div class="field">
				  <div class="ui radio checkbox">
					<input type="radio" name="verification" value="ad" checked="" tabindex="0" class="hidden">
					<label>ad</label>
				  </div>
				</div>
				<div class="field">
				  <div class="ui radio checkbox">
					<input type="radio" name="verification" value="mail" tabindex="0" class="hidden">
					<label>mail</label>
				  </div>
				</div>
			</div>		
			<div class="field">
			  <div class="ui left icon input">
				<i class="user icon"></i>
				<input type="text" name="account" placeholder="Account" required>
			  </div>
			</div>
			<div class="field">
			  <div class="ui left icon input">
				<i class="lock icon"></i>
				<input type="password" name="password" placeholder="Password"  required>
			  </div>
			</div>
			<div class="left field">
			  <div class="ui left checkbox">
				<input type="checkbox" name="remember" value="remember" >
				<label>Remember Me</label>
			  </div>
			</div>
				<button name="submit" class="ui sdc-blue fluid large button" type="submit">Login</button>
				<input type="hidden" name="refer" value="<?php echo (isset($_GET['refer'])) ? $_GET['refer'] : 'login.php'; ?>">	
		  	</div>
		</form>
		<div class="ui message">
			SDC-ISS System
		 </div>
	  </div>
	</div>
	<?php unset($_SESSION["error"]); ?>

