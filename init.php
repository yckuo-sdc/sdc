<!--init-->
<?php 
date_default_timezone_set('Asia/Taipei');
$db = Database::get();
$route = new Router(Request::uri()); //搭配 .htaccess 排除資料夾名稱後解析 URL
