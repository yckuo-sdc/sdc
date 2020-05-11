<?php session_start();?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>臺南市政府 智慧發展中心</title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700|Archivo+Narrow:400,700" rel="stylesheet" type="text/css">
<link rel="shortcut icon" href="../images/logo.ico" />

<!-- add jQuery-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>

<!-- semantic ui -->
<link rel="stylesheet" type="text/css" href="../node_modules/semantic/semantic.css">
<script src="../node_modules/semantic/semantic.js"></script>
<!-- add my JS-->
<script src="js/login.js"></script>

<!--[if IE 6]>
<link href="default_ie6.css" rel="stylesheet" type="text/css" />
<![endif]-->
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

</head>
<body>
	<div class="ui middle aligned center aligned grid">
	  <div class="column">
		<h2 class="ui sdc-blue image header">
		  <div class="content">
			<i class="shield icon"></i>Log-in to your account
		  </div>
		</h2>
		<form class="ui large form" method="post" action="actionlogin.php">
		  <div class="ui stacked segment">
			<div class="inline fields">
    			<label for="verification">type</label>
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
				<label>Keep Me Logged In</label>
			  </div>
			</div>
			<!--<div class="ui fluid large teal submit button">-->
				
				<button class="ui sdc-blue fluid large button" type="submit">Login</button>
				<input type="hidden" name="refer" value="<?php echo (isset($_GET['refer'])) ? $_GET['refer'] : 'login.php'; ?>">
				
				<!--</div>-->
		  </div>
			<?php
				if(isset($_SESSION["error"])){
				echo "<div class='ui error message' style='display:block'>";
					$error = $_SESSION["error"];	
					echo "<span>$error</span>";
				echo "</div>";
				}
			?>

		</form>
		<div class="ui message">
			SDC-ISS System
		 </div>
	  </div>
	</div>

</body>
</html>
<?php unset($_SESSION["error"]); ?>
