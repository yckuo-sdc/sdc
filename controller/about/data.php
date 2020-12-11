<?php 
#$table = "api_list";
#$condition = "1 = ?";
#$api_list = $db->query($table, $condition, $order_by = "1", $fields = "*", $limit = "", [1]);
$sql = "SELECT a.id, a.class, a.name, a.data_type, a.url, b.data_number, b.last_update FROM api_list AS a 
        INNER JOIN api_status AS b 
        ON a.id = b.api_id
        WHERE b.id IN(
            SELECT max(id) FROM api_status GROUP BY api_id
        )";
$api_list = $db->execute($sql);

require 'view/header/default.php'; 
require 'view/body/about/data.php';
require 'view/footer/default.php'; 
