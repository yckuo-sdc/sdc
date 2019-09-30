<?php
    require("../mysql_connect.inc.php");
	$date_today = date('Y-m-d',strtotime('now'));
	$date_3month_ago = date('Y-m-d',strtotime('-3 month'));
	//echo $date_today;
	//echo $date_2weeks_ago;

	$sql =" SELECT OccurrenceTime,COUNT(OccurrenceTime) as count 
			FROM security_event 
			WHERE OccurrenceTime BETWEEN '".$date_3month_ago."' AND '".$date_today."' 
			GROUP BY OccurrenceTime ORDER by OccurrenceTime asc";
    $result 	= mysqli_query($conn,$sql);
    $rowcount	= mysqli_num_rows($result);

	echo "<?xml version=\"1.0\"?>";
	echo "<data>";	
   		while($row = mysqli_fetch_assoc($result)) {
			echo "<OccurrenceTime>".date('Y-m-d',strtotime($row['OccurrenceTime']))."</OccurrenceTime>";
			echo "<count>".$row['count']."</count>";
			$sql_done =" SELECT OccurrenceTime,COUNT(OccurrenceTime) as count 
				FROM security_event 
				WHERE OccurrenceTime LIKE '".$row['OccurrenceTime']."' AND Status LIKE '已結案'
				GROUP BY OccurrenceTime ORDER by OccurrenceTime asc";
			$result_done= mysqli_query($conn,$sql_done);
			$row_done = mysqli_fetch_assoc($result_done);
			echo "<OccurrenceTime_done>".date('Y-m-d',strtotime($row_done['OccurrenceTime']))."</OccurrenceTime_done>";
			echo "<count_done>".$row_done['count']."</count_done>";
		}
	echo "</data>";

	$conn->close();
?>
