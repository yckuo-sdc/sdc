<!--about-->
<?php 
if(!verifyBySession_Cookie("account")){
	return ;
}
$account = $_SESSION['account'];
storeUserLogs2($db, 'pageSwitch', $_SERVER['REMOTE_ADDR'], $account, $_SERVER['REQUEST_URI']);
require 'view/header.php'; 

$subpage = strtolower($route->getParameter(2));

switch($subpage){
	case 'data': load_about_data(); break;
	default: load_about_data(); break;
}

function load_about_data(){
	$db = Database::get();
	$table = "api_list"; // 設定你想查詢資料的資料表
	$condition = "1 = ?";
	$api_list = $db->query($table, $condition, $order_by = "1", $fields = "*", $limit = "", [1]);
?>
<div id="page" class="container">
	<div id="content">
		<div class="sub-content show">
			<div class="post">
				<div class="post_title">Data-Stream Status</div>
					<div class="post_cell">
						<table class="ui celled table">
						<thead>
							<tr>
							<th>種類</th>
							<th>名稱</th>
							<th>資料格式</th>
							<th>擷取數量</th>
							<th>更新時間</th>
							<th>網址</th>
						</tr>
						</thead>	
						<tbody>	
						 <?php
						foreach($api_list as $api){
							echo "<tr>";
								echo "<td>".$api['class']."</td>";
								echo "<td>".$api['name']."</td>";
								echo "<td>".$api['data_type']."</td>";
								echo "<td>".$api['data_number']."</td>";
								echo "<td>".$api['last_update']."</td>";
								echo "<td><a href='".$api['url']."' target='_blank'>".$api['url']."</a></td>";
							echo "</tr>";
						 }
						?>
						</tbody>
						</table>
				</div>
			</div>
		</div>
		<div style="clear: both;">&nbsp;</div>
	</div>
</div> <!--end #page-->
<?php } 
require 'view/footer.php'; 
?>	
<!-- end #content -->

