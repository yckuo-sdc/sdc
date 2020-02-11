<!--siderbar.php-->
<?php
	switch($page){  // 依照 GET 參數載入共用的內容
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
					</ul>
				</li>
			</ul>
		</div>
		<?php break;
		case "query":?>
		<div id="sidebar" class="info_sidebar">
			<ul>
				<li>
					<h2>ISMS資安查詢</h2>
					<ul>
						<li class="active title"><a>府內資安事件查詢</a></li>
						<li class="title"><a>NCERT資安通報查詢</a></li>
						<li class="title"><a>NCERT資安聯絡人</a></li>
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
						<li class="title"><a>弱點查詢(All)</a></li>
						<!--<li class="title"><a>弱點查詢(Host)</a></li>
						<li class="title"><a>弱點查詢(Web)</a></li>-->
						<li class="title"><a>Retrieve VS</a></li>
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
					<h2>Tool</h2>
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
