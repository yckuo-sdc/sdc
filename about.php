<!--about-->
<?php 
if(isset($_GET['subpage'])) $subpage = $_GET['subpage'];
else 						$subpage = 'data';

switch($subpage){
	case 'data': load_about_data(); break;
}

function load_about_data(){
	$db = Database::get();
	$table = "api_list"; // 設定你想查詢資料的資料表
	$condition = "1";
	$api_list = $db->query($table, $condition, $order_by = "1", $fields = "*", $limit = "");
?>
<div id="page" class="container">
	<div id="content">
		<div class="sub-content show">
			<div class="post">
				<div class="post_title">Data-Stream Status</div>
					<div class="post_cell">
						<table class="ui celled table">
						 <?php
						echo "<thead>";	
						echo "<tr>";
							echo "<th>種類</th>";
							echo "<th>名稱</th>";
							echo "<th>資料格式</th>";
							echo "<th>擷取數量</th>";
							echo "<th>更新時間</th>";
							echo "<th>網址</th>";
						echo "</tr>";
						echo "</thead>";	
						echo "<tbody>";	
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
						echo "</tbody>";
						echo "</table>";
						?>
				</div>
			</div>
		</div>
		<div style="clear: both;">&nbsp;</div>
	</div>
</div> <!--end #page-->
<?php } ?>	
	<!-- end #content -->

