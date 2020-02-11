<?php				   
	$per = 10; 		
	$max_pages = 10;

	$pages=" ";
	if (!isset($_GET['page'])){ 
		$pages = 1; 
	}else{
		$pages = $_GET['page']; 
	}
	
	//select row_number,and other field value
	$sql = "SELECT * FROM ".$table." WHERE ".$condition." ".$order;
		
	$result = mysqli_query($conn,$sql);
	$rowcount = mysqli_num_rows($result);
				
	$Totalpages = ceil($rowcount / $per); 
	$lower_bound = ($pages <= $max_pages) ? 1 : $pages - $max_pages + 1;
	$upper_bound = ($pages <= $max_pages) ? min($max_pages,$Totalpages) : $pages;					
	$start = ($pages -1)*$per; //計算資料庫取資料範圍的開始值。
	if($pages == 1)					$offset = ($rowcount < $per) ? $rowcount : $per;
	elseif($pages == $Totalpages)	$offset = $rowcount - $start;
	else							$offset = $per;
				
	$prev_page = ($pages > 1) ? $pages -1 : 1;
	$next_page = ($pages < $Totalpages) ? $pages +1 : $Totalpages;	
	$sql_subpage = $sql." limit ".$start.",".$offset;
	$result = mysqli_query($conn,$sql_subpage);
						
	if($rowcount==0){
		echo "查無此筆紀錄";
	}else{
		echo "共有".$rowcount."筆資料！";
		echo $loop_header;
		while($row = mysqli_fetch_assoc($result)) {
			echo $row['IP']."<br>";	
		}	
		echo "</div>";
		//The href-link of bottom pages
		echo "<div class='ui pagination menu'>";	
		echo "<a class='item test' href='?mainpage=query&page=1'>首頁</a>";
		for ($j = $lower_bound; $j <= $upper_bound ;$j++){
			if($j == $pages){
				echo"<a class='active item bold' href='?mainpage=query&page=".$j."'>".$j."</a>";
			}else{
				echo"<a class='item test' href='?mainpage=query&page=".$j."'>".$j."</a>";
			}
		}
		echo"<a class='item test' href='?mainpage=query&page=".$next_page."'> → </a>";		
		//last page
		echo"<a class='item test' href='?mainpage=query&page=".$Totalpages."'>末頁</a>";
		echo "</div>";
	   
		//The mobile href-link of bottom pages
		echo "<div class='ui pagination menu mobile'>";	
		echo "<a class='item test' href='?mainpage=query&page=".$prev_page."'> ← </a>";
		echo"<a class='active item bold' href='?mainpage=query&page=".$pages."'>(".$pages."/".$Totalpages.")</a>";
		echo"<a class='item test' href='?mainpage=query&page=".$next_page."'> → </a>";		
		echo "</div>";
		
		
	}

					

?>
