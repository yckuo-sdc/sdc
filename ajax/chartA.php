<?php
    require("../mysql_connect.inc.php");
	$date_today = date('Y-m-d',strtotime('now'));
	$date_1month_ago = date('Y-m-d',strtotime('-1 month'));
	//echo $date_today;
	//echo $date_2weeks_ago;

	$sql =" SELECT OccurrenceTime,COUNT(OccurrenceTime) as count 
			FROM security_event 
			WHERE OccurrenceTime BETWEEN '".$date_1month_ago."' AND '".$date_today."' 
			GROUP BY OccurrenceTime ORDER by OccurrenceTime asc";
    $result 	= mysqli_query($conn,$sql);
    $rowcount	= mysqli_num_rows($result);

	echo "<?xml version=\"1.0\"?>";
	echo "<data>";	
   		while($row = mysqli_fetch_assoc($result)) {
			echo "<OccurrenceTime>".date('Y-m-d',strtotime($row['OccurrenceTime']))."</OccurrenceTime>";
			echo "<count>".$row['count']."</count>";
		}
	echo "</data>";

	$conn->close();
?>
