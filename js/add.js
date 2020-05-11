function uncheck(ElementID) {
	if(ElementID == "new_password"){
		document.getElementById("new_password").disabled	 = true;
		document.getElementById("confirm_password").disabled = true;
	}else if(ElementID == "self_password"){
		document.getElementById("self_password").disabled	 = true;
	}
}
function check(ElementID) {
	if(ElementID == "new_password"){
		document.getElementById("new_password").disabled	 = false;
		document.getElementById("confirm_password").disabled = false;
	}else if(ElementID == "self_password"){
		document.getElementById("self_password").disabled	 = false;
	}
}
function ldap_edit() {
	console.log('ldap edit');
	var selector = ".post.ldap ";
	$.ajax({
		 url: 'ajax/sub_ldap_edit.php',
		 cache: false,
		 dataType:'html',
		 type:'GET',
		 data:$('#form-ldap').serialize(),
		 error: function(xhr) {
			 alert('Ajax failed');
		 },success: function(data) {
			$(selector+'.record_content').html("");
			$(selector+'.record_content').html(data);
		 }
	});
	return 0;
}

function ldap_clear() {
	console.log('ldap clear');
	var selector = ".post.ldap ";
	$(selector+'.record_content').html("");
	return 0;
}



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

function Call_retrieve_tainangov_security_incident_gs_ajax(){
	$.ajax({
		 url: 'ajax/retrieve_tainangov_security_incident.php',
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

function Call_retrieve_gcb_ajax(){
	$.ajax({
		 url: 'ajax/retrieve_gcb.php',
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

function Call_retrieve_vs_ajax(){
	$('.ui.inline.loader').addClass('active');
	$.ajax({
		 url: 'ajax/retrieve_vs.php',
		 cache: false,
		 dataType:'html',
		 type:'GET',
		 data: {key:1,keyword_type:2},
		 error: function(xhr) {
			 alert('Ajax failed');
		 },success: function(response) {
			 $('.ui.inline.loader').removeClass('active');
			 $('.retrieve_vs_info').html("");
			 $('.retrieve_vs_info').html(response);
		 }
	});
	return 0;
}

function Call_sub_query_ajax(type){

	 var selector = ".post."+type+" ";
	 if(type == 'gcb_client_list' || type == 'wsus_client_list' || type == 'antivirus_client_list')  selector = ".post.is_client_list .tab-content."+type+" ";
		 
	 $.ajax({
		 url: 'ajax/sub_query.php',
		 cache: false,
		 dataType:'html',
		 type:'GET',
		 data: {key:$(selector+'#key').val(),keyword_type:$(selector+'#keyword_type').val(),type:type},
		 error: function(xhr) {
			 alert('Ajax failed');
		 },success: function(data) {
			 //console.log("success");
			 $(selector+'.record_content').html("");
			 $(selector+'.record_content').html(data);
			 //console.log("done");
		 }
	});
	return 0;
 }

function Call_sub_query_pagination_ajax(page,key,keyword_type,type){
	 var selector = ".post."+type+" ";
	 if(type == 'gcb_client_list' || type == 'wsus_client_list' || type == 'antivirus_client_list')  selector = ".post.is_client_list .tab-content."+type+" ";
	 $.ajax({
		 url: 'ajax/sub_query.php',
		 cache: false,
		 dataType:'html',
		 type:'GET',
		 data: {page:page,key:key,keyword_type:keyword_type,type:type},
		 error: function(xhr) {
			 alert('Ajax failed');
		 },success: function(data) {
			 //console.log("success");
			 $(selector+'.record_content').html("");
			 $(selector+'.record_content').html(data);
			 //console.log("done");
		 }
	});
	return 0;
 }

function Call_sub_vs_query_ajax(type){
	 var selector = ".post."+type+" ";
	 var obj = $(selector+"input[name='status[]']");
	 var unfinished = obj[0].checked;
	 var finished   = obj[1].checked;
	 $.ajax({
		 url: 'ajax/sub_vs_query.php',
		 cache: false,
		 dataType:'html',
		 type:'GET',
		 data: {key:$(selector+'#key').val(),keyword_type:$(selector+'#keyword_type').val(),type:type,unfinished:unfinished,finished:finished},
		 error: function(xhr) {
			 alert('Ajax failed');
		 },success: function(data) {
			 //console.log("success");
			 $(selector+'.record_content').html("");
			 $(selector+'.record_content').html(data);
			 //console.log("done");
		 }
	});
	return 0;
}

function Call_sub_vs_query_pagination_ajax(page,key,keyword_type,type,unfinished,finished){
	 var selector = ".post."+type+" ";
	 $.ajax({
		 url: 'ajax/sub_vs_query.php',
		 cache: false,
		 dataType:'html',
		 type:'GET',
		 data: {page:page,key:key,keyword_type:keyword_type,type:type,unfinished:unfinished,finished:finished},
		 error: function(xhr) {
			 alert('Ajax failed');
		 },success: function(data) {
			 //console.log("success");
			 $(selector+'.record_content').html("");
			 $(selector+'.record_content').html(data);
			 //console.log("done");
		 }
	});
	return 0;
}

function Call_retrieve_ou_vs_ajax(){
	 $.ajax({
		 url: 'ajax/retrieve_ou_vs.php',
		 cache: false,
		 dataType:'html',
		 type:'GET',
		 data: {key:1,keyword_type:2},
		 error: function(xhr) {
			 alert('Ajax failed');
		 },success: function(data) {
			 //console.log("success");
			 $('.ou_vs_content').html("");
			 $('.ou_vs_content').html(data);
			 //console.log("done");
		 }
	});
	return 0;
 }


function Call_retrieve_nmap_ajax(type){
	var selector = ".post."+type+" ";
	$(selector+'.ui.inline.loader').addClass('active');
	$.ajax({
		 url: 'ajax/sub_nmap.php',
		 cache: false,
		 dataType:'html',
		 type:'GET',
		 data: {target:$(selector+'.target').val()},
		 error: function(xhr) {
			 alert('Ajax failed');
		 },success: function(data) {
			 $(selector+'.ui.inline.loader').removeClass('active');
			 $(selector+'.record_content').html("");
			 $(selector+'.record_content').html(data);
		 }
	});
	return 0;
}

function Call_retrieve_ldap_ajax(type){
	var selector = ".post."+type+" ";
	$(selector+'.ui.inline.loader').addClass('active');
	$.ajax({
		 url: 'ajax/sub_ldap.php',
		 cache: false,
		 dataType:'html',
		 type:'GET',
		 data: {target:$(selector+'.target').val()},
		 error: function(xhr) {
			 alert('Ajax failed');
		 },success: function(data) {
			 $(selector+'.ui.inline.loader').removeClass('active');
			 $(selector+'.record_content').html("");
			 $(selector+'.record_content').html(data);
		 }
	});
	return 0;
}

function Call_retrieve_ldap_tree_ajax(){
	var selector = ".post ";
	$.ajax({
		 url: 'ajax/sub_ldap_tree.php',
		 cache: false,
		 dataType:'html',
		 type:'GET',
		 data: {},
		 error: function(xhr) {
			 alert('Ajax failed');
		 },success: function(data) {
			 $(selector+'.ldap_tree_content').html("");
			 $(selector+'.ldap_tree_content').html(data);
		 }
	});
	return 0;
}

function Call_retrieve_hydra_ajax(type){
	var selector = ".post."+type+" ";
	$(selector+'.ui.inline.loader').addClass('active');
	console.log($(selector+"input[name='one_pwd_mode']:checked").val());
	$.ajax({
		 url: 'ajax/sub_hydra.php',
		 cache: false,
		 dataType:'html',
		 type:'GET',
		 data: {target:$(selector+'.target').val(),protocol:$(selector+'#protocol').val(),account:$(selector+'#account').val(),one_pwd_mode:$(selector+"input[name='one_pwd_mode']:checked").val(),self_password:$(selector+'#self_password').val()},
		 error: function(xhr) {
			 alert('Ajax failed');
		 },success: function(data) {
			 $(selector+'.ui.inline.loader').removeClass('active');
			 $(selector+'.record_content').html("");
			 $(selector+'.record_content').html(data);
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
	/*
	if(getParameterByName('subpage',url)!= null){
		var subpage = getParameterByName('subpage',url);
	}else{
		var subpage = 1;
	}
	*/
	var subpage = (getParameterByName('subpage',url)!= null)?getParameterByName('subpage',url):1;	
	var tab = (getParameterByName('tab',url)!= null)?getParameterByName('tab',url):1;	
	
	//console.log(subpage);	
	var num = subpage-1;
	//console.log(num);
	var title = $('#sidebar li').find('.title')[num]; 
	var content = $('#content').find('.sub-content')[num];
	$('#sidebar li .title').removeClass('active');
	$(title).addClass('active');
	$('#content .sub-content').removeClass('show').hide();
	$(content).addClass('show').show();
	

	var num = tab-1;
	var title   = $('.tabular.menu').find('.item')[num];
	var content = $('.ui.attached.segment').find('.tab-content')[num];
	$('.tabular.menu .item').removeClass("active");
	$(title).addClass('active');
	$('.ui.attached.segment .tab-content').removeClass('show').hide();
	$(content).addClass('show').show();

}

$(document).ready(function(){

	change_page();

	//auto retrieve GS
	//Call_retrieve_gs_ajax();
	//Call_retrieve_tainangov_security_incident_gs_ajax();
	
	//load ou_vs_content
	Call_retrieve_ou_vs_ajax();
	
	$('#sidebar a').css('text-decoration','none');

	//load ldap's tree
	Call_retrieve_ldap_tree_ajax();
	
	//sidebar內容切換
	$('#sidebar li li').click(function(){
			var num = $(this).index();
			console.log(num);
			var content = $('#content').find('.sub-content')[num];
			$('#sidebar li li').removeClass('active');
			$(this).addClass('active');
			$('#content .sub-content').removeClass('show').hide();
			$(content).addClass('show').show();
			
			// push state for changing browser history
			var url=location.href;
			if(getParameterByName('mainpage',url)!= null){
				var mainpage = getParameterByName('mainpage',url);
			}else{
				var mainpage = 1;
			}
			subpage = num + 1;
			history.pushState({"mainpage": mainpage,"subpage": subpage},"", "index.php?mainpage="+mainpage+"&subpage="+subpage);

	});
	
	//tabular tab 內容切換
	$('.tabular.menu .item').click(function(){
		var num = $(this).index();
		console.log(num);
		var content = $('.ui.attached.segment').find('.tab-content')[num];
		$('.tabular.menu .item').removeClass("active");
		$(this).addClass('active');
		$('.ui.attached.segment .tab-content').removeClass('show').hide();
		$(content).addClass('show').show();
		// push state for changing browser history
		var url=location.href;
		var mainpage = (getParameterByName('mainpage',url)!= null)?getParameterByName('mainpage',url):1;	
		var subpage = (getParameterByName('subpage',url)!= null)?getParameterByName('subpage',url):1;	
		var tab = num + 1;
		history.pushState({"mainpage": mainpage,"subpage": subpage,"tab": tab},"", "index.php?mainpage="+mainpage+"&subpage="+subpage+"&tab="+tab);

	});

	/*自己頁面的submenu內容切換*/

	/*vs_query.php's component action*/
	$('.post.ip_and_url_scanResult .record_content').delegate('.ui.pagination.menu > .item', 'click', function() {
		page				= $(this).attr('page');
		key 				= $(this).attr('key');
		keyword_type 		= $(this).attr('keyword_type');
		type 				= $(this).attr('type');
		unfinished 			= $(this).attr('unfinished');
		finished 			= $(this).attr('finished');
		Call_sub_vs_query_pagination_ajax(page,key,keyword_type,type,unfinished,finished);
	});
	/*query.php's component action*/
	$('.post.security_event .record_content, .post.tainangov_security_Incident .record_content, .post.security_contact .record_content, .post.is_client_list .tab-content.gcb_client_list .record_content, .post.is_client_list .tab-content.wsus_client_list .record_content, .post.is_client_list .tab-content.antivirus_client_list .record_content').delegate('.ui.pagination.menu > .item', 'click', function() {
		page				 = $(this).attr('page');
		key 				 = $(this).attr('key');
		keyword_type 		 = $(this).attr('keyword_type');
		type 				 = $(this).attr('type');
		Call_sub_query_pagination_ajax(page,key,keyword_type,type);
	});
	/*query.php & vs_query's component action*/
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
	/*query.php's component action*/
	$('.ou_vs_content').delegate('.ou_block > a', 'click', function() {
	//$('.ou_block > a').click(function() {
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
	/*sub_ldap_tree.php's component action*/
	$('.ldap_tree_content').delegate('li > i.square.icon', 'click', function() {
		var icon1 = $(this);
		var icon2 = $(this).parent('li').children('i.folder.icon');
		var detail = $(this).parent('li');
		if(detail.hasClass('hide')){
			detail.removeClass('hide');
			icon1.removeClass('plus').addClass('minus');
			icon2.addClass('open');
		}else{
			detail.addClass('hide');
			icon1.removeClass('minus').addClass('plus');
			icon2.removeClass('open');
		}
	});

	//bind keypress
	 $('.post.security_event #key').keyup(function(event) {
		 if(event.keyCode == 13 ) {
			console.log('keyup');     
			Call_sub_query_ajax('security_event'); 
		}
	 });
	 $('.post.tainangov_security_Incident #key').keyup(function(event) {
		 if(event.keyCode == 13 ) {
			console.log('keyup');     
			Call_sub_query_ajax('tainangov_security_Incident'); 
		}
	 });
	 $('.post.security_contact #key').keyup(function(event) {
		 if(event.keyCode == 13 ) {
			console.log('keyup');     
			Call_sub_query_ajax('security_contact'); 
		}
	 });
	 $('.post.is_client_list .tab-content.gcb_client_list #key').keyup(function(event) {
		 if(event.keyCode == 13 ) {
			console.log('keyup');     
			Call_sub_query_ajax('gcb_client_list'); 
		}
	 });
	 $('.post.is_client_list .tab-content.wsus_client #key').keyup(function(event) {
		 if(event.keyCode == 13 ) {
			console.log('keyup');     
			Call_sub_query_ajax('wsus_client_list'); 
		}
	 });
	 $('.post.is_client_list .tab-content.antivirus_client #key').keyup(function(event) {
		 if(event.keyCode == 13 ) {
			console.log('keyup');     
			Call_sub_query_ajax('antivirus_client_list'); 
		}
	 });
	 $('.post.ip_and_url_scanResult #key').keyup(function(event) {
		 if(event.keyCode == 13 ) {
			console.log('keyup');     
			Call_sub_vs_query_ajax('ip_and_url_scanResult'); 
		}
	 });
	 $('.post.ipscanResult #key').keyup(function(event) {
		 if(event.keyCode == 13 ) {
			console.log('keyup');     
			Call_sub_vs_query_ajax('ipscanResult'); 
		}
	 });
	 $('.post.urlscanResult #key').keyup(function(event) {
		 if(event.keyCode == 13 ) {
			console.log('keyup');     
			Call_sub_vs_query_ajax('urlscanResult'); 
		}
	 });
	 $('.post.nmap #target').keyup(function(event) {
		 if(event.keyCode == 13 ) {
			console.log('keyup');     
			Call_retrieve_nmap_ajax('nmap');	
		}
	 });
	 $('.post.ldap #target').keyup(function(event) {
		 if(event.keyCode == 13 ) {
			console.log('keyup');     
			Call_retrieve_ldap_ajax('ldap');	
		}
	 });

	//bind serach_btn
	$('.post.security_event #search_btn').click(function (){
		console.log('security_event serach_btn');     
		Call_sub_query_ajax('security_event');
		console.log('security_event serach_btn done');
	});

	$('.post.tainangov_security_Incident #search_btn').click(function (){
		console.log('tainangov_security_Incident serach_btn');     
		Call_sub_query_ajax('tainangov_security_Incident');
		console.log('tainangov_security_Incident serach_btn done');
	});

	$('.post.security_contact #search_btn').click(function (){
		console.log('security_contact serach_btn');     
		Call_sub_query_ajax('security_contact');
		console.log('security_contact serach_btn done');
	});
	
	$('.post.is_client_list .tab-content.gcb_client_list #search_btn').click(function (){
		console.log('gcb_client_list serach_btn');     
		Call_sub_query_ajax('gcb_client_list');
		console.log('gcb_client_list serach_btn done');
	});
	
	$('.post.is_client_list .tab-content.wsus_client_list #search_btn').click(function (){
		console.log('wsus_client_list serach_btn');     
		Call_sub_query_ajax('wsus_client_list');
		console.log('wsus_client_list serach_btn done');
	});
	
	$('.post.is_client_list .tab-content.antivirus_client_list #search_btn').click(function (){
		console.log('antivirus_client_list serach_btn');     
		Call_sub_query_ajax('antivirus_client_list');
		console.log('antivirus_client_list serach_btn done');
	});
	
	$('.post.ip_and_url_scanResult #search_btn').click(function (){
		console.log('ip_and_url_scanResult serach_btn');     
		Call_sub_vs_query_ajax('ip_and_url_scanResult');
		console.log('ip_and_url_scanResult serach_btn done');
	});

	$('.post.ipscanResult #search_btn').click(function (){
		console.log('ipscanResult serach_btn');     
		Call_sub_vs_query_ajax('ipscanResult');
		console.log('ipscanResult serach_btn done');
	});

	$('.post.urlscanResult #search_btn').click(function (){
		console.log('urlscanResult serach_btn');     
		Call_sub_vs_query_ajax('urlscanResult');
		console.log('urlscanResult serach_btn done');
	});

	//retrieve from Google Sheets of security events
	$('#gs_event_btn').click(function(){
		Call_retrieve_gs_ajax();	
	});
	//retrieve from Google Sheets of Ncert security incidents
	$('#gs_ncert_incident_btn').click(function(){
		Call_retrieve_tainangov_security_incident_gs_ajax();
	});
	//retrieve from GCB API
	$('#gcb_api_btn').click(function(){
		Call_retrieve_gcb_ajax();	
	});
	//retrieve from Vulnerability Scans
	$('#vs_btn').click(function(){
		Call_retrieve_vs_ajax();	
	});
	//retrieve from nmap
	$('.post.nmap #nmap_btn').click(function(){
		Call_retrieve_nmap_ajax('nmap');	
	});
	//retrieve from ldap
	$('.post.ldap #ldap_btn').click(function(){
		Call_retrieve_ldap_ajax('ldap');	
	});
	//retrieve from hydra
	$('.post.hydra #hydra_btn').click(function(){
		Call_retrieve_hydra_ajax('hydra');
		console.log("hydra");
	});
	
	
	//semantic file input
	$("input:text").click(function() {
		  $(this).parent().find("input:file").click();
	});
	$('input:file', '.ui.action.input').on('change', function(e){
		var name = e.target.files[0].name;
		$('input:text', $(e.target).parent()).val(name);
	});	
	$("#upload_Form").on('submit',(function(e){
		e.preventDefault();
		$.ajax({
			url: "ajax/upload_contact.php",
			type: "POST",
			data:  new FormData(this),
			contentType: false,
			cache: false,
			processData:false,
			success: function(data){
				$(".retrieve_ncert").html("");
				$(".retrieve_ncert").html(data);
			},
			error: function(){
			}               
		});
    }));	
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
