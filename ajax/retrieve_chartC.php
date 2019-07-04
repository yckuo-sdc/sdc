<?php
    require("../mysql_connect.inc.php");
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);
    //set the charset of 
	$conn->query('SET NAMES UTF8');
    //select row_number,and other field value
	$sql = "SELECT EventTypeName,COUNT(EventTypeName) as count FROM security_event GROUP BY EventTypeName ORDER by count desc";
    $result 	= mysqli_query($conn,$sql);
    $rowcount	= mysqli_num_rows($result);
	echo "<h3></h3>"; 
//	while($row = mysqli_fetch_assoc($result)) {
//		echo $row['EventTypeName'].":";
//		echo $row['count']."<br>";
//	}
	echo "<?xml version=\"1.0\"?>";
	echo "<data>";	
   		while($row = mysqli_fetch_assoc($result)) {
			echo "<EventType>";
				echo "<name>".$row['EventTypeName']."</name>";
				echo "<count>".$row['count']."</count>";
			echo "</EventType>";
		}
	$sql = "SELECT AgencyName,COUNT(AgencyName) as count FROM security_event WHERE NOT AgencyName LIKE '' GROUP BY AgencyName ORDER by count desc LIMIT 10";
    $result 	= mysqli_query($conn,$sql);
    $rowcount	= mysqli_num_rows($result);
   		while($row = mysqli_fetch_assoc($result)) {
			echo "<AgencyName>";
			echo "<name>".$row['AgencyName']."</name>";
			echo "<count>".$row['count']."</count>";
			echo "</AgencyName>";
		}
	$sql = "SELECT UnitName,COUNT(UnitName) as count FROM security_event WHERE NOT UnitName LIKE '' GROUP BY UnitName ORDER by count desc LIMIT 10";
    $result 	= mysqli_query($conn,$sql);
    $rowcount	= mysqli_num_rows($result);
   		while($row = mysqli_fetch_assoc($result)) {
			echo "<UnitName>".$row['UnitName']."</UnitName>";
			echo "<Unitcount>".$row['count']."</Unitcount>";
		}
	echo "</data>";

	$conn->close();
?>
