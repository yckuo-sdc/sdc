/*****Main****/
$(document).ready(function(){
	var url;
	if (location.protocol == 'https:') url='https://sdc-iss.tainan.gov.tw/';
	else							   url='http://sdc-iss.tainan.gov.tw/';

	//bind show_chart_btn
	$('#show_chart_btn').click(function (){
		Call_retrieve_c3_chartB_ajax(url);
	});
	
	Call_retrieve_c3_chartA_ajax(url);
	Call_retrieve_c3_chartB_ajax(url);
	Call_retrieve_c3_chartC_ajax(url);
	//Call_retrieve_c3_chartD_ajax(url);
	Call_retrieve_c3_chartE_ajax(url);
});

function Call_retrieve_c3_chartA_ajax(url){
	$.ajax({
		 url: url+'ajax/chart.php',
		 cache: false,
		 dataType:'html',
		 type:'GET',
		 data: {chartID:'chartA'},
		 error: function(xhr) {
			 alert('Ajax failed');
		 },success: function(data) {
			 var countArray=[],nameArray=[];
			 var countArray_done=[],nameArray_done=[];
			$(data).find("OccurrenceTime").each(function(){
				// console.log($(this).text());
				 nameArray.push($(this).text());
			});
			$(data).find("count").each(function(){
				// console.log($(this).text());
				 countArray.push(+$(this).text());
			});
			$(data).find("OccurrenceTime_done").each(function(){
				// console.log($(this).text());
				 nameArray_done.push($(this).text());
			});
			$(data).find("count_done").each(function(){
				// console.log($(this).text());
				 countArray_done.push(+$(this).text());
			});
			countArray.unshift('資安事件數量');
			countArray_done.unshift('資安事件已結案數量');
			var chart = c3.generate({
					bindto: '#chartA',
					data: {
						columns: [
							countArray,
							countArray_done
						],
						type:  'area-step'
					},axis: {
						x: {
							type: 'category',
							categories: nameArray,
							tick: {
								count: 3
							}
						}
					}
			});
			 
		 }
	});
	return 0;
 }

function Call_retrieve_c3_chartB_ajax(url){
	$.ajax({
		 url: url+'ajax/chart.php',
		 cache: false,
		 dataType:'html',
		 type:'GET',
		 data: {chartID:'chartB'},
		 error: function(xhr) {
			 alert('Ajax failed');
		 },success: function(data) {
			 //console.log("data");
			 
			 var countArray18=[],countArray19=[];
			 var nameArray18=[],nameArray19=[];
			 //console.log($(data).find("month").length);
			$(data).find("month2018").each(function(){
				// console.log($(this).text());
				 nameArray18.push($(this).text());
			});
			$(data).find("count2018").each(function(){
				 //console.log($(this).text());
				 countArray18.push(+$(this).text());
			});
			$(data).find("month2019").each(function(){
				 //console.log($(this).text());
				 nameArray19.push($(this).text());
			});
			$(data).find("count2019").each(function(){
				// console.log($(this).text());
				 countArray19.push(+$(this).text());
			});
			//var countArray2 = countArray.slice();
			var d = new Date();
			var n = d.getFullYear();
			countArray18.unshift((n-1)+'資安事件(數量)');
			countArray19.unshift(n+'資安事件(數量)');
			//countArray2.unshift('data2');
			var chart = c3.generate({
					bindto: '#chartB',
					data: {
						columns: [
							countArray18,countArray19
						],
						type: 'bar'
					},axis: {
						x: {
							type: 'category',
							categories: nameArray18
						}
					},bar: {
						width: {
							ratio: 0.5 // this makes bar width 50% of length between ticks	
						}
					}
			});
			 
		 }
	});
	return 0;
 }

function Call_retrieve_c3_chartC_ajax(url){
	$.ajax({
		url: url+'ajax/chart.php',
		cache: false,
		dataType:'html',
		type:'GET',
		data: {chartID:'chartC'},
		error: function(xhr) {
			alert('Ajax failed');
		},success: function(data) {	 
			var countArray=[],Unitcount=[];
			var nameArray=[],UnitName=[];
			var countIP=[];
			var mArray=[];
			$(data).find("EventType").each(function(){
				// console.log($(this).text());
				 var name=$(this).children("name").text();
				 var count=$(this).children("count").text();
				 //console.log(name);
				 //console.log(count);
				 mArray.push([name,count]);

			});
			$(data).find("AgencyName").each(function(){
				//console.log($(this).text());
				 var name=$(this).children("name").text();
				 var count=$(this).children("count").text();
				 var IP_count=$(this).children("IP_count").text();
				 nameArray.push(name);
				 countArray.push(count);
				 countIP.push(IP_count);
			});
			//add the label of bar chart
			countArray.unshift('機關資安事件數量');
			countIP.unshift('機關資安事件IP數量');
		
			//the setting of pie chart
			var cht_width = '500px';	//default width
			var arr = $('.post_title');
			for(i = 0; i < arr.length; i++){
				if((width = $(arr[i]).css('width')) !== '0px'){
					cht_width = width.replace(/px/,'');
					break;	
				}
			}
			var cht_height = cht_width * 0.4205;

			var chart = c3.generate({
				bindto: '#chartC-2',
				data: {
				columns:
					mArray,
				type : 'pie'
				},
				size:{
					height: 360
				},
				onresize: function(){
			
				}
			});
			
			var chart = c3.generate({
				bindto: '#chartC',
				data:{ 
					columns: [countArray,countIP],
					type: 'bar'
				},axis: {
					rotated: true,
					x: {
						type: 'category',
						categories: nameArray,
						rotated: true
					}
				},bar: {
					width: {
						ratio: 0.5 // this makes bar width 50% of length between ticks	
					}
				},
				size:{
					height: 720
				}

			});

		}
	});
	return 0;
	
}	

function Call_retrieve_c3_chartD_ajax(url){
	$.ajax({
		url: url+'ajax/chart.php',
		cache: false,
		dataType:'html',
		type:'GET',
		data: {chartID:'chartD'},
		error: function(xhr) {
			alert('Ajax failed');
		},success: function(data) {	 
			var ouArray=[],total_VULArray=[],fixed_VULArray=[],percentArray=[];
			var unfixed_VULArray=[];
			$(data).find("VUL").each(function(){
				// console.log($(this).text());
				 var ou=$(this).children("ou").text();
				 var unfixed_VUL=$(this).children("unfixed_VUL").text();
				 //var total_VUL=$(this).children("total_VUL").text();
				 var fixed_VUL=$(this).children("fixed_VUL").text();
				 //console.log(name);
				 //console.log(count);
				 ouArray.push(ou);
				 unfixed_VULArray.push(-unfixed_VUL);
				 //total_VULArray.push(total_VUL);
				 fixed_VULArray.push(fixed_VUL);
				 percentArray.push(fixed_VUL);
			});
			//add the label of bar chart
			unfixed_VULArray.unshift('待修補數量');
			//total_VULArray.unshift('漏洞數量');
			fixed_VULArray.unshift('已修補數量');
			//percentArray.unshift('data3');
		

			var chart = c3.generate({
				bindto: '#chartD',
				size: {
					//height: 800,
					//width: 600
				},
				data:{ 
					columns: [unfixed_VULArray,fixed_VULArray],
					//columns: [total_VULArray,fixed_VULArray],
					type: 'bar',
					groups:[
						['待修補數量','已修補數量']
						//['漏洞數量','已修補數量']
					],
					colors: {
						待修補數量: '#d62728',
						已修補數量: '#2ca02c'
					},
					labels:{
						format:{
							待修補數量: d3.format(''),
							已修補數量: d3.format(''),
						}
					}
				},axis: {
					rotated: true,
					x: {
							type: 'category',
							categories: ouArray
					}

				},bar: {
					width: {
						ratio: 0.5 // this makes bar width 50% of length between ticks	
					}
				},
			});

		}
	});
	return 0;
}	

function Call_retrieve_c3_chartE_ajax(url){
	$.ajax({
		url: url+'ajax/chart.php',
		cache: false,
		dataType:'html',
		type:'GET',
		data: {chartID:'chartE'},
		error: function(xhr) {
			alert('Ajax failed');
		},success: function(data) {	 
			var mArray=[];
			var nArray=[];
			var passArray=[];
			var total_ip=0;
			$(data).find("OSEnvID").each(function(){
				// console.log($(this).text());
				 var name=$(this).children("name").text();
				 var count=$(this).children("count").text();
				 //console.log(name);
				 //console.log(count);
				 mArray.push([name,count]);

			});
			$(data).find("drip_vlan").each(function(){
				// console.log($(this).text());
				 var name=$(this).children("name").text();
				 var count=$(this).children("count").text();
				 //console.log(name);
				 //console.log(count);
				 nArray.push([name,count]);
				 total_ip = total_ip + parseInt(count);
			});
			$(data).find("gcb_pass").each(function(){
				 var total_count=$(this).children("total_count").text();
				 var pass_count=$(this).children("pass_count").text();
				 //passArray.push(Math.round((pass_count / total_count*10000) / 100));
				 passArray.push(pass_count / total_count * 100);
			});
			passArray.unshift('通過率');
			//the setting of pie chart
			var cht_width = '500px';	//default width
			var arr = $('.post_title');
			for(i = 0; i < arr.length; i++){
				if((width = $(arr[i]).css('width')) !== '0px'){
					cht_width = width.replace(/px/,'');
					break;	
				}
			}
			var cht_height = cht_width * 0.4205;

			var chart = c3.generate({
				bindto: '#chartE',
				data: {
				columns:
					mArray,
				type : 'pie'
				},
				pie:{
					label: {
						/*format: function (value, ratio, id) {
							  return d3.format()(value);
						}*/
					}
				},
				size:{
					height: 360
				},
				tooltip:{ 
					format: { 
						value: function (value, ratio, id) {
							return Math.round(ratio * 1000)/10+'% | '+value;
						} 
					} 
				}

				,
				onresize: function(){
			
				}
			});

			var chart = c3.generate({
				bindto: '#chartG',
				data: {
				columns:
					nArray,
				type : 'donut'
				},
				donut:{
					title: "IP總數:"+total_ip,
					label: {
						/*format: function (value, ratio, id) {
							  return d3.format()(value);
						}*/
					}
				},
				size:{
					height: 360
				},
				tooltip:{ 
					format: { 
						value: function (value, ratio, id) {
							return Math.round(ratio * 1000)/10+'% | '+value;
						} 
					} 
				}

				,
				onresize: function(){
			
				}
			});
			
			var chart = c3.generate({
				bindto: '#chartF',
				data: {
					columns: [
						passArray
					],
					type: 'gauge',
					onclick: function (d, i) { console.log("onclick", d, i); },
					onmouseover: function (d, i) { console.log("onmouseover", d, i); },
					onmouseout: function (d, i) { console.log("onmouseout", d, i); }
				},
				gauge: {
				},
				color: {
					pattern: ['#FF0000', '#F97600', '#F6C600', '#60B044'], // the three color levels for the percentage values.
					threshold: {
						values: [30, 60, 90, 100]
					}
				},
				size: {
					height: 180
				}
			});
		}
	});
	return 0;
}	



