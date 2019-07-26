<?php
	header('Content-type: text/html; charset=utf-8');
	//alert message
	function phpAlert($msg) {
		echo '<script type="text/javascript">alert("' . $msg . '")</script>';
	}
	//mysql
	require("../mysql_connect.inc.php");
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	 }
	$conn->query('SET NAMES UTF8');
	 //select row_number,and other field value
	$sql = "SELECT * FROM application_system";
	$result = mysqli_query($conn,$sql);
	$num_total_entry = mysqli_num_rows($result);
	
	while($row = mysqli_fetch_assoc($result)) {
		echo $row['IP'];
		$output = shell_exec("/usr/bin/nmap ".$row['IP']);
		echo $output;
		$sql = "UPDATE application_system SET Scan_Result='".$output."' WHERE SID =".$row['SID'];
		if ($conn->query($sql) == TRUE){
		}else {
			echo "Error: " . $sql . "<br>" . $conn->error."<p>\n\r";
		}
	}
	
	$conn->close();	
	

?>
