<?php
require '../vendor/autoload.php';

$db = Database::get();

//update the column 'ad' from table 'ad_computer_list'
$sql = "UPDATE api_list AS A
JOIN api_status AS B 
ON A.id = B.api_id AND B.id IN(
	SELECT MAX(api_status.id) FROM api_status WHERE api_status.status = 200 AND api_status.api_id = A.id GROUP BY api_status.api_id
) 
SET A.url = B.url, A.data_number = B.data_number, A.last_update = B.last_update ";

$db->execute($sql, []);

