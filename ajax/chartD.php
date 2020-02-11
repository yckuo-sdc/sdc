<?php
    require("../mysql_connect.inc.php");
	//select row_number,and other field value
	$sql = "SELECT ou,(sum(total_VUL)-sum(fixed_VUL)) as unfixed_VUL,sum(total_VUL) as total_VUL,sum(fixed_VUL) as fixed_VUL FROM V_VUL_tableau WHERE ou NOT LIKE '區公所' GROUP BY ou ORDER BY total_VUL desc";
	//$sql = "SELECT ou,sum(total_VUL) as total_VUL,sum(fixed_VUL) as fixed_VUL FROM V_VUL_tableau WHERE ou NOT LIKE '區公所' GROUP BY ou ORDER BY total_VUL desc";
    $result 	= mysqli_query($conn,$sql);
    $rowcount	= mysqli_num_rows($result);
	echo "<h3></h3>"; 
	echo "<?xml version=\"1.0\"?>";
	echo "<data>";	
   		while($row = mysqli_fetch_assoc($result)) {
			echo "<VUL>";
				echo "<ou>".$row['ou']."</ou>";
				echo "<unfixed_VUL>".$row['unfixed_VUL']."</unfixed_VUL>";
				//echo "<total_VUL>".$row['total_VUL']."</total_VUL>";
				echo "<fixed_VUL>".$row['fixed_VUL']."</fixed_VUL>";
			echo "</VUL>";
		}
	echo "</data>";
	$conn->close();
?>
