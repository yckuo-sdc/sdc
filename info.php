<!--info.php-->
<div id="page" class="container">
	<div id="content">
		<div class="sub-content show">
			<div class="post">
				<div class="post_title">Enews Report</div>
					<div class="post_cell">
					<div class="post_table">
               		 <?php //select data form database
                    	require("mysql_connect.inc.php");
                   		 //select row_number,and other field value
                    	$sql = "SELECT OccurrenceTime FROM security_event";
                    	$result = mysqli_query($conn,$sql);
                    	$num_total_entry = mysqli_num_rows($result);
                    	$sql = "SELECT * FROM security_event WHERE Status LIKE '已結案'";
                    	$result = mysqli_query($conn,$sql);
                    	$num_done_entry = mysqli_num_rows($result);
                    	$sql = "SELECT * FROM security_event WHERE Status LIKE '未完成'";
                    	$result = mysqli_query($conn,$sql);
                    	$num_undone_entry = mysqli_num_rows($result);
						$sql = "SELECT * FROM security_event WHERE Status LIKE '未完成' AND NOT(UnprocessedReason LIKE '' )";
                    	$result = mysqli_query($conn,$sql);
                    	$num_exception_entry = mysqli_num_rows($result);
						
						$date_from_week = date('Y-m-d',strtotime('monday this week'));  
						$date_to_week = date('Y-m-d',strtotime('sunday this week'));
						$date_from_month = date('Y-m-d',strtotime('first day of this month'));
						$date_to_month = date('Y-m-d',strtotime('last day of this month'));  

                    	$sql = "SELECT * FROM security_event WHERE OccurrenceTime BETWEEN '".$date_from_month."' AND '".$date_to_month."'";
                    	$result = mysqli_query($conn,$sql);
                    	$num_thisMonth_entry = mysqli_num_rows($result);
                    	$sql = "SELECT * FROM security_event WHERE OccurrenceTime BETWEEN '".$date_from_week."' AND '".$date_to_week."'";
                    	$result = mysqli_query($conn,$sql);
                    	$num_thisWeek_entry = mysqli_num_rows($result);
						$completion_rate = round($num_done_entry/$num_total_entry*100,2)."%"; 
					?>

					<table>
						<colgroup>
							<col width='50%' />
							<col width='50%' />
						</colgroup>
					<tr>
						<th>項目</th>
						<th>數值</th>
					</tr>
					<tr>
						<td>列管數量</td>
						<td><?php echo $num_total_entry ?></td>
					</tr>
					<tr>
						<td>已完成</td>
						<td><?php echo $num_done_entry ?></td>
					</tr>
					<tr>
						<td>未完成</td>
						<td><?php echo $num_undone_entry ?></td>
					</tr>
					<tr>
						<td>無法完成</td>
						<td><?php echo $num_exception_entry ?></td>
					</tr>
						<td>本月已發生</td>
						<td><?php echo $num_thisMonth_entry ?></td>
					</tr>
					<tr>
						<td>本周已發生</td>
						<td><?php echo $num_thisWeek_entry ?></td>
					</tr>
					<tr>
						<td>完成率</td>
						<td><?php echo $completion_rate ?></td>
					</tr>
					</table>
					<?php
                    $conn->close();

					?>
					</div>
					<!--<div id="record_content" class ="post_table"></div>-->
					<div style="clear:both"></div>
					<p></p>
<!--					<ol class="post_cell">
					<center>
						<img class="graduate_img_center" src="images/arch.png"> 						
					</center>	
					</ol>-->
					</div>
			</div>
			<div class="post">
				<div class="post_title">資安事件趨勢圖(最近1個月)</div>
				<div class="cell">
					<div id="chartA" class="chart"></div>	
				</div>
			</div>
		</div>
		<div class="sub-content">
			<div class="post">
				<!--div class="post_block"-->
					<div class="post_title">Compared with last year</div>
					<div class="post_cell">
					繪製長條圖時，長條柱或柱組中線須對齊項目刻度。相較之下，折線圖則是將數據代表之點對齊項目刻度。在數字大且接近時，兩者皆可使用波浪形省略符號，以擴大表現數據間的差距，增強理解和清晰度。
					</div>
				<!--/div-->
					<div id="chartB" class="chart"></div>	
					<button id="show_chart_btn" class="ui button">Plot</button>
				
			</div>
		</div>
		<div class="sub-content">
			<div class="post">
				<div class="post_title">資安類型統計圖</div>
				<div class="post_cell">
					<div id="chartC-2" class="chart"></div>	
			    </div>		
			</div>
			<div class="post">
				<div class="post_title">機關排序TOP10</div>
				<div class="post_cell">
					<div id="chartC" class="chart"></div>	
			    </div>		
			</div>
		</div>
<!--
		<div class="4">
			<div class="post">
				<div class="post_title">ChartD</div>
				<div id="chartD" class="chart"></div>	
				<li>資安事件處理列表回報(TainanGov)</li>
			</div>
		</div>
		<div class="5">
			<div class="post">
				<div class="post_title">ChartE</div>
					<div class="post_cell">
						
						本所現有專任教授 25 名(教授16名*(包含兩位特聘教授)、副教授6名、助理教授3名)。
						</br>(2016.2 製表，圖表內容為2013~2015)
					</div>
				
					<div id='cht_teacher' class='chart'></div>
			</div>
		</div>
		<div class="6">
			<div class="post">
				<div class="post_title">ChartF</div>
					<div class="post_cell">
						本所現有學生人數(碩士班+博士班)一共 299 名: 男261名(87.3%)、女38名(12.7%)，師生比為11.96。
						</br>(2016.2 製表，圖表內容為2013~2015)
					</div>
				
					<div id='cht_student' class='chart'></div>
			</div>
		</div>
		-->
		<div class="sub-content">
			<div class="post">
				<div class="post_title">ChartD</div>
					<div class="post_cell">
				
					
						
						下圖僅先列出前七名本所就業公司(共81人)，另外前三名依次為: 台積電、 聯發科 、華碩。
						(2016.2 製表，圖表內容為2013~2015)
					</div>
					
					<div id='cht_graduate2' class='chart'></div>
					<!--
					<ol class="post_cell">
						<img class="graduate_img_1" src="images/graduate.png"> 
						<img class="graduate_img_2" src="images/row.png">						
					</ol>
					-->
			</div>
		</div>
			<div style="clear: both;">&nbsp;</div>
	</div>
	
		<!-- end #content -->
		
