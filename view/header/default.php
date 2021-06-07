<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="Content-Type" content="text/html; charset=big5" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>臺南市政府 智慧發展中心</title>
	<meta name="keywords" content="" />
	<meta name="description" content="" />
	<link rel="shortcut icon" href="/images/logo.ico" />

	<!-- jQuery-->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

	<!-- node_modules-->
	<script src="/node_modules/d3js/d3.min.js" charset="utf-8"></script>
	<link href="/node_modules/c3js/c3.css" rel="stylesheet" type="text/css">
	<script src="/node_modules/c3js/c3.min.js"></script>
	<script src="/node_modules/tablesort.js"></script>
    <script src="https://www.gstatic.com/charts/loader.js"></script>

	<!-- semantic ui -->
	<link rel="stylesheet" type="text/css" href="/node_modules/semantic/semantic.css">
	<script src="/node_modules/semantic/semantic.js"></script>

	<!-- template css-->
	<link href="/css/default.css" rel="stylesheet" type="text/css" media="all" />

	<!-- my CSS-->
	<link href="/css/subpage.css" rel="stylesheet"/>
	<link href="/css/app.css" rel="stylesheet"/>

	<!-- my JS-->
	<script src="/js/app.js"></script>
	<script src='/js/c3chart.js'></script>
	<script src='/js/gchart.js'></script>

</head>
<body id="example">
    <div class="ui vertical inverted sidebar menu left" id="toc">
    <?php require 'view/sidebar.php'; ?>
    </div>
    <?php require 'view/nav.php'; ?>
    <div class="pusher">
        <div class="full height">
            <div class="toc">
                <div class="ui vertical inverted sidebar menu left overlay visible">
                    <?php require 'view/sidebar.php'; ?>
                </div>
            </div>
            <div class="article">
