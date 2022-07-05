<?php 
$sql = "SELECT a.id, a.class, a.name, a.data_type,a.update_frequency, b.url, b.data_number, b.updated_at,
        CASE 
            WHEN DATE(b.updated_at) = CURDATE() THEN 1 
            ELSE 0 
        END AS today_updated
        FROM apis AS a 
        INNER JOIN api_status AS b 
        ON a.id = b.api_id
        AND b.id IN(
            SELECT max(id) FROM api_status GROUP BY api_id
        )
        AND a.update_method = 'automatic'";
$automatic_apis = $db->execute($sql);

$automatic_apis_num = count($automatic_apis); 
$today_updated_array = array_column($automatic_apis, "today_updated");
$today_updated_num = array_count_values($today_updated_array)[1];
$label_color = ($automatic_apis_num == $today_updated_num) ? "grey" : "red";

$sql = "SELECT a.id, a.class, a.name, a.data_type, a.update_frequency, b.url, b.data_number, b.updated_at
        FROM apis AS a 
        INNER JOIN api_status AS b 
        ON a.id = b.api_id
        AND b.id IN(
            SELECT max(id) FROM api_status GROUP BY api_id
        )
        AND a.update_method = 'manual'";
$manual_apis = $db->execute($sql);

$manual_apis_num = count($manual_apis); 

require 'view/header/default.php'; 
require 'view/body/about/data.php';
require 'view/footer/default.php'; 
