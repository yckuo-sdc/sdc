<?php
if(!isLogin() || !isset($_POST['EventID'])) {
	header("Location: /logout"); 
	return;
}

$data_array = array();
$id = "";

foreach($_POST as $key => $val){
	if($key == "EventID"){
		$id = $val;
	}elseif($key != "submit"){
		$data_array[$key] = $val;
	}
}

if(!empty($id)){
	$table = "security_event";
	$key_column = "EventID"; 
	$db->update($table, $data_array, $key_column, $id);
	$flash->success("編輯成功");
}else{
	$flash->error("編輯失敗");
}

header("Location: ".$_SERVER['HTTP_REFERER']); 
