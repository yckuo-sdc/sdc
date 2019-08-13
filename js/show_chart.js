$(document).ready(function(){

	//pass variables by keyword_input
	//function Call_search_ajax(){
		 $.ajax({
			 url: 'ajax/retrieve_table.php',
			 cache: false,
			 dataType:'html',
			 type:'GET',
			 data: {key:1,keyword_type:$('#keyword_type').val()},
			 error: function(xhr) {
				 alert('Ajax failed');
			 },success: function(data) {
				 $('#record_content').html("");
				 $('#record_content').html(data);
				// window.location.hash = "query";
			 }
		});
	//	return 0;
	// }
	function Call_retrieve_c3_chartA_ajax(){
		 $.ajax({
			 url: 'ajax/chartA.php',
			 cache: false,
			 dataType:'html',
			 type:'GET',
			 data: {key:1},
			 error: function(xhr) {
				 alert('Ajax failed');
			 },success: function(data) {
			 	 //console.log("data");
				 
				 var countArray=[],nameArray=[];
				$(data).find("OccurrenceTime").each(function(){
					// console.log($(this).text());
					 nameArray.push($(this).text());
				});
				$(data).find("count").each(function(){
					// console.log($(this).text());
					 countArray.push($(this).text());
				});
				countArray.unshift('資安事件(數量)');
				var chart = c3.generate({
						bindto: '#chartA',
	    				data: {
							columns: [
								countArray
							]
						},axis: {
				   			x: {
								type: 'category',
								categories: nameArray
							}
						}
				});
				 
			 }
		});
		return 0;
	 }
	function Call_retrieve_c3_chartB_ajax(){
		 $.ajax({
			 url: 'ajax/chartB.php',
			 cache: false,
			 dataType:'html',
			 type:'GET',
			 data: {key:1},
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
					 countArray18.push($(this).text());
				});
				$(data).find("month2019").each(function(){
					 //console.log($(this).text());
					 nameArray19.push($(this).text());
				});
				$(data).find("count2019").each(function(){
					// console.log($(this).text());
					 countArray19.push($(this).text());
				});
				//var countArray2 = countArray.slice();
				countArray18.unshift('2018資安事件(數量)');
				countArray19.unshift('2019資安事件(數量)');
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

	function Call_retrieve_c3_chartC_ajax(){
	$.ajax({
		url: 'ajax/chartC.php',
		cache: false,
		dataType:'html',
		type:'GET',
		data: {key:1,keyword_type:2},
		error: function(xhr) {
			alert('Ajax failed');
		},success: function(data) {	 
			var countArray=[],Unitcount=[];
			var nameArray=[],UnitName=[];
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
				 nameArray.push(name);
				 countArray.push(count);
			});
			//add the label of bar chart
			countArray.unshift('機關資安事件數量');
		
			var chart = c3.generate({
				bindto: '#chartC',
	    		data:{ 
					columns: [countArray],
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
				}
			});
	
			c3.generate({
				bindto: '#chartC-2',
				data: {
				columns:
					mArray,
				type : 'pie'
				},
				size:{
					width: cht_width,
					height: cht_height
				},
				onresize: function(){
			
				}
			});
		}
	});
		
	}	



	//bind show_chart_btn
	$('#show_chart_btn').click(function (){
		Call_retrieve_c3_ajax();
	});
	
	
	// use c3js 
	var chart = c3.generate({
		bindto: '#chartB',
		data: {
			columns: [
				['data1', 30, 200, 100, 400, 150, 250],
				['data2', 50, 20, 10, 40, 15, 25]								       
			]
		}
	});

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

	//chartA
	Call_retrieve_c3_chartA_ajax();
	//chartB
	Call_retrieve_c3_chartB_ajax();
	//chartC
	Call_retrieve_c3_chartC_ajax();
	
	//student
	c3.generate({
		bindto: '#cht_student',
		data: {
			columns: [
				['男', 261],
				['女', 38]
			],
			type : 'pie'
		},
		size:{
			width: cht_width,
			height: cht_height
		},
		onresize: function(){
			
		}
	});
	
	//graduation
	c3.generate({
		bindto: '#cht_graduate1',
		data: {
			columns: [
				['IC design與IC製造業', 21.74],
				['電腦及周邊製造業與IT系統廠商', 16.21],
				['服兵役', 11.86],
				['網通業(軟體+硬體)', 11.86],
				['法人或其他研究單位', 8.7],
				['半導體晶圓代工(台積電、聯電)', 7.51],
				['網通業(軟體)', 7.11],
				['光電產業', 6.72],
				['學術', 3.56],
				['其他類別科技業', 2.37],
				['非科技業', 1.19],
				['公司名稱未知', 0.79],
				['準備國考', 0.4]
			],
			type : 'pie'
		},
		size:{
			width: cht_width,
			height: cht_height
		},
		onresize: function(){
			
		}
	});

	c3.generate({
		bindto: '#cht_graduate2',
		data: {
			columns: [
				['台積電', 18],
				['聯發科', 13],
				['華碩', 13],
				['宏達電', 12],
				['中華電信', 10],
				['鴻海', 7],
				['瑞昱', 8]
			],
			type : 'pie'
		},
		size:{
			width: cht_width,
			height: cht_height
		},
		onresize: function(){}
	});
});
