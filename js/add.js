function Call_retrieve_gs_ajax(){
	$.ajax({
		 url: 'ajax/retrieve.php',
		 cache: false,
		 dataType:'html',
		 type:'GET',
		 data: {key:1,keyword_type:2},
		 error: function(xhr) {
			 alert('Ajax failed');
		 },success: function(response) {
			 $('.retrieve_info').html("");
			 $('.retrieve_info').html(response);
		 }
	});
	return 0;
}

function getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}
//change mainpage and subpage
function change_page()
{
	var url=location.href;
	console.log(url);
	
	if(getParameterByName('subpage',url)!= null){
		var subpage = getParameterByName('subpage',url);
	}else{
		var subpage = 1;
	}
	//console.log(subpage);	
	var num = subpage-1;
	//console.log(num);
	var title = $('#sidebar li').find('.title')[num]; 
	var content = $('#content').find('.sub-content')[num];
	$('#sidebar li .title').removeClass('active');
	$(title).addClass('active');
	$('#content .sub-content').removeClass('show').hide();
	$(content).addClass('show').show();

}

$(document).ready(function(){

	change_page();
	

	$('#menu a')
		.hover(
		function(){
			var num = $(this).parent().index();
			console.log(num);
			var content = $('#page').find('.front')[num];
			$(content).stop().show();
		},
		function(){
			$('.front').delay(300).hide(0);
		});
		
	$('#menu a').click(function(){
		if(!$(this).hasClass('selected'))
		{
			$('#menu a').removeClass('selected');
			$(this).addClass('selected');

		}
	});
	
	//$('.front').css("width",$('#menu ul').width());
	
	//修正分頁的.front位置
	$('#page #1').css("left",$('#menu ul li:first-child').offset().left-$('#page').offset().left);
	$('#page #2').css("left",$('#menu ul li:nth-child(2)').offset().left-$('#page').offset().left);
	$('#page #3').css("left",$('#menu ul li:nth-child(3)').offset().left-$('#page').offset().left);
	$('#page #4').css("left",$('#menu ul li:nth-child(4)').offset().left-$('#page').offset().left);
	$('#page #5').css("left",$('#menu ul li:nth-child(5)').offset().left-$('#page').offset().left);
	$('#page #6').css("left",$('#menu ul li:nth-child(6)').offset().left-$('#page').offset().left);

	
	$('.front').hover(
		function(){
			console.log('front');
			$(this).stop().show();
		},
		function(){
			$(this).stop().hide();
		})

	$('.front .sub').hover(
		function(){
			$(this).css('color','#fff');
		},
		function(){
			$(this).css('color','#ddd');
		})
	
	$('#sidebar a').css('text-decoration','none');

	//sidebar內容切換
	$('#sidebar li li').click(function(){
			var num = $(this).index();
			console.log(num);
			var content = $('#content').find('.sub-content')[num];
			$('#sidebar li li').removeClass('active');
			$(this).addClass('active');
			$('#content .sub-content').removeClass('show').hide();
			$(content).addClass('show').show();
	});
	
	/*自己頁面的submenu內容切換*/

	/*query.php's component action*/
	$('.record_content').delegate('.ui.list > .item a', 'click', function() {
		var icon = $(this).find('i.icon');
		var detail = $(this).parent().find('.description');
		if(detail.hasClass('show')){
			icon.removeClass('up').addClass('down');
			detail.removeClass('show');
		}else{
			icon.removeClass('down').addClass('up');
			detail.addClass('show');
		}
	});

	//retrieve from Google Sheets
	$('#gs_btn').click(function(){
		Call_retrieve_gs_ajax();	
	});
	//mobile menu icon
	$('.ui.container a').click(function(){
		console.log("a");
		var menu = $('#menu');
		//$('#menu').toggleClass('show');
		if(menu.hasClass('show')){
			menu.removeClass('show').animate({"display":"none"},"slow");
		}else{
			menu.addClass('show').animate({"display":"block"},"slow");
		}
		
	});

});
