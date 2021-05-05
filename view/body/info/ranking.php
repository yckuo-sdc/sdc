<div id="page" class="container">
	<div id="content">
		<div class="sub-content show">
			<div class="post">
                <?=$route->createBreadcrumbs(' > ');?>
                <h2 class="ui dividing header">Ranking Data</h2>
				<div class="post_title">資安事件跨年度比較</div>
				<div class="post_cell">
				繪製長條圖時，長條柱或柱組中線須對齊項目刻度。相較之下，折線圖則是將數據代表之點對齊項目刻度。在數字大且接近時，兩者皆可使用波浪形省略符號，以擴大表現數據間的差距，增強理解和清晰度。
				</div>
				<div id="chartB" class="chart"></div>	
				<button id="show_chart_btn" class="ui button">Plot</button>
				<div id="eventType_post" class="post_title">資安類型統計圖</div>
				<div class="post_cell">
					<div id="eventType_chart" class="chart"></div>	
			    </div>		
				<div id="topEvent_post" class="post_title">Top 10 機關資安事件排序</div>
				<div class="post_cell">
					<div id="topEvent_chart" class="chart"></div>	
			    </div>		
				<div id="topDestIP_post" class="post_title">Top 10 攻擊目標IP</div>
				<div class="post_cell">
					<div id="topDestIP_chart" class="chart"></div>	
			    </div>		
				<div class="post_title">G Chart</div>
				<div class="post_cell">
                    <div id="pie_chart_div" class="chart"></div>
				</div><!--end of .post_cell-->
			</div>
		</div><!--end of .sub-content-->
		<div style="clear: both;">&nbsp;</div>
	</div><!-- end #content -->
</div> <!--end #page-->
