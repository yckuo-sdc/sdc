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
		include('header.php'); // 載入共用的頁首
?>
		<div class="ui vertical inverted sidebar menu left" id="toc">
			<?php include('sidebar.php'); // 載入共用的側欄?>
		</div>
		<?php include('menu.php'); // 載入共用的選單?>
		<div class="pusher">
			<div class="full height">
				<div class="toc">
					<div class="ui vertical inverted sidebar menu left overlay visible">
						<?php include('sidebar.php'); // 載入共用的側欄?>
					</div>
				</div>
				<div class="article">
				<?php
				switch($page){  // 依照 GET 參數載入共用的內容
					case "about":
					  include('about.php');
					break;
					case "info":
					  include('info.php');
					break;
					case "query":
					  include('query.php');
					break;
					case "vulnerability":
					  include('vulnerability.php');
					break;
					case "tool":
					  include('tool.php');
					break;
				}
				include('footer.php'); // 載入共用的頁尾
				?>
				</div>
			</div>
		</div>
	</body>
	</html>
<?php 
	} 
?>

