<?php 
$table = "security_ncert"; 
$db->query($table, $condition = "1 = ?", $order_by = "1", $fields = "*", $limit = "", [1]);
$ncert_num = $db->getLastNumRows();
$db->query($table, $condition = "Status LIKE :Status", $order_by = "1", $fields = "*", $limit = "", [':Status'=>'已結案']);
$done_ncert_num = $db->getLastNumRows();
$undone_ncert_num = $ncert_num - $done_ncert_num;
$sql = "SELECT COUNT(*) AS count FROM security_ncert WHERE Status LIKE '未完成' AND ( 
        DiscoveryTime < DATE_SUB(
            CASE RepairTime 
                WHEN '1000-01-01 00:00:00' 
                THEN NOW()
                ELSE RepairTime
            END,
            INTERVAL 72 HOUR
        )
        AND ImpactLevel IN ('1級(輕微資安事件)', '2級(一般資安事件)')
    OR
        DiscoveryTime < DATE_SUB(
            CASE RepairTime 
                WHEN '1000-01-01 00:00:00' 
                THEN NOW()
                ELSE RepairTime
            END,
            INTERVAL 36 HOUR
        )
        AND ImpactLevel IN ('3級(重要資安事件)', '4級(重大資安事件)')
)";
$overdue_ncert_num = $db->execute($sql)[0]['count'];		

$table = "security_event"; 
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

//blink animation for alert message
$blink_class = ($done_ncert_num != $ncert_num) ? "blink" : "";
$blink_color_class = ($done_ncert_num != $ncert_num) ? "pink" : "";
$blink_label1 = ($undone_ncert_num != 0) ? "<a href='/query/ncert/' class='ui label blink yellow' style='color:black !important'>待辦 ".$undone_ncert_num."</a>" : "";
$blink_label2 = ($overdue_ncert_num != 0) ? "<a href='/query/ncert/' class='ui label blink red' style='color:black !important'>逾期 ".$overdue_ncert_num."</a>" : "";
$blink_label = $blink_label1 . $blink_label2;

require 'view/header/default.php'; 
require 'view/body/info/enews.php';
require 'view/footer/default.php'; 
