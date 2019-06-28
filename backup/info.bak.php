<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<title>臺南市政府 智慧發展中心</title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700|Archivo+Narrow:400,700" rel="stylesheet" type="text/css">
<link rel="shortcut icon" href="images/logo.ico" />

<!-- add jQuery-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>

<!-- node_modules-->
<script src="node_modules/d3js/d3.min.js" charset="utf-8"></script>
<link href="node_modules/c3js/c3.css" rel="stylesheet" type="text/css">
<script src="node_modules/c3js/c3.min.js"></script>
<!-- semantic ui -->
<link rel="stylesheet" type="text/css" href="node_modules/semantic/semantic.css">
<script src="node_modules/semantic/semantic.js"></script>
<!-- template css-->
<link href="css/default.css" rel="stylesheet" type="text/css" media="all" />
<!-- add my CSS-->
<link href="css/add.css" rel="stylesheet"/>
<link href="css/subpage.css" rel="stylesheet"/>

<!-- add my JS-->
<script src="js/add.js"></script>
<script src='js/show_chart.js'></script>

<!--[if IE 6]>
<link href="default_ie6.css" rel="stylesheet" type="text/css" />
<![endif]-->
</head>
<body>

<div id="header-wrapper">
	<div id="banner-img">
		<div class="ui container">	
			<a class="launch icon item"> <i class="content icon"></i></a>
		</div>
		<div id="banner-font" onclick="window.location='info.php';"></div>
		<div id="banner-subtitle">
			<div id="banner-subtitle-menu">
				<ul>
					<li><a class="1" accesskey="1" title="" href="info.php">回首頁</a></li>
					<li class="xx">|</li>
					<li><a class="3" accesskey="3" title="" href="mailto:yckuo@mail.tainan.gov.tw">聯絡我們</a></li>
					<li class="xx">|</li>
					<li><a class="4" accesskey="4" title="" href="login/">登入</a></li>
				</ul>
			</div>
			<input type="search" id="search" placeholder="Search..." />
		</div>
	</div>
	
	<div id="menu">
			<ul>
				<li><a class="1" accesskey="1" title="" href="#">最新消息</a></li>
				<li><a class="2" accesskey="2" title="" href="info.php">圖形化資訊</a></li>
				<li><a class="3" accesskey="3" title="" href="query.php">ISMS資安查詢</a></li>
				<li><a class="4" accesskey="4" title="" href="#">漏洞掃描</a></li>
				<li><a class="5" accesskey="5" title="" href="#">選單E</a></li>
				<li><a class="6" accesskey="6" title="" href="#">相關連結</a></li> 
			</ul>
	</div>
</div>


<div id="page" class="container">
	
	<div class="front" id="1">
	</div>
	
	<div class="front" id="2">
		<div class="sub" onclick='location.href="info.php?1"'>Enews Report</div>
		<div class="sub" onclick='location.href="info.php?2"'>Comparison </div>
		<div class="sub" onclick='location.href="info.php?3"'>Ranking Data</div>
		<div class="sub" onclick='location.href="info.php?4"'>ChartD</div>
	</div>
	
	<div class="front" id="3">
		<div class="sub" onclick='location.href="query.php?1"'>資安查詢</div>
		<div class="sub" onclick='location.href="query.php?2"'>Retrieve GS</div>
		<div class="sub" onclick='location.href="query.php?3"'>sub 3</div>
	</div>
	
	<div class="front"  id="4">
	</div>

	<div class="front"  id="5">
	</div>

	<div class="front"  id="6">
		<div class="sub" onclick="window.open('http://vision.tainan.gov.tw');">推動友善資訊服務平台
		</div>
		<div class="sub" onclick="window.open('http://10.7.102.4/tndev/');">IP查詢系統(芳彬)</div>
		<div class="sub" onclick="window.open('http://10.7.100.100/mrbs/');">SDC會議室預約系統</div>
	</div>


	<div id="content">
		<div class="1 show">
			<div class="post">
				<div class="post_title">Enews Report</div>
					<div class="post_cell">
					<div class="post_table">
               		 <?php //select data form database
                    	require("mysql_connect.inc.php");
                    	$conn = new mysqli($servername, $username, $password, $dbname);
                    	if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);
                    	//set the charset of query
						$conn->query('SET NAMES UTF8');
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
		<div class="2">
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
		<div class="3">
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
		<div class="4">
			<div class="post">
				<div class="post_title">ChartD</div>
					<div class="post_cell">
						
						本所學生畢業後任職公司約有30 % 在IC 設計公司 (含晶圓代工)、約有16% 在IT 系統廠商。
						</br>(2016.2 製表，圖表內容為2013~2015)
					</div>
				
					<div id='cht_graduate1' class='chart'></div>
					
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
		<div id="sidebar" class="info_sidebar">
			<ul>
				<li>
					<h2>圖形化資訊</h2>
					<ul>
						<li class="active 1"><a>Enews Report</a></li>
						<li class="2"><a>Comparison</a></li>
						<li class="3"><a>Ranking Data</a></li>
						<li class="4"><a>Chart D</a></li>
					</ul>
				</li>
		
			</ul>
		</div>
		<!-- end #sidebar -->
</div>
<div id="footer" class="container">
	<p>COPYRIGHT &copy; TainanGov SDC. All rights reserved. Design by <a href="#">KKC</a>.</p>
</div>
</body>
</html>
