$(document).ready(function(){	
	//修正首頁的.front位置，分頁讀到這行會報錯，所以獨立成一個檔案
	//$('#banner-wrapper .front').css("left",$('#menu ul li a:first-child').offset().left-$('#banner-wrapper').offset().left);

	$('#banner-wrapper #1').css("left",$('#menu ul li:first-child').offset().left-$('#banner-wrapper').offset().left);
	$('#banner-wrapper #2').css("left",$('#menu ul li:nth-child(2)').offset().left-$('#banner-wrapper').offset().left);
	$('#banner-wrapper #3').css("left",$('#menu ul li:nth-child(3)').offset().left-$('#banner-wrapper').offset().left);
	$('#banner-wrapper #4').css("left",$('#menu ul li:nth-child(4)').offset().left-$('#banner-wrapper').offset().left);
	$('#banner-wrapper #5').css("left",$('#menu ul li:nth-child(5)').offset().left-$('#banner-wrapper').offset().left);
	$('#banner-wrapper #6').css("left",$('#menu ul li:nth-child(6)').offset().left-$('#banner-wrapper').offset().left);

});