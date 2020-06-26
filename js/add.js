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
function ldap_edit(isActive) {
	console.log('ldap edit'+isActive);
	var type = $('#form-ldap input[name=type]').val();
	if(type == 'search'){
		var displayname = $('#form-ldap input[name=displayname]').val();
		var title = $('#form-ldap input[name=title]').val();
		var mail = $('#form-ldap input[name=mail]').val();
		if(displayname=='' || title =='' || mail ==''){
			alert('您有必填欄位未輸入');
			return 0;
		}
	}else if(type == 'newuser'){
		var organizationalUnit = $('#form-ldap input[name=organizationalUnit]').val();
		var cn = $('#form-ldap input[name=cn]').val();
		var new_password = $('#form-ldap input[name=new_password]').val();
		var confirm_password = $('#form-ldap input[name=confirm_password]').val();
		var displayname = $('#form-ldap input[name=displayname]').val();
		var title = $('#form-ldap input[name=title]').val();
		var mail = $('#form-ldap input[name=mail]').val();
		if(organizationalUnit=='' || cn=='' || new_password=='' || confirm_password=='' ||  displayname=='' || title =='' || mail ==''){
			alert('您有必填欄位未輸入');
			return 0;
		}
	}

	var selector = ".post.ldap ";
	$.ajax({
		 url: 'ajax/sub_ldap_edit.php',
		 cache: false,
		 dataType:'html',
		 type:'GET',
		 data:$('#form-ldap').serialize()+'&isActive='+isActive,
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


function Call_drip_block_IP_ajax(type,ip,response){
	$('.ui.inline.loader').addClass('active');
	console.log(type);
	$.ajax({
		 url: 'ajax/drip_block_IP.php',
		 cache: false,
		 dataType:'html',
		 type:'GET',
		 data: {type:type,ip:encodeURIComponent(ip)},
		 error: function(xhr) {
			 alert('Ajax failed');
		 },success: function(data) {
			 $('.ui.inline.loader').removeClass('active');
			 console.log("success");
			 response.html("");
			 response.html(data);
			 console.log("done");
		 }
	});
	return 0;
 }

function Call_sub_query_pagination_ajax(page,key,keyword,type){
	 var selector = ".post."+type+" ";
	 if(type == 'gcb_client_list' || type == 'wsus_client_list' || type == 'antivirus_client_list' || type == 'drip_client_list')  selector = ".post.is_client_list .tab-content."+type+" ";
	 $.ajax({
		 url: 'ajax/sub_query.php',
		 cache: false,
		 dataType:'html',
		 type:'GET',
		 data: {page:page,key:key,keyword:keyword,type:type},
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




function Call_retrieve_gs_ajax(){
	$.ajax({
		 url: 'ajax/retrieve.php',
		 cache: false,
		 dataType:'html',
		 type:'GET',
		 data: {key:1,keyword:2},
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
		 data: {key:1,keyword:2},
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
		 data: {key:1,keyword:2},
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
		 data: {key:1,keyword:2},
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

function Call_sub_query_ajax(type,ap){
	var selector = ".post."+type+" ";
	if(type == 'gcb_client_list' || type == 'wsus_client_list' || type == 'antivirus_client_list' || type == 'drip_client_list'){
		 selector = ".post.is_client_list .tab-content."+type+" ";
	 }
	var key = $(selector+'#key').val();
	var keyword = $(selector+'#keyword').val();
	if (typeof key != 'undefined' && key !='' && keyword !=''  && type !='') {
		//ap='csv'
		if(ap=='csv'){
				window.location.assign("https://sdc-iss.tainan.gov.tw/ajax/sub_query.php?key="+encodeURI(key)+"&keyword="+encodeURI(keyword)+"&type="+type+"&ap="+ap);
		}else{
			//ap='html'
			$.ajax({
				 url: 'ajax/sub_query.php',
				 cache: false,
				 dataType:'html',
				 type:'GET',
				 data: {key:key,keyword:keyword,type:type,ap:ap},
				 error: function(xhr) {
					 alert('Ajax failed');
				 },success: function(data) {
					 $(selector+'.record_content').html("");
					 $(selector+'.record_content').html(data);
				 }
			});
		}
	}else{
		alert("沒有輸入");
	}
	return 0;
 }

function Call_sub_query_pagination_ajax(page,key,keyword,type){
	 var selector = ".post."+type+" ";
	 if(type == 'gcb_client_list' || type == 'wsus_client_list' || type == 'antivirus_client_list' || type == 'drip_client_list')  selector = ".post.is_client_list .tab-content."+type+" ";
	 $.ajax({
		 url: 'ajax/sub_query.php',
		 cache: false,
		 dataType:'html',
		 type:'GET',
		 data: {page:page,key:key,keyword:keyword,type:type},
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

function Call_sub_vs_query_ajax(type,ap){
	var jsonObj = [];
	$('.post.ip_and_url_scanResult span.query_label').each(function () {
		var id = $(this).attr("title");
		var email = $(this).val();
		item = {}
		item ["keyword"] = $(this).attr("keyword");
		item ["key"] 	 = $(this).attr("key");
		jsonObj.push(item);
	});
	jsonObj = JSON.stringify(jsonObj);
	//console.log(jsonObj);     
	var selector = ".post."+type+" ";
	var obj = $(selector+"input[name='status[]']");
	var unfinished = obj[0].checked;
	var finished   = obj[1].checked;
	var key 	= $(selector+'#key').val();
	var keyword = $(selector+'#keyword').val();
	if ( (typeof key != 'undefined' && key !='' && keyword !=''  && type !='') || jsonObj !='[]') {
		//ap='csv'
		if(ap=='csv'){
			window.location.assign("https://sdc-iss.tainan.gov.tw/ajax/sub_vs_query.php?key="+encodeURI(key)+"&keyword="+encodeURI(keyword)+"&type="+type+"&unfinished="+unfinished+"&finished="+finished+"&ap="+ap+"&jsonObj="+encodeURIComponent(jsonObj));
		}else{
		//ap='html'
			$.ajax({
				 url: 'ajax/sub_vs_query.php',
				 cache: false,
				 dataType:'html',
				 type:'GET',
				 data: {key:key,keyword:keyword,type:type,unfinished:unfinished,finished:finished,ap:ap,jsonObj:jsonObj},
				 error: function(xhr) {
					 alert('Ajax failed');
				 },success: function(data) {
					 $(selector+'.record_content').html("");
					 $(selector+'.record_content').html(data);
				 }
			});
		}
	}else{
			alert("沒有輸入");
	}
	return 0;
}

function Call_sub_vs_query_pagination_ajax(page,key,keyword,type,unfinished,finished){
	 var jsonObj = [];
	 $('.post.ip_and_url_scanResult span.query_label').each(function () {
		var id = $(this).attr("title");
		var email = $(this).val();
		item = {}
		item ["keyword"] = $(this).attr("keyword");
		item ["key"] 	 = $(this).attr("key");
		jsonObj.push(item);
	 });
	 jsonObj = JSON.stringify(jsonObj);
	 //console.log(jsonObj);     
	 var selector = ".post."+type+" ";
	 $.ajax({
		 url: 'ajax/sub_vs_query.php',
		 cache: false,
		 dataType:'html',
		 type:'GET',
		 data: {page:page,key:key,keyword:keyword,type:type,unfinished:unfinished,finished:finished,jsonObj:jsonObj},
		 error: function(xhr) {
			 alert('Ajax failed');
		 },success: function(data) {
			 $(selector+'.record_content').html("");
			 $(selector+'.record_content').html(data);
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
		 data: {key:1,keyword:2},
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
	var selector = ".post.ldap ";
	$(selector+'.ui.inline.loader').addClass('active');
	$.ajax({
		 url: 'ajax/sub_ldap.php',
		 cache: false,
		 dataType:'html',
		 type:'GET',
		 data: {target:$(selector+'.target').val(),type:type},
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
		 url: 'ajax/ldap_computer_tree.php',
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
	var subpage = (getParameterByName('subpage',url)!= null)?getParameterByName('subpage',url):1;	
	var tab = (getParameterByName('tab',url)!= null)?getParameterByName('tab',url):1;	
	
	//console.log(subpage);	
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
		keyword	 			= $(this).attr('keyword');
		type 				= $(this).attr('type');
		unfinished 			= $(this).attr('unfinished');
		finished 			= $(this).attr('finished');
		Call_sub_vs_query_pagination_ajax(page,key,keyword,type,unfinished,finished);
	});
	/*query.php's component action*/
	$('.post.security_event .record_content, .post.tainangov_security_Incident .record_content, .post.security_contact .record_content, .post.is_client_list .tab-content.gcb_client_list .record_content, .post.is_client_list .tab-content.wsus_client_list .record_content, .post.is_client_list .tab-content.antivirus_client_list .record_content, .post.is_client_list .tab-content.drip_client_list .record_content').delegate('.ui.pagination.menu > .item', 'click', function() {
		page				 = $(this).attr('page');
		key 				 = $(this).attr('key');
		keyword 		 	 = $(this).attr('keyword');
		type 				 = $(this).attr('type');
		Call_sub_query_pagination_ajax(page,key,keyword,type);
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
	/*vul.php's component action*/
	$('.post.ip_and_url_scanResult i.square.icon').click(function() {
		var selector = ".post.ip_and_url_scanResult ";
		var key 	= $(selector+'#key').val();
		var keyword = $(selector+'#keyword').val();
		if (typeof key != 'undefined' && key !='' && keyword !='') {
			var query_content = $(selector+'.query_content');
			var query_label ="<span class='query_label' keyword='"+keyword+"' key='"+key+"'>"+keyword+"："+key+"<button type='button' class='close' style='opacity:0.2'><i class='close icon'></i></button></span>";
			query_content.append(query_label);
		}else{
			alert("沒有輸入");
		}
	});
	/*vul.php's component action*/
	$('.post.ip_and_url_scanResult .query_content').delegate('button.close', 'click', function() {
		var span = $(this).parent('span');
		var query_content = $('.post.ip_and_url_scanResult .query_content');
		span.remove();
	});

	//bind keypress
	 $('.post.security_event #key').keyup(function(event) {
		 if(event.keyCode == 13 ) {
			console.log('keyup');     
			Call_sub_query_ajax('security_event','html'); 
		}
	 });
	 $('.post.tainangov_security_Incident #key').keyup(function(event) {
		 if(event.keyCode == 13 ) {
			console.log('keyup');     
			Call_sub_query_ajax('tainangov_security_Incident','html'); 
		}
	 });
	 $('.post.security_contact #key').keyup(function(event) {
		 if(event.keyCode == 13 ) {
			console.log('keyup');     
			Call_sub_query_ajax('security_contact','html'); 
		}
	 });
	 $('.post.is_client_list .tab-content.gcb_client_list #key').keyup(function(event) {
		 if(event.keyCode == 13 ) {
			console.log('keyup');     
			Call_sub_query_ajax('gcb_client_list','html'); 
		}
	 });
	 $('.post.is_client_list .tab-content.wsus_client #key').keyup(function(event) {
		 if(event.keyCode == 13 ) {
			console.log('keyup');     
			Call_sub_query_ajax('wsus_client_list','html'); 
		}
	 });
	 $('.post.is_client_list .tab-content.antivirus_client #key').keyup(function(event) {
		 if(event.keyCode == 13 ) {
			console.log('keyup');     
			Call_sub_query_ajax('antivirus_client_list','html'); 
		}
	 });
	 $('.post.is_client_list .tab-content.drip_client #key').keyup(function(event) {
		 if(event.keyCode == 13 ) {
			console.log('keyup');     
			Call_sub_query_ajax('drip_client_list','html'); 
		}
	 });
	 $('.post.ip_and_url_scanResult #key').keyup(function(event) {
		 if(event.keyCode == 13 ) {
			console.log('keyup');     
			Call_sub_vs_query_ajax('ip_and_url_scanResult','html'); 
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
			Call_retrieve_ldap_ajax('search');	
		}
	 });

	//bind serach_btn
	$('.post.security_event #search_btn').click(function (){
		console.log('security_event serach_btn');     
		Call_sub_query_ajax('security_event','html');
		console.log('security_event serach_btn done');
	});

	$('.post.tainangov_security_Incident #search_btn').click(function (){
		console.log('tainangov_security_Incident serach_btn');     
		Call_sub_query_ajax('tainangov_security_Incident','html');
		console.log('tainangov_security_Incident serach_btn done');
	});

	$('.post.security_contact #search_btn').click(function (){
		console.log('security_contact serach_btn');     
		Call_sub_query_ajax('security_contact','html');
		console.log('security_contact serach_btn done');
	});
	
	$('.post.is_client_list .tab-content.gcb_client_list #search_btn').click(function (){
		console.log('gcb_client_list serach_btn');     
		Call_sub_query_ajax('gcb_client_list','html');
		console.log('gcb_client_list serach_btn done');
	});
	
	$('.post.is_client_list .tab-content.wsus_client_list #search_btn').click(function (){
		console.log('wsus_client_list serach_btn');     
		Call_sub_query_ajax('wsus_client_list','html');
		console.log('wsus_client_list serach_btn done');
	});
	
	$('.post.is_client_list .tab-content.antivirus_client_list #search_btn').click(function (){
		console.log('antivirus_client_list serach_btn');     
		Call_sub_query_ajax('antivirus_client_list','html');
		console.log('antivirus_client_list serach_btn done');
	});
	
	$('.post.is_client_list .tab-content.drip_client_list #search_btn').click(function (){
		console.log('drip_client_list serach_btn');     
		Call_sub_query_ajax('drip_client_list','html');
		console.log('drip_client_list serach_btn done');
	});
	
	$('.post.is_client_list .tab-content.drip_client_list #export2csv_btn').click(function (){
		console.log('drip_client_list exp_btn');     
		Call_sub_query_ajax('drip_client_list','csv');
		console.log('drip_client_list exp_btn done');
	});

	$('.post.ip_and_url_scanResult #search_btn').click(function (){
		console.log('ip_and_url_scanResult serach_btn');     
		Call_sub_vs_query_ajax('ip_and_url_scanResult','html');
		console.log('ip_and_url_scanResult serach_btn done');
	});

	$('.post.ip_and_url_scanResult #export2csv_btn').click(function (){
		
		Call_sub_vs_query_ajax('ip_and_url_scanResult','csv');
		console.log('ip_and_url_scanResult exp_btn done');
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
	//retrieve from ldap search
	$('.post.ldap #ldap_search_btn').click(function(){
		Call_retrieve_ldap_ajax('search');	
	});
	//retrieve from ldap newuser
	$('.post.ldap #ldap_newuser_btn').click(function(){
		Call_retrieve_ldap_ajax('newuser');	
	});
	//retrieve from hydra
	$('.post.hydra #hydra_btn').click(function(){
		Call_retrieve_hydra_ajax('hydra');
		console.log("hydra");
	});
	//retrieve from drip_IP_block
	$('.post.is_client_list #block-btn').click(function(){
		var response = $(this).parent().find('.block_IP_response');
		console.log($(this).attr('data-ip'));
		console.log("block-btn");
		Call_drip_block_IP_ajax('block',$(this).attr('data-ip'),response);
	});
	//retrieve from drip_IP_unblock
	$('.post.is_client_list #unblock-btn').click(function(){
		var response = $(this).parent().find('.block_IP_response');
		console.log($(this).attr('data-ip'));
		console.log("unblock-btn");
		Call_drip_block_IP_ajax('unblock',$(this).attr('data-ip'),response);
	});
	
	//semantic progress bar
	$('.yckuo.progress').progress();

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

	// mobile launch button
	$('#example .fixed.main.menu .item.launch').click(function(){
		console.log("launch");
		$('#toc.ui.left.sidebar').toggleClass('overlay visible');			
 	});
	// push sidebar to left
	$('.pusher').click(function(){
		$('#toc.ui.left.sidebar').removeClass('overlay visible');			
 	});

});
