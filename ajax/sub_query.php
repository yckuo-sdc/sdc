<?php
	
	header('Content-type: text/html; charset=utf-8');
	//alert message
	function phpAlert($msg) {
		echo '<script type="text/javascript">alert("' . $msg . '")</script>';
	}

	if(!empty($_GET['key']) && !empty($_GET['keyword_type'])){
		//過濾特殊字元(')
		//$key  		   = str_replace("'","\'",$_GET['key']);
		//$keyword_type  = str_replace("'","\'",$_GET['keyword_type']);
		$key  		   = $_GET['key'];
		$keyword_type  = $_GET['keyword_type'];




		//connect database
        require("../mysql_connect.inc.php");
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);
        //set the charset of query
		$conn->query('SET NAMES UTF8');
		 //特殊字元跳脫(NUL (ASCII 0), \n, \r, \, ', ", and Control-Z)
		$key			 = mysqli_real_escape_string($conn,$key);
		$keyword_type	 = mysqli_real_escape_string($conn,$keyword_type);

		if($keyword_type == "all"){
			//echo $key;
			//FullText Seach excpet the EventID,OccurrenceTime
		//	$sql = "SELECT * FROM security_event WHERE MATCH(Status,EventTypeName,Location,IP,BlockReason,DeviceTypeName,DeviceOwnerName,DeviceOwnerPhone,AgencyName,UnitName,NetworkProcessContent,MaintainProcessContent,AntivirusProcessContent,UnprocessedReason,Remarks) AGAINST ('+".$key."' IN BOOLEAN MODE) ORDER by EventID DESC,OccurrenceTime DESC";
			$sql = "SELECT * FROM security_event WHERE Status LIKE '%".$key."%' OR EventTypeName LIKE '%".$key."%' OR Location LIKE '%".$key."%' OR IP LIKE '%".$key."%' OR BlockReason LIKE '%".$key."%' OR DeviceTypeName LIKE '%".$key."%' OR DeviceOwnerName LIKE '%".$key."%' OR DeviceOwnerPhone LIKE '%".$key."%' OR AgencyName LIKE '%".$key."%' OR UnitName LIKE '%".$key."%' OR NetworkProcessContent LIKE '%".$key."%' OR MaintainProcessContent LIKE '%".$key."%' OR AntivirusProcessContent LIKE '%".$key."%' OR UnprocessedReason LIKE '%".$key."%' OR Remarks LIKE '%".$key."%' ORDER by EventID DESC,OccurrenceTime DESC";
		}else{
			$sql = "SELECT * FROM security_event WHERE ".$keyword_type." LIKE '%".$key."%' ORDER by EventID DESC,OccurrenceTime DESC";
		}
		$result = mysqli_query($conn,$sql);
		if(!$result){
			echo"Error:".mysqli_error($conn);
			exit();
		}
		$rowcount = mysqli_num_rows($result);
		if ($rowcount == 0){
			echo "很抱歉，該分類目前沒有資料！";
		}
		else{
			echo "該分類共搜尋到".$rowcount."筆資料！";
		   
			echo "<div class='ui relaxed divided list'>";
				echo "<div class='item'>";
					echo "<div class='content'>";
						echo "<a class='header'>";
						//echo "序號&nbsp";
                        echo "發現日期&nbsp&nbsp";
                        echo "結案狀態&nbsp&nbsp";
                        echo "資安事件類型&nbsp&nbsp";
                       	echo "位置&nbsp&nbsp";
						echo "設備IP&nbsp&nbsp";
						echo "姓名&nbsp&nbsp";
						echo "分機&nbsp&nbsp";
						echo "<a>";
					echo "</div>";
				echo "</div>";
                    while($row = mysqli_fetch_assoc($result)) {
						echo "<div class='item'>";
						echo "<div class='content'>";
							echo "<a>";
							//echo $row['EventID']."&nbsp&nbsp"";
							if($row['Status']=="已結案")echo "<i class='check circle icon' style='color:green'></i>";
							else echo "<i class='exclamation circle icon'></i>";
                        	echo date_format(new DateTime($row['OccurrenceTime']),'Y-m-d')."&nbsp&nbsp";
                        	echo $row['Status']."&nbsp&nbsp";
                        	echo "<span style='background:#fde087'>".$row['EventTypeName']."</span>&nbsp&nbsp";
                        	echo $row['Location']."&nbsp&nbsp";
							echo "<span style='background:#DDDDDD'>".$row['IP']."</span>&nbsp&nbsp";
							echo $row['DeviceOwnerName']."&nbsp&nbsp";
							echo $row['DeviceOwnerPhone']."&nbsp&nbsp";
							echo "<i class='angle double down icon'></i>";
							echo "</a>";
							echo "<div class='description'>";
								echo "<ol>";
								echo "<li>序號:".$row['EventID']."</li>";
								echo "<li>結案狀態:".$row['Status']."</li>";
								echo "<li>發現日期:".date_format(new DateTime($row['OccurrenceTime']),'Y-m-d')."</li>";
								echo "<li>資安事件類型:".$row['EventTypeName']."</li>";
								echo "<li>位置:".$row['Location']."</li>";
								echo "<li>電腦IP:".$row['IP']."</li>";
								echo "<li>封鎖原因:".$row['BlockReason']."</li>";
								echo "<li>設備類型:".$row['DeviceTypeName']."</li>";
								echo "<li>電腦所有人姓名:".$row['DeviceOwnerName']."</li>";
								echo "<li>電腦所有人分機:".$row['DeviceOwnerPhone']."</li>";
								echo "<li>機關:".$row['AgencyName']."</li>";
								echo "<li>單位:".$row['UnitName']."</li>";
								echo "<li>處理日期(國眾):".$row['NetworkProcessContent']."</li>";
								echo "<li>處理日期(三佑科技):".$row['MaintainProcessContent']."</li>";
								echo "<li>處理日期(京稘或中華SOC):".$row['AntivirusProcessContent']."</li>";
								echo "<li>未能處理之原因及因應方式:".$row['UnprocessedReason']."</li>";
								echo "</ol>";
							echo "</div>";
							echo "</div>";
						echo "</div>";

                    }
			echo "</div>";

		}
		
		$conn->close();
		
	}else{
		phpAlert("沒有輸入");
	}
	
?> 
