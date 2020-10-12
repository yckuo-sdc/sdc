<!--index-->
<?php 
require 'vendor/autoload.php';

if(isset($_GET['sid']))	session_id($_GET['sid']);	# fetch and update Session ID with sso_verify_vision.php

session_start(); 
if(!verifyBySession_Cookie("account")){
	return ;
}

if(isset($_GET['mainpage']) AND !empty($_GET['mainpage']))$mainpage = $_GET['mainpage'];
else													  $mainpage = "info";

$controller_array = scandir('controller');
$controller_array = array_change_key_case($controller_array, CASE_LOWER);

$db = Database::get();
storeUserLogs2($db, 'pageSwitch', $_SERVER['REMOTE_ADDR'], $_SESSION['account'], $_SERVER['REQUEST_URI']);

require 'view/header.php'; 
?>
	<div class="ui vertical inverted sidebar menu left" id="toc">
		<?php require 'view/sidebar.php'; ?>
	</div>
	<?php require 'view/nav.php'; ?>
	<div class="pusher">
		<div class="full height">
			<div class="toc">
				<div class="ui vertical inverted sidebar menu left overlay visible">
					<?php require 'view/sidebar.php'; ?>
				</div>
			</div>
			<div class="article">
			<?php
			if (in_array($mainpage.'.php', $controller_array)) {
				  require 'controller/'.$mainpage.'.php';
			}else{
				  require 'controller/login.php';
			}

			require 'view/footer.php'; 
			?>
			</div>
		</div>
	</div>
</body>
</html>
