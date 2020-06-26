<!--index.php-->
<?php 
	require_once("login/function.php");
	# fetch and update Session ID with sso_verify_vision.php
	if(isset($_GET['sid']))	session_id($_GET['sid']);	
	session_start(); 
	if(verifyBySession_Cookie("account")){
		if(isset($_GET['mainpage']) AND !empty($_GET['mainpage']))$page = $_GET['mainpage'];
		else													  $page = "info";
		require("mysql_connect.inc.php");
		storeUserLogs($conn,'pageSwitch',$_SERVER['REMOTE_ADDR'],$_SESSION['account'],$_SERVER['REQUEST_URI'],date('Y-m-d h:i:s'));
		$conn->close();
		require('header.php'); // 載入共用的頁首
?>
		<div class="ui vertical inverted sidebar menu left" id="toc">
			<?php require('sidebar.php'); // 載入共用的側欄?>
		</div>
		<?php require('menu.php'); // 載入共用的選單?>
		<div class="pusher">
			<div class="full height">
				<div class="toc">
					<div class="ui vertical inverted sidebar menu left overlay visible">
						<?php require('sidebar.php'); // 載入共用的側欄?>
					</div>
				</div>
				<div class="article">
				<?php
				switch($page){  // 依照 GET 參數載入共用的內容
					case "about":
					  require('about.php');
					break;
					case "info":
					  require('info.php');
					break;
					case "query":
					  require('query.php');
					break;
					case "vul":
					  require('vul.php');
					break;
					case "tool":
					  require('tool.php');
					break;
				}
				require('footer.php'); // 載入共用的頁尾
				?>
				</div>
			</div>
		</div>
	</body>
	</html>
<?php 
	} 
?>

