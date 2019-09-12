<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--header.php-->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<title>臺南市政府 智慧發展中心</title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700|Archivo+Narrow:400,700" rel="stylesheet" type="text/css">
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
		<div id="banner-font" onclick="window.location='index.php?mainpage=info';"></div>
		<div id="banner-subtitle">
			<div id="banner-subtitle-menu">
				<ul>
					<li><a class="1" accesskey="1" title="" href="index.php?mainpage=info">回首頁</a></li>
					<li class="xx">|</li>
					<li><a class="3" accesskey="3" title="" href="mailto:yckuo@mail.tainan.gov.tw">聯絡我們</a></li>
					<li class="xx">|</li>
					<li><a class="4" accesskey="4" title="" href="login/logout.php">登出<?php echo "(".$_SESSION['UserName'].")";?></a></li>
				</ul>
			</div>
			<input type="search" id="search" placeholder="Search..." />
		</div>
	</div>
	
	<div id="menu">
		<ul class="drop-down-menu">
			<li><a class="1" accesskey="1" title="" href="#">最新消息</a></li>
			<li><a class="2" accesskey="2" title="" href="index.php?mainpage=info">視覺化資訊</a>
				<ul>
					<li><a href="index.php?mainpage=info&subpage=1">Enews Report</a></li>
					<li><a href="index.php?mainpage=info&subpage=2">Comparison</a></li>
					<li><a href="index.php?mainpage=info&subpage=3">Ranking Data</a></li>
					<li><a href="index.php?mainpage=info&subpage=4">VUL Bar Chart</a></li>
				</ul>
			</li>
			<li><a class="3" accesskey="3" title="" href="index.php?mainpage=query">ISMS資安查詢</a>
				<ul>
					<li><a href="index.php?mainpage=query&subpage=1">資安事件查詢</a></li>
					<li><a href="index.php?mainpage=query&subpage=2">資安聯絡人</a></li>
					<li><a href="index.php?mainpage=query&subpage=3">Retrieve GS & Ncert</a></li>
				</ul>
			</li>
			<li><a class="4" accesskey="4" title="" href="index.php?mainpage=vulnerability">漏洞掃描</a>
				<ul>		
					<li><a href="index.php?mainpage=vulnerability&subpage=1">OverView</a></li>
					<li><a href="index.php?mainpage=vulnerability&subpage=2">漏洞查詢(host)</a></li>
					<li><a href="index.php?mainpage=vulnerability&subpage=3">漏洞查詢(web)</a></li>
					<li><a href="index.php?mainpage=vulnerability&subpage=4">Retrieve VS</a></li>
				</ul>

			</li>
			<li><a class="5" accesskey="5" title="" href="index.php?mainpage=nmap">Nmap</a>
				<ul>	
					<li><a href="index.php?mainpage=nmap&subpage=1">Nmap</a></li>
					<li><a href="index.php?mainpage=nmap&subpage=2">AP</a></li>
				</ul>
			</li>
			<li><a class="6" accesskey="6" title="" href="#">相關連結</a> 
				<ul>
					<li><a href="http://vision.tainan.gov.tw" target="_blank">推動友善資訊服務平台</a></li>
					<li><a href="http://10.7.102.4/tndev/" target="_blank">IP查詢系統(芳彬)</a></li>
					<li><a href="https://sdc-mrbs.tainan.gov.tw/" target="_blank">SDC會議室預約系統</a></li>
				</ul>
			</li>		
			<li class="hide"><a class="7" accesskey="7" title="" href="login/logout.php">登出<?php echo "(".$_SESSION['UserName'].")";?></a></li> 
		</ul>
	</div>
</div>

