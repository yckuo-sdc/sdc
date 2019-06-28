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
		<div class="sub" onclick='location.href="info.php?2"'>Comparison</div>
		<div class="sub" onclick='location.href="info.php?3"'>Ranking Data</div>
		<div class="sub" onclick='location.href="info.php?4"'>ChartD</div>
	</div>
	
	<div class="front" id="3">
		<div class="sub" onclick='location.href="query.php?1"'>資安事件查詢</div>
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
				<form class="ui form" action="javascript:void(0)">

 				<div class="fields">
			    	<div class="field">
					    <label>種類</label>
						<select name="keyword_type" id="keyword_type" class="ui fluid dropdown" required>
                   		 <!--<option value="" class="keyword_paper active" selected>關鍵字種類</option>-->
						<option value="IP" class="keyword_paper active" selected>設備IP</option>
						<option value="Status" class="keyword_paper active">結案狀態</option>
						<option value="EventTypeName" class="keyword_paper active">資安類型</option>
						<option value="DeviceOwnerName" class="keyword_paper active">所有人姓名</option>
						<option value="OccurrenceTime" class="keyword_paper active">發現日期</option>
						<option value="all" class="keyword_paper active">全部</option>
						</select>
					</div>
				 	<div class="field">
					    <label>關鍵字</label>
						<div class="ui input">
							<input type='text' name='key' id='key' placeholder="請輸入關鍵字">
						</div>
					</div>
					<div class="field">
						<button id="search_btn" name="search_btn" class="ui button">搜尋</button>
					</div>
					 <div class="field">
						<button id="show_all_btn" class="ui button" onclick="window.location.href='query.php?1'">顯示全部</button>
					</div>
				</div>
				</form>
				<div class="record_content">
                <?php //select data form database
                    require("mysql_connect.inc.php");
                    $conn = new mysqli($servername, $username, $password, $dbname);
                    if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);
                    //set the charset of query
                    $conn->query('SET NAMES UTF8');
                    //------------pagination----------//
                    $pages=" ";
                    if (!isset($_GET['page'])){ 
                        $pages = 1; 
                    }else{
                        $pages = $_GET['page']; 
                    }
                    
                    //select row_number,and other field value
                    $sql = "SELECT * FROM security_event ORDER by EventID desc,OccurrenceTime desc";
                        
                    $result = mysqli_query($conn,$sql);
                    $rowcount = mysqli_num_rows($result);
                                
                    $per = 10; 		
                    $max_pages = 10;
                    $Totalpages = ceil($rowcount / $per); 
                    $lower_bound = ($pages <= $max_pages) ? 1 : $pages - $max_pages + 1;
                    $upper_bound = ($pages <= $max_pages) ? min($max_pages,$Totalpages) : $pages;					
                    $start = ($pages -1)*$per; //計算資料庫取資料範圍的開始值。
                    if($pages == 1)					$offset = ($rowcount < $per) ? $rowcount : $per;
                    elseif($pages == $Totalpages)	$offset = $rowcount - $start;
                    else							$offset = $per;
                                
                    $prev_page = ($pages > 1) ? $pages -1 : 1;
                    $next_page = ($pages < $Totalpages) ? $pages +1 : $Totalpages;	
                    $sql_subpage = $sql." limit ".$start.",".$offset;
                                
                    $result = mysqli_query($conn,$sql_subpage);
                                        
                    if($rowcount==0){
                        echo "查無此筆紀錄";
                    }else{
                        echo "共有".$rowcount."筆資料！";


						echo "<div class='ui relaxed divided list'>";
							echo "<div class='item'>";
								echo "<div class='content'>";
									echo "<a class='header'>";
									//echo "序號&nbsp";
                        			echo "發現日期&nbsp&nbsp";
                        			echo "結案狀態&nbsp&nbsp";
                        			echo "資安事件類型&nbsp&nbsp";
                       				echo "位置&nbsp&nbsp";
									echo "設備IP&nbsp&nbsp";
									echo "姓名&nbsp&nbsp";
									echo "分機&nbsp&nbsp";
									echo "<a>";
								echo "</div>";
							echo "</div>";

                    while($row = mysqli_fetch_assoc($result)) {
						echo "<div class='item'>";
						echo "<div class='content'>";
							echo "<a>";
							//echo $row['EventID']."&nbsp&nbsp"";
							if($row['Status']=="已結案")echo "<i class='check circle icon' style='color:green'></i>";
							else echo "<i class='exclamation circle icon'></i>";
                        	echo date_format(new DateTime($row['OccurrenceTime']),'Y-m-d')."&nbsp&nbsp";
                        	echo $row['Status']."&nbsp&nbsp";
                        	echo "<span style='background:#fde087'>".$row['EventTypeName']."</span>&nbsp&nbsp";
                        	echo $row['Location']."&nbsp&nbsp";
							echo "<span style='background:#DDDDDD'>".$row['IP']."</span>&nbsp&nbsp";
							echo $row['DeviceOwnerName']."&nbsp&nbsp";
							echo $row['DeviceOwnerPhone']."&nbsp&nbsp";
							echo "<i class='angle double down icon'></i>";
							echo "</a>";
							echo "<div class='description'>";
								echo "<ol>";
								echo "<li>序號:".$row['EventID']."</li>";
								echo "<li>結案狀態:".$row['Status']."</li>";
								echo "<li>發現日期:".date_format(new DateTime($row['OccurrenceTime']),'Y-m-d')."</li>";
								echo "<li>資安事件類型:".$row['EventTypeName']."</li>";
								echo "<li>位置:".$row['Location']."</li>";
								echo "<li>電腦IP:".$row['IP']."</li>";
								echo "<li>封鎖原因:".$row['BlockReason']."</li>";
								echo "<li>設備類型:".$row['DeviceTypeName']."</li>";
								echo "<li>電腦所有人姓名:".$row['DeviceOwnerName']."</li>";
								echo "<li>電腦所有人分機:".$row['DeviceOwnerPhone']."</li>";
								echo "<li>機關:".$row['AgencyName']."</li>";
								echo "<li>單位:".$row['UnitName']."</li>";
								echo "<li>處理日期(國眾):".$row['NetworkProcessContent']."</li>";
								echo "<li>處理日期(三佑科技):".$row['MaintainProcessContent']."</li>";
								echo "<li>處理日期(京稘或中華SOC):".$row['AntivirusProcessContent']."</li>";
								echo "<li>未能處理之原因及因應方式:".$row['UnprocessedReason']."</li>";
								echo "</ol>";
							echo "</div>";
							echo "</div>";
						echo "</div>";
                    }
					
					echo "</div>";
                                            
                    //The href-link of bottom pages
                    echo "<div class='ui pagination menu'>";	
                    echo "<a class='item test' href='?page=1'>首頁</a>";
                    echo "<a class='item test' href='?page=".$prev_page."'> ← </a>";
                    for ($j = $lower_bound; $j <= $upper_bound ;$j++){
                        if($j == $pages){
                            echo"<a class='active item bold' href='?page=".$j."'>".$j."</a>";
                        }else{
                            echo"<a class='item test' href='?page=".$j."'>".$j."</a>";
                        }
                    }
                    echo"<a class='item test' href='?page=".$next_page."'> → </a>";		
                    //last page
                    echo"<a class='item test' href='?page=".$Totalpages."'>末頁</a>";
                    echo "</div>";
				   
                    //The mobile href-link of bottom pages
                    echo "<div class='ui pagination menu mobile'>";	
                    echo "<a class='item test' href='?page=".$prev_page."'> ← </a>";
                    echo"<a class='active item bold' href='?page=".$pages."'>(".$pages."/".$Totalpages.")</a>";
                    echo"<a class='item test' href='?page=".$next_page."'> → </a>";		
                    echo "</div>";
				
				
					}

					

                    $conn->close();
                        
                ?>
				</div>

						
			</div>
		</div>
		<div class="2">
			<div class="post">
				<div class="post_title">Retrieve from Google Sheets</div>
				<button id="gs_btn" class="ui button">Retrieve</button>
				<div class="retrieve_info"></div>
			</div>
		</div>
		<div class="3">
			<div class="post">
				<div class="post_title">sub 3</div>
			</div>
		</div>
		<div style="clear: both;">&nbsp;</div>
	</div>
	
		<!-- end #content -->
		<div id="sidebar" class="info_sidebar">
			<ul>
				<li>
					<h2>ISMS資安查詢</h2>
					<ul>
						<li class="active 1"><a>資安事件查詢</a></li>
						<li class="2"><a>Retrieve GS</a></li>
						<li class="3"><a>sub 3</a></li>
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
