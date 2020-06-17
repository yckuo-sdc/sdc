<?php
	header('Content-type: text/html; charset=utf-8');
	//mysql
	require("../mysql_connect.inc.php");
	include("../login/function.php");
	 //select row_number,and other field value
	$sql = "SELECT * FROM  application_system";
	$result = mysqli_query($conn,$sql);
	$num_total_entry = mysqli_num_rows($result);
	
	while($row = mysqli_fetch_assoc($result)) {
		$SubnetName = $row['Name'];
		$SID 		= $row['SID'];
		$IPv4 		= $row['IP'];
		$IPInteger 	= ip2long($IPv4);
		echo $IPv4."\n";
		$output = shell_exec("/usr/bin/nmap -Pn ".$IPv4);
		$res = NmapParser($output);
		//print_r($res);
		foreach($res as $v1){
			foreach($v1 as $v2){
				echo $v2." ";
			}
			echo "\n";
			$ScanTime = date('Y-m-d h:i:s');	
			$sql = "INSERT INTO portscanResult(SubnetName,SID,IPInteger,IPv4,PortNumber,Protocol,Status,Service,ScanTime) VALUES('$SubnetName',$SID,$IPInteger,'$IPv4','$v1[0]','$v1[1]','$v1[2]','$v1[3]','$ScanTime')" ;
			if ($conn->query($sql) == TRUE){
			}else {
				echo "Error: " . $sql . "<br>" . $conn->error."<p>\n\r";
			}
		}
		$sql = "UPDATE application_system SET Scan_Result='".$output."' WHERE SID =".$SID;
		if ($conn->query($sql) == TRUE){
		}else {
			echo "Error: " . $sql . "<br>" . $conn->error."<p>\n\r";
		}
	}
	$conn->close();	
?>
