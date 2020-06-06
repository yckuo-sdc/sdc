<!--nmap.php-->
<div id="page" class="container">
	<div id="content">
		<div class="sub-content show">
			<div class="post">
				<div class="post_title">Data-Stream Status</div>
					<div class="post_cell">
						<table class="ui celled table">
						 <?php //select data form database
							require("mysql_connect.inc.php");
							 //select row_number,and other field value
							$sql = "SELECT api_status.*,api_list.* FROM api_status,api_list WHERE api_status.id IN(SELECT MAX(id) FROM api_status WHERE api_status.status=200 AND api_status.api_id = api_list.id GROUP BY api_id)";
							$result = mysqli_query($conn,$sql);
							$num_total_entry = mysqli_num_rows($result);
							
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
                   			 while($row = mysqli_fetch_assoc($result)) {
								echo "<tr>";
									echo "<td>".$row['class']."</td>";
									echo "<td>".$row['name']."</td>";
									echo "<td>".$row['data_type']."</td>";
									echo "<td>".$row['data_number']."</td>";
									echo "<td>".$row['last_update']."</td>";
									echo "<td><a href='".$row['url']."' target='_blank'>".$row['url']."</a></td>";
								echo "</tr>";
							 }
							echo "</tbody>";
							echo "</table>";

							$conn->close();
						?>
				</div>
			</div>
		</div>
		<div style="clear: both;">&nbsp;</div>
	</div>
	
	<!-- end #content -->

