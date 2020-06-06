<!--siderbar.php-->
<?php
	switch($page){  // 依照 GET 參數載入共用的內容
		case "dashboard":?>
		<div id="sidebar" class="info_sidebar">
			<ul>
				<li>
					<h2>儀表板</h2>
					<ul>
						<li class="active title"><a>Data status</a></li>
					</ul>
				</li>
			</ul>
		</div>
		<?php break;
		case "info":?>
		<div id="sidebar" class="info_sidebar">
			<ul>
				<li>
					<h2>視覺化資訊</h2>
					<ul>
						<li class="active title"><a>Enews Report</a></li>
						<li class="title"><a>Comparison</a></li>
						<li class="title"><a>Ranking Data</a></li>
						<li class="title"><a>VUL Bar Chart</a></li>
						<li class="title"><a>Client List</a></li>
					</ul>
				</li>
			</ul>
		</div>
		<?php break;
		case "query":?>
		<div id="sidebar" class="info_sidebar">
			<ul>
				<li>
					<h2>資安查詢</h2>
					<ul>
						<li class="active title"><a>府內資安事件查詢</a></li>
						<li class="title"><a>資安通報查詢</a></li>
						<li class="title"><a>資安聯絡人</a></li>
						<li class="title"><a>端點資安用戶端清單</a></li>
						<li class="title"><a>Retrieve GS & Ncert</a></li>
					</ul>
				</li>
		
			</ul>
		</div>

		<?php break;
		case "vulnerability":?>
		<div id="sidebar" class="info_sidebar">
			<ul>
				<li>
					<h2>漏洞修補</h2>
					<ul>
						<li class="active title"><a>OverView</a></li>
						<li class="title"><a>弱點查詢</a></li>
						<li class="title"><a>Retrieve VS</a></li>
						<li class="title"><a>Scan Target</a></li>
					</ul>
				</li>
			</ul>
		</div>
		<?php
		break;
		case "nmap":?>
		<div id="sidebar" class="info_sidebar">
			<ul>
				<li>
					<h2>工具</h2>
					<ul>
						<li class="active title"><a>Nmap</a></li>
						<li class="title"><a>AP</a></li>
						<li class="title"><a>LDAP</a></li>
						<li class="title"><a>Hydra</a></li>
					</ul>
				</li>
		
			</ul>
		</div>
		<?php
		break;
	}
?>
