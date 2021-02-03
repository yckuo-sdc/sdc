<?php
foreach($_GET as $key => $val){
	${$key} = $val;
}

switch(true){
    case ($action == "edit" && $category == "event"):
		$table = "security_event";
		$condition = "EventID = :EventID";
		$data_array[':EventID'] = $id;
		$entry = $db->query($table, $condition, $order_by = "1", $fields = "*", $limit = "", $data_array);
		echo json_encode($entry[0], JSON_UNESCAPED_UNICODE);
		break;
    case ($action == "delete " && $category == "event"):
		$table = "security_event";
		$key_column = "EventID";
		$db->delete($table, $key_column, $id);
        $flash->success("刪除成功");
		break;
    case ($action == "edit" && $category == "ldap_computer"):
       break;
}
