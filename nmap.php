<!--nmap.php-->
<div id="page" class="container">
	<div id="content">
		<div class="sub-content show">
			<div class="post">
				<div class="post_title">Nmap</div>
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
		<div class="sub-content">
			<div class="post">
				<div class="post_title">Application System</div>
					<div class="post_cell">
						<div class="post_table">
						 <?php //select data form database
							require("mysql_connect.inc.php");
							 //select row_number,and other field value
							$sql = "SELECT * FROM application_system";
							$result = mysqli_query($conn,$sql);
							$num_total_entry = mysqli_num_rows($result);
							
							echo "<table>";
							echo "<colgroup>";
								echo "<col width='10%'/>";
								echo "<col width='15%'/>";
								echo "<col width='25%'/>";
								echo "<col width='50%'/>";
							echo "</colgroup>";
							echo "<tbody>";	
							echo "<tr>";
								echo "<th>系統名稱</th>";
								echo "<th>IP</th>";
								echo "<th>網址</th>";
								echo "<th>Scan Result</th>";
							echo "</tr>";
                   			 while($row = mysqli_fetch_assoc($result)) {
								echo "<tr>";
									echo "<td>".$row['Name']."</td>";
									echo "<td>".$row['IP']."</td>";
									echo "<td><a href='".$row['URL']."' target='_blank'>".$row['URL']."</a></td>";
									echo "<td>".nl2br($row['Scan_Result'])."</td>";
								echo "</tr>";
							 }
							echo "</tbody>";
							echo "</table>";

							$conn->close();
						?>

					</div>
				</div>
			</div>
		</div>
		<div style="clear: both;">&nbsp;</div>
	</div>
	
	<!-- end #content -->

