<!--nmap.php-->
<div id="content">
		<div class="sub-content show">
			<div class="post">
		<?php
			$target = "localhost vision.tainan.gov.tw 10.7.102.4";
			echo "<pre>";
			echo "Nmap ".$target;
			echo "</pre>";

			echo "<input id='target' type='text' style='width:90%'  value='".$target."'><br>";
			echo "<button id='nmap_btn'>Nmap<i class='play icon'></i></button>";



	//		echo "<pre>";
	//		system("/usr/bin/nmap $target");
	//		echo "</pre>";





		?>
			<div class="nmap_content"></div>
			<!--
				<form class="ui form" action="javascript:void(0)">

 				<div class="fields">
			    	<div class="field">
					    <label>種類</label>
						<select name="keyword_type" id="keyword_type" class="ui fluid dropdown" required>
						<option value="IP" class="keyword_paper active" selected>設備IP</option>
						<option value="Status" class="keyword_paper active">結案狀態</option>
						<option value="EventTypeName" class="keyword_paper active">資安類型</option>
						<option value="DeviceOwnerName" class="keyword_paper active">所有人姓名</option>
						<option value="OccurrenceTime" class="keyword_paper active">發現日期</option>
						<option value="all" class="keyword_paper active">全部</option>
						</select>
					</div>
				 	<div class="field">
					    <label>關鍵字</label>
						<div class="ui input">
							<input type='text' name='key' id='key' placeholder="請輸入關鍵字">
						</div>
					</div>
					<div class="field">
						<button id="search_btn" name="search_btn" class="ui button">搜尋</button>
					</div>
					 <div class="field">
						<button id="show_all_btn" class="ui button" onclick="window.location.href='index.php?mainpage=query&subpage=1'">顯示全部</button>
					</div>
				</div>
				</form>
				<div class="record_content">
				-->
				<?php //select data form database
                    /*require("mysql_connect.inc.php");
                    $conn = new mysqli($servername, $username, $password, $dbname);
                    if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);
                    //set the charset of query
                    $conn->query('SET NAMES UTF8');
                    //------------pagination----------//
                    $pages=" ";
                    if (!isset($_GET['page'])){ 
                        $pages = 1; 
                    }else{
                        $pages = $_GET['page']; 
                    }
                    
                    //select row_number,and other field value
                    $sql = "SELECT * FROM security_event ORDER by EventID desc,OccurrenceTime desc";
                        
                    $result = mysqli_query($conn,$sql);
                    $rowcount = mysqli_num_rows($result);
                                
                    $per = 10; 		
                    $max_pages = 10;
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
                                            
                    //The href-link of bottom pages
                    echo "<div class='ui pagination menu'>";	
                    echo "<a class='item test' href='?mainpage=query&page=1'>首頁</a>";
                    echo "<a class='item test' href='?mainpage=query&page=".$prev_page."'> ← </a>";
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

					

                    $conn->close();
					*/                        
                ?>
				<!-- </div> -->

						
			</div>
		</div>
		<div style="clear: both;">&nbsp;</div>
	</div>
	
		<!-- end #content -->
		<div id="sidebar" class="info_sidebar">
			<ul>
				<li>
					<h2>Nmap</h2>
					<ul>
						<li class="active title"><a>Nmap</a></li>
					</ul>
				</li>
		
			</ul>
		</div>
		<!-- end #sidebar -->
