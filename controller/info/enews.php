<?php 
$table = "security_ncert"; // 設定你想查詢資料的資料表
$db->query($table, $condition = "1 = ?", $order_by = "1", $fields = "*", $limit = "", [1]);
$ncert_num = $db->getLastNumRows();
$db->query($table, $condition = "Status LIKE :Status", $order_by = "1", $fields = "*", $limit = "", [':Status'=>'已結案']);
$done_ncert_num = $db->getLastNumRows();
				
$table = "security_event"; // 設定你想查詢資料的資料表
$db->query($table, $condition = "1 = ?", $order_by = "1", $fields = "*", $limit = "", [1]);
$event_num = $db->getLastNumRows();
$db->query($table, $condition = "Status LIKE :Status", $order_by = "1", $fields = "*", $limit = "", [':Status'=>'已結案']);
$done_event_num = $db->getLastNumRows();
$db->query($table, $condition = "Status LIKE :Status", $order_by = "1", $fields = "*", $limit = "", [':Status'=>'未完成']);
$undone_event_num = $db->getLastNumRows();
$db->query($table, $condition = "Status LIKE :Status AND UnprocessedReason IS NOT NULL AND UnprocessedReason != :Reason", $order_by = "1", $fields = "*", $limit = "", [':Status' => '未完成', ':Reason'=>'']);
$excepted_event_num = $db->getLastNumRows();

$date_from_week = date('Y-m-d',strtotime('monday this week'));  
$date_to_week = date('Y-m-d',strtotime('sunday this week'));
$date_from_month = date('Y-m-d',strtotime('first day of this month'));
$date_to_month = date('Y-m-d',strtotime('last day of this month'));  
$db->query($table, $condition = "OccurrenceTime BETWEEN :date_from_month AND :date_to_month", $order_by = "1", $fields = "*", $limit = "", [':date_from_month'=>$date_from_month, ':date_to_month'=>$date_to_month]);
$thisMonth_event_num = $db->getLastNumRows();
$db->query($table, $condition = "OccurrenceTime BETWEEN :date_from_week AND :date_to_week", $order_by = "1", $fields = "*", $limit = "", [':date_from_week'=>$date_from_week, ':date_to_week'=>$date_to_week]);
$thisWeek_event_num = $db->getLastNumRows();
$completion_rate = round($done_event_num / $event_num * 100,2)."%"; 

$order_by = "EventID desc";
$limit = "LIMIT 10";
$last_10_events = $db->query($table, $condition = "1 = ?", $order_by , $fields = "*", $limit, [1]);

require 'view/header/default.php'; 
require 'view/body/info/enews.php';
require 'view/footer/default.php'; 
