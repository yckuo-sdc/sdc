<?php
	header('Content-type: text/html; charset=utf-8');
	//mysql
	require("../mysql_connect.inc.php");
	include("../login/function.php");
	 //select row_number,and other field value
	$sql = "SELECT * FROM application_system";
	$result = mysqli_query($conn,$sql);
	$num_total_entry = mysqli_num_rows($result);
	
	while($row = mysqli_fetch_assoc($result)) {
		echo $row['IP'];
		$output = shell_exec("/usr/bin/nmap -Pn ".$row['IP']);
		$res = NmapParser($output);
		print_r($res);
		echo $output;
		$sql = "UPDATE application_system SET Scan_Result='".$output."' WHERE SID =".$row['SID'];
		if ($conn->query($sql) == TRUE){
		}else {
			echo "Error: " . $sql . "<br>" . $conn->error."<p>\n\r";
		}
	}
	
	$conn->close();	
	

?>
