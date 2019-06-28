<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>國立成功大學 電腦與通信工程研究所</title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700|Archivo+Narrow:400,700" rel="stylesheet" type="text/css">
<link href="default.css" rel="stylesheet" type="text/css" media="all" />
<link rel="shortcut icon" href="images/logo.ico" />

<!-- add jQuery-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>

<!-- add my CSS-->
<link href="add.css" rel="stylesheet"/>

<!-- add my JS-->
<script src="add.js"></script>

<!--[if IE 6]>
<link href="default_ie6.css" rel="stylesheet" type="text/css" />
<![endif]-->
</head>
<body>

<?php
	
	//從資料庫取得資料
	$servername = "140.116.49.175";
	$username = "user";
	$password = "910729";
	$dbname = "eeweb";
						

					
	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
								
?>


<div id="header-wrapper">
	<div id="banner-img">
		<div id="banner-font" onclick="window.location='index.php';"></div>
		<div id="banner-subtitle">
			<div id="banner-subtitle-menu">
				<ul>
					<li><a class="1" accesskey="1" title="" href="index.php">回首頁</a></li>
					<li class="xx">|</li>
					<li><a class="2" accesskey="2" title="" href="https://office.ee.ncku.edu.tw/ccechinese/">舊系網</a></li>
					<li class="xx">|</li>
					<li><a class="3" accesskey="3" title="" href="mailto:wennyhung@mail.ncku.edu.tw">聯絡我們</a></li>
					<li class="xx">|</li>
					<li><a class="4" accesskey="4" title="" href="https://www.ee.ncku.edu.tw/sql_func/">登入</a></li>
					<li class="xx">|</li>
					<li><a class="5" accesskey="5" title="" href="index_en.php">English</a></li>
				</ul>
			</div>
			<input type="search" id="search" placeholder="Search..." />
		</div>
	</div>
	
	<div id="menu">
		<center>
			<ul>
				<li><a class="1" accesskey="1" title="" href="news.php">最新消息</a></li>
				<li><a class="2" accesskey="2" title="" href="info.html">系所簡介</a></li>
				<li><a class="3" accesskey="3" title="" href="research.php">研究領域</a></li>
				<li><a class="4" accesskey="4" title="" href="people.php">成員介紹</a></li>
				<li><a class="5" accesskey="5" title="" href="student.php">學生專區</a></li>
				<li><a class="6" accesskey="6" title="" href="#">相關連結</a></li>
			</ul>
		</center>		
	</div>
</div>



<div id="page" class="container">
	<div class="front" id="1">
		<div class="sub" onclick='location.href="news.php?1"'>全部資訊</div>
		<div class="sub" onclick='location.href="news.php?2"'>一般資訊</div>
		<div class="sub" onclick='location.href="news.php?3"'>招生資訊</div>
		<div class="sub" onclick='location.href="news.php?4"'>演講資訊</div>
		<div class="sub" onclick='location.href="news.php?5"'>獲獎資訊</div>
		<div class="sub" onclick='location.href="news.php?6"'>獎助學金</div>
		<div class="sub" onclick='location.href="news.php?7"'>徵才資訊</div>
	</div>
	<div class="front" id="2">
		<div class="sub" onclick='location.href="info.html?1"'>本所介紹</div>
		<div class="sub" onclick='location.href="info.html?2"'>本所架構</div>
		<div class="sub" onclick='location.href="info.html?3"'>教育目標</div>
		<div class="sub" onclick='location.href="info.html?4"'>學生核心能力培養</div>
		<div class="sub" onclick='location.href="info.html?5"'>師資架構</div>
		<div class="sub" onclick='location.href="info.html?6"'>學生人數</div>
		<div class="sub" onclick='location.href="info.html?7"'>畢業出路</div>
	</div>	
	<div class="front" id="3">
		<div class="sub" onclick='location.href="research.php?1"'>研究組別</div>
		<div class="sub" onclick='location.href="research.php?2"'>研究主題</div>
		<div class="sub" onclick='location.href="research.php?3"'>研究室介紹</div>
	</div>

	<div class="front"  id="4">
		<div class="sub" onclick='location.href="people.php?1"'>師資陣容</div>
		<div class="sub" onclick='location.href="people.php?2"'>系辦成員</div>
	</div>
	<div class="front"  id="5">
		<div class="sub" onclick='location.href="student.php?1"'>課程介紹</div>
		<div class="sub" onclick='location.href="student.php?2"'>規章表格</div>
	</div>	
	<div class="front"  id="6">
		<div class="sub" onclick="window.open('https://web.ncku.edu.tw/bin/home.php');">成功大學</div>
		<div class="sub" onclick="window.open('https://www.eecs.ncku.edu.tw/bin/home.php');">電資學院</div>
		<div class="sub" onclick="window.open('https://www.ee.ncku.edu.tw/');">電機工程學系</div>
		<div class="sub" onclick="window.open('https://ime.ee.ncku.edu.tw/');">微電子工程研究所</div>
	</div>


	<div id="content">
	
	
		
	<?php
	
			echo "<div id ='show'></div>";
			
			$div_group = array("1","2","3","4","5","6","7");
	
			$announce_type = array("一般資訊","招生資訊","演講資訊","獲獎資訊","獎助資訊","徵人資訊");
				
				
			for($i=0;$i<sizeof($div_group);$i++){					   
		

				
				if($i==0){
					echo "<div class='".$div_group[$i]." show'>";
					$conn->query('SET NAMES UTF8');
					$sql = "select * from announce_information where date <= CURRENT_DATE() order by date DESC limit 8";
				}
				else{
					echo "<div class='".$div_group[$i]."'>";
					$conn->query('SET NAMES UTF8');
					$sql = "select * from announce_information where type like '".$announce_type[$i-1]."' and date <= CURRENT_DATE() order by date DESC limit 8";
				}
				
					echo "<div class='post'>";
						echo "<table style='width:100%'>";
						
						echo "<colgroup>";
						echo "<col width='15%'>";
						echo "<col width='15%'>";
						echo "<col width='70%'>";
						echo "</colgroup>";
		

						if ($result = mysqli_query($conn,$sql)) {
							
							echo "<tr>";
							echo "<th>公告日期</th>";
							echo "<th>公告對象</th>"; 
							echo "<th>公告標題</th>";
							echo "</tr>";
		
							while($row = mysqli_fetch_row($result)) {
			
								echo "<tr>";
								echo "<td>$row[5]</td>";
								echo "<td>$row[3]</td>"; 
								echo "<td>";
									echo "<a href='news.php?subpage=1&id=".$row[0]."'>".$row[2]."</a>";
								echo "</td>";
								echo "</tr>";
					
							}
						
			
						}
						else {
							echo "Error: " . $sql . "<br>" . $conn->error;
						}	
							
						echo "</table>";		
							
					echo "</div>"; //End of post

				echo "</div>";	//End of div_group
					
			}
					
					
	
			$conn->close();			
					
			
	?>	
	
	
	<!--
		<div class="1 show">
			<div class="post">
				
			</div>
		</div>
		
		<div class="2">
		</div>
		
		<div class="3">
		</div>
		
		<div class="4">
		</div>
		
		<div class="5">
		</div>
		
		<div class="6">
		</div>
		
		<div class="7">
		</div>
		
	-->
		
		<div style="clear: both;">&nbsp;</div>
	</div>
	<!-- end #content -->
	<div id="sidebar">
		<ul>
			<li>
				<h2>最新消息</h2>
				<ul>
					<li class="1 active"><a>全部資訊</a></li>
					<li class="2"><a>一般資訊</a></li>
					<li class="3"><a>招生資訊</a></li>
					<li class="4"><a>演講資訊</a></li>
					<li class="5"><a>獲獎資訊</a></li>
					<li class="6"><a>獎助學金</a></li>
					<li class="7"><a>徵才資訊</a></li>
				</ul>
			</li>
	
		</ul>
	</div>
	<!-- end #sidebar -->
</div>
<div id="footer" class="container">
	<p>COPYRIGHT &copy; NCKU CCE. All rights reserved. Design by <a href="https://www.ee.ncku.edu.tw/">NCKU EE</a>.</p>
</div>
</body>
</html>
