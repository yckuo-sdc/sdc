<?php 
$sql = "SELECT a.id, a.class, a.name, a.data_type, b.url, b.data_number, b.updated_at,
        CASE 
            WHEN DATE(b.updated_at) = CURDATE() THEN 1 
            ELSE 0 
        END AS updated_at_today
        FROM apis AS a 
        INNER JOIN api_status AS b 
        ON a.id = b.api_id
        AND b.id IN(
            SELECT max(id) FROM api_status GROUP BY api_id
        )";
$apis = $db->execute($sql);

require 'view/header/default.php'; 
require 'view/body/about/data.php';
require 'view/footer/default.php'; 
