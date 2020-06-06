<?php
	header('Content-type: text/html; charset=utf-8');
	//mysql
	require("../mysql_connect.inc.php");
	include("../login/function.php");
	 //select row_number,and other field value
	$sql = "SELECT * FROM portscanTarget WHERE SubnetName='YongHua Serverfarm'";
	$result = mysqli_query($conn,$sql);
	$num_total_entry = mysqli_num_rows($result);
	
	while($row = mysqli_fetch_assoc($result)) {
		$subnetname = $row['SubnetName'];
		$start = $row['StartIPInteger'];
		$end = $row['EndIPInteger'];
		for($i=$start;$i<=$end;$i++){
			$IPv4 = long2ip($i);
			echo $IPv4."\n";
			$output = shell_exec("/usr/bin/nmap -p1-10000 ".$IPv4);
			echo $output;
			$res = NmapParser($output);
			//print_r($res);
			foreach($res as $v1){
				foreach($v1 as $v2){
					echo $v2." ";
				}
				echo "\n";
				$sql = "INSERT INTO portscanResult(SubnetName,IPInteger,IPv4,PortNumber,Protocol,Status,Service) VALUES('$subnetname',$i,'$IPv4','$v1[0]','$v1[1]','$v1[2]','$v1[3]')" ;
				if ($conn->query($sql) == TRUE){
				}else {
					echo "Error: " . $sql . "<br>" . $conn->error."<p>\n\r";
				}
			}
		}
	}
	
	$conn->close();	
	

?>
