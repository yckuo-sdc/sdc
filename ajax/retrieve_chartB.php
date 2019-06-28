<?php
    require("../mysql_connect.inc.php");
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);
    //set the charset of 
	$conn->query('SET NAMES UTF8');
    //select row_number,and other field value
	$sql2018 = "SELECT MONTH(OccurrenceTime) as month,COUNT(*) as count FROM security_event WHERE YEAR(OccurrenceTime) LIKE '2018' GROUP BY MONTH(OccurrenceTime)";
	$sql2019 = "SELECT MONTH(OccurrenceTime) as month,COUNT(*) as count FROM security_event WHERE YEAR(OccurrenceTime) LIKE '2019' GROUP BY MONTH(OccurrenceTime)";
    $result2018 	= mysqli_query($conn,$sql2018);
    $result2019 	= mysqli_query($conn,$sql2019);
    $rowcount2018	= mysqli_num_rows($result2018);
    $rowcount2019	= mysqli_num_rows($result2019);
		echo "<?xml version=\"1.0\"?>";
		echo "<data>";	
            while($row = mysqli_fetch_assoc($result2018)) {
				 echo "<month2018>".$row['month']."月</month2018>";
				 echo "<count2018>".$row['count']."</count2018>";
			}
            while($row = mysqli_fetch_assoc($result2019)) {
				 echo "<month2019>".$row['month']."月</month2019>";
				 echo "<count2019>".$row['count']."</count2019>";
	    	}																							
		echo "</data>";
	$conn->close();
?>
