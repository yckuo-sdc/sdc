/******Main*******/
$(document).ready(function(){

	pageSwitch();
	
	//sidebar switch
	$('#sidebar li li').click(function(){
		var num = $(this).index();
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
	
	//tabular tab switch
	$('.tabular.menu .item').click(function(){
		var num = $(this).index();
		var content = $('.ui.attached.segment').find('.tab-content')[num];
		$('.tabular.menu .item').removeClass("active");
		$(this).addClass('active');
		$('.ui.attached.segment .tab-content').removeClass('show').hide();
		$(content).addClass('show').show();
		// push state for changing browser history
		var url=location.href;
		var page=location.pathname.split("/");
		var mainpage = page[1];	
		var subpage = page[2];	
		var tab = num + 1;
		history.pushState({"mainpage": mainpage,"subpage": subpage,"tab": tab},"", "/"+mainpage+"/"+subpage+"/?tab="+tab);
	});

	/*vul_query.php's component action*/
	$('.post.ip_and_url_scanResult .record_content').on('click', '.ui.pagination.menu > .item', function() {
		var page = $(this).attr('page');
		var key = $(this).attr('key');
		var keyword	= $(this).attr('keyword');
		var type = $(this).attr('type');
		var jsonStatus = $(this).attr('jsonStatus');
		var jsonObj = $(this).attr('jsonObj');
		sub_vul_query_pagination_ajax(page,key,keyword,type,jsonStatus,jsonObj);
	});

	/*query.php's component action*/
	$('.post.network .record_content').on('click', '.ui.pagination.menu > .item', function() {
		var data_array = [	
			{name : "page", value: $(this).attr('page')},
			{name : "key", value: $(this).attr('key')},
			{name : "keyword", value: $(this).attr('keyword')},
			{name : "operator", value: $(this).attr('operator')},
			{name : "type", value: $(this).attr('type')},
			{name : "jsonObj", value: $(this).attr('jsonObj')}
		];
		sub_ips_query_pagination_ajax(data_array);
	});

	/*query.php's component action*/
	$('.post.security_event .record_content, .post.ncert .record_content, .post.security_contact .record_content, .post.is_client_list .tab-content.gcb_client_list .record_content, .post.is_client_list .tab-content.wsus_client_list .record_content, .post.is_client_list .tab-content.antivirus_client_list .record_content, .post.is_client_list .tab-content.drip_client_list .record_content').on('click', '.ui.pagination.menu > .item', function() {
		var data_array = [	
			{name : "page", value: $(this).attr('page')},
			{name : "key", value: $(this).attr('key')},
			{name : "keyword", value: $(this).attr('keyword')},
			{name : "type", value: $(this).attr('type')},
			{name : "jsonObj", value: $(this).attr('jsonObj')}
		];

		sub_query_pagination_ajax(data_array);
	});
	
	/*query.php & vul_query's component action*/
	$('.record_content').on('click', '.ui.list > .item a', function() {
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
	$('.ou_vs_content').on('click', '.ou_block a', function() {
		var icon = $(this).find('i.icon');
		var detail = $(this).closest('.ou_block').find('.description');
		if(detail.hasClass('show')){
			icon.removeClass('up').addClass('down');
			detail.removeClass('show');
		}else{
			icon.removeClass('down').addClass('up');
			detail.addClass('show');
		}
	});
	
	/*query.php's component action*/
	$('.post.isac .dynamiclists i.icon').on('click', function() {
		var icon = $(this);
		var detail = $(this).closest('.dynamiclists').find('.remaining.card');
		if(detail.hasClass('show')){
			icon.removeClass('down').addClass('right');
			detail.removeClass('show');
		}else{
			icon.removeClass('right').addClass('down');
			detail.addClass('show');
		}
	});

	/*sub_ldap_tree.php's component action*/
	$('.ldap_tree_content').on('click', 'li > i.square.icon', function() {
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

	/*query.php's component action*/
	$('.post.is_client_list .tab-content.drip_client_list i.square.icon').click(function() {
		var selector 	= ".post.is_client_list .tab-content.drip_client_list ";
		var key 		= $(selector + '#key').val();
		var keyword 	= $(selector + '#keyword').val();
		var keyword_text= $(selector + '#keyword option:selected').text();
		if (typeof key != 'undefined' && key !='' && keyword !='') {
			var query_content = $(selector + '.query_content');
			var query_label ="<span class='query_label' keyword='"+keyword+"' key='"+key+"'>"+keyword_text+"="+key+"<button type='button' class='close' style='opacity:0.2'><i class='close icon'></i></button></span>";
			query_content.append(query_label);
		}else{
			alert("沒有輸入");
		}
	});

	/*query.php's component action*/
	$('.post.network .tab-content.yonghua i.square.icon').click(function() {
		var selector = ".post.network .tab-content.yonghua ";
		var key = $(selector + '#key').val();
		var keyword = $(selector + '#keyword').val();
		var operator = $(selector + '#operator').val();
		var keyword_text = $(selector + '#keyword option:selected').text();
		if (typeof key != 'undefined' && key !='' && keyword !='' && operator !='') {
			var query_content = $(selector + '.query_content');
			var query_label ="<span class='query_label' keyword='"+keyword+"' operator='"+operator+"' key='"+key+"'>"+keyword_text+operator+key+"<button type='button' class='close' style='opacity:0.2'><i class='close icon'></i></button></span>";
			query_content.append(query_label);
		}else{
			alert("沒有輸入");
		}
	});
	
	/*query.php's component action*/
	$('.post.network .tab-content.minjhih i.square.icon').click(function() {
		var selector = ".post.network .tab-content.minjhih ";
		var key = $(selector + '#key').val();
		var keyword = $(selector + '#keyword').val();
		var operator = $(selector + '#operator').val();
		var keyword_text = $(selector + '#keyword option:selected').text();
		if (typeof key != 'undefined' && key !='' && keyword !='' && operator !='') {
			var query_content = $(selector + '.query_content');
			var query_label ="<span class='query_label' keyword='"+keyword+"' operator='"+operator+"' key='"+key+"'>"+keyword_text+operator+key+"<button type='button' class='close' style='opacity:0.2'><i class='close icon'></i></button></span>";
			query_content.append(query_label);
		}else{
			alert("沒有輸入");
		}
	});
	
	/*query.php's component action*/
	$('.post.network .tab-content.idc i.square.icon').click(function() {
		var selector = ".post.network .tab-content.idc ";
		var key = $(selector + '#key').val();
		var keyword = $(selector + '#keyword').val();
		var operator = $(selector + '#operator').val();
		var keyword_text = $(selector + '#keyword option:selected').text();
		if (typeof key != 'undefined' && key !='' && keyword !='' && operator !='') {
			var query_content = $(selector + '.query_content');
			var query_label ="<span class='query_label' keyword='"+keyword+"' operator='"+operator+"' key='"+key+"'>"+keyword_text+operator+key+"<button type='button' class='close' style='opacity:0.2'><i class='close icon'></i></button></span>";
			query_content.append(query_label);
		}else{
			alert("沒有輸入");
		}
	});
	
	/*query.php's component action*/
	$('.post.network .tab-content.intrayonghua i.square.icon').click(function() {
		var selector = ".post.network .tab-content.intrayonghua ";
		var key = $(selector + '#key').val();
		var keyword = $(selector + '#keyword').val();
		var operator = $(selector + '#operator').val();
		var keyword_text = $(selector + '#keyword option:selected').text();
		if (typeof key != 'undefined' && key !='' && keyword !='' && operator !='') {
			var query_content = $(selector + '.query_content');
			var query_label ="<span class='query_label' keyword='"+keyword+"' operator='"+operator+"' key='"+key+"'>"+keyword_text+operator+key+"<button type='button' class='close' style='opacity:0.2'><i class='close icon'></i></button></span>";
			query_content.append(query_label);
		}else{
			alert("沒有輸入");
		}
	});
	
	/*query.php's component action*/
	$('.post.is_client_list .tab-content.drip_client_list .query_content, .post.network .tab-content.yonghua .query_content, .post.network .tab-content.minjhih .query_content, .post.network .tab-content.idc .query_content, .post.network .tab-content.intrayonghua .query_content').on('click', 'button.close', function() {
		var span = $(this).parent('span');
		span.remove();
	});

	/*vul.php's component action*/
	$('.post.ip_and_url_scanResult i.square.icon').click(function() {
		var selector = ".post.ip_and_url_scanResult ";
		var key = $(selector + '#key').val();
		var keyword = $(selector + '#keyword').val();
		var keyword_text = $(selector + '#keyword option:selected').text();
		if (typeof key != 'undefined' && key !='' && keyword !='') {
			var query_content = $(selector + '.query_content');
			var query_label ="<span class='query_label' keyword='"+keyword+"' key='"+key+"'>"+keyword_text+"="+key+"<button type='button' class='close' style='opacity:0.2'><i class='close icon'></i></button></span>";
			query_content.append(query_label);
		}else{
			alert("沒有輸入");
		}
	});

	/*vul.php's component action*/
	$('.post.ip_and_url_scanResult .query_content').on('click', 'button.close', function() {
		var span = $(this).parent('span');
		span.remove();
	});

	/*bind form submit*/

	$('.post.security_event form').on('submit', function(){
		sub_query_ajax('security_event','html');
	});

	$('.post.ncert form').on('submit', function(){
		sub_query_ajax('ncert','html');
	});

	$('.post.security_contact form').on('submit', function(){
		sub_query_ajax('security_contact','html');
	});
	
	$('.post.is_client_list .tab-content.gcb_client_list form').on('submit', function(){
		sub_query_ajax('gcb_client_list','html');
	});
	
	$('.post.is_client_list .tab-content.wsus_client_list form').on('submit', function(){
		sub_query_ajax('wsus_client_list','html');
	});
	
	$('.post.is_client_list .tab-content.antivirus_client_list form').on('submit', function(){
		sub_query_ajax('antivirus_client_list','html');
	});
	
	$('.post.is_client_list .tab-content.drip_client_list form').on('submit', function(){
		sub_query_ajax('drip_client_list','html');
	});
	
	$('.post.is_client_list .tab-content.drip_client_list #export2csv_btn').click(function (){
		sub_query_ajax('drip_client_list','csv');
	});

	$('.post.network .tab-content.yonghua form').on('submit', function(){
		sub_ips_query_ajax('yonghua');
	});
	
	$('.post.network .tab-content.minjhih form').on('submit', function(){
		sub_ips_query_ajax('minjhih');
	});
	
	$('.post.network .tab-content.idc form').on('submit', function(){
		sub_ips_query_ajax('idc');
	});
	
	$('.post.network .tab-content.intrayonghua form').on('submit', function(){
		sub_ips_query_ajax('intrayonghua');
	});
	
	$('.post.ip_and_url_scanResult form').on('submit', function(){
		sub_vul_query_ajax('ip_and_url_scanResult','html');
	});

	$('.post.ip_and_url_scanResult #export2csv_btn').click(function (){
		sub_vul_query_ajax('ip_and_url_scanResult','csv');
	});

	/*bind retrieve button*/

	//Google Sheets of security events
	$('#gs_event_btn').click(function(){
		retrieve_gs_ajax();	
	});
	//Google Sheets of Ncert security incidents
	$('#gs_ncert_event_btn').click(function(){
		retrieve_ncert_event_gs_ajax();
	});
	//GCB API
	$('#gcb_api_btn').click(function(){
		retrieve_gcb_ajax();	
	});
	//Vulnerability Scans
	$('#vs_btn').click(function(){
		retrieve_vul_ajax();	
	});
	//Nmap
	$('.post.nmap #nmap_btn').click(function(){
		retrieve_nmap_ajax('nmap');	
	});
	//LDAP search
	$('.post.ldap #ldap_search_btn').click(function(){
		retrieve_ldap_ajax('search');	
	});
	//LDAP newuser
	$('.post.ldap #ldap_newuser_btn').click(function(){
		retrieve_ldap_ajax('newuser');	
	});
	//Hydra
	$('.post.hydra #hydra_btn').click(function(){
		retrieve_hydra_ajax('hydra');
	});
	//drip_IP_block
	$('.post.is_client_list #block-btn').click(function(){
		var response = $(this).parent().find('.block_IP_response');
		drip_block_IP_ajax('block',$(this).attr('data-ip'),response);
	});
	//drip_IP_unblock
	$('.post.is_client_list #unblock-btn').click(function(){
		var response = $(this).parent().find('.block_IP_response');
		drip_block_IP_ajax('unblock',$(this).attr('data-ip'),response);
	});
	
	//semantic progress bar
	//$('.yckuo.progress').progress().removeClass('active');
	$('.yckuo.progress').progress();

	//semantic file input
	$("input:text").click(function() {
		  $(this).parent().find("input:file").click();
	});
	$('input:file', '.ui.action.input').on('change', function(e){
		var name = e.target.files[0].name;
		$('input:text', $(e.target).parent()).val(name);
	});	
	
	//semantic modal display
	$("#modal_btn").on('click', function(){
		$('.ui.modal').modal('show');
    });	

	$("#upload_Form").on('submit',(function(e){
		e.preventDefault();
		$.ajax({
			url: "/ajax/upload_contact.php",
			type: "POST",
			data:  new FormData(this),
			contentType: false,
			cache: false,
			processData:false,
			success: function(data){
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
	
	$('#sidebar a').css('text-decoration','none');

});


/******Custom Functions*******/

//change hydra's password mode between single and mutiple input
function hydra_pwd_mode(type){
	var input = $('.post.hydra input[name=self_password]');
	if(type == 'yes'){
		input.prop('disabled', false);
	}else{
		input.prop('disabled', true);
	}
}

//ldap_edit
function ldap_edit() {
	var selector = ".post.ldap ";
	var isActive = $(selector+'form input[name=stateSwitch]').prop("checked");
	var type = $(selector+'form input[name=type]').val();
	var input = $(selector+'form').serializeArray();
	var obj = [	{name : "isActive", value: isActive} ];
    input = input.concat(obj);

	// input validation
	switch(type){
		case 'edituser':
			var requirement = ['displayname', 'title', 'mail'];
			console.log(requirement);
			var v = 0;
			input.forEach(function(item, index, array) {
				if(requirement.indexOf(item.name) >= 0 && item.value == ""){
					v = 1;
				}
			});
			
			if(v == 1){		
				alert('您有必填欄位未輸入');
				return ;
			}
			break;
		case 'newuser':
			var requirement = ['organizationalUnit', 'cn', 'new_password', 'confirm_password', 'displayname', 'title', 'mail'];
			console.log(requirement);
			var v = 0;
			input.forEach(function(item, index, array) {
				if(requirement.indexOf(item.name) >= 0 && item.value == ""){
					v = 1;
				}
			});
			
			if(v == 1){		
				alert('您有必填欄位未輸入');
				return ;
			}
			break;
		default:
			break;
	}

	// end of input validation

	$.ajax({
		 url: '/ajax/sub_ldap_edit.php',
		 cache: false,
		 dataType:'html',
		 type:'GET',
		 data:input,
		 error: function(xhr) {
			 alert('Ajax failed');
		 },success: function(data) {
			$(selector + '.record_content').html(data);
		 }
	});
	return 0;
}

function ldap_clear() {
	console.log('ldap clear');
	var selector = ".post.ldap ";
	$(selector + '.record_content').html("");
	return 0;
}

function drip_block_IP_ajax(type,ip,response){
	$('.ui.inline.loader').addClass('active');
	console.log(type);
	$.ajax({
		 url: '/ajax/drip_block_IP.php',
		 cache: false,
		 dataType:'html',
		 type:'GET',
		 data: {type:type,ip:encodeURIComponent(ip)},
		 error: function(xhr) {
			 alert('Ajax failed');
		 },success: function(data) {
			 $('.ui.inline.loader').removeClass('active');
			 console.log("success");
			 response.html(data);
			 console.log("done");
		 }
	});
	return 0;
 }

function sub_query_ajax(type, ap){
	var selector = ".post."+type+" ";
	if(type == 'gcb_client_list' || type == 'wsus_client_list' || type == 'antivirus_client_list' || type == 'drip_client_list'){
		 selector = ".post.is_client_list .tab-content."+type+" ";
	}
	var jsonObj = [];
	$(selector + 'span.query_label').each(function () {
		var item = {}
		item ["keyword"] = $(this).attr("keyword");
		item ["key"] 	 = $(this).attr("key");
		jsonObj.push(item);
	});
	jsonObj = JSON.stringify(jsonObj);
	
	// Encode a set of form elements as an array of names and values
	var input = $(selector+'form').serializeArray();
	var obj = [	{name : "jsonObj", value: jsonObj},
				{name : "type", value: type},
				{name : "ap", value: ap}		];
    input = input.concat(obj);

	// input validation
	var v1 = 0, v2 = 0;
	input.forEach(function(item, index, array) {
		if(item.name == "jsonObj" && item.value == "[]"){
			v1 = 1;	
		}else if(item.name != "jsonObj" && item.value == ""){
			v2 = 1;	
		}
	});
	if(v1 && v2){
		alert("沒有輸入");
        return;
	}

	//ap='csv'
	if(ap=='csv'){
		window.location.assign("/ajax/sub_query.php?"+$.param(input));
	}else{
	//ap='html'
		$.ajax({
			 url: '/ajax/sub_query.php',
			 cache: false,
			 dataType:'html',
			 type:'GET',
			 data: input,
			 error: function(xhr) {
				 alert('Ajax failed');
			 },success: function(data) {
				 $(selector + '.record_content').html(data);
			 }
		});
	}
	
	return 0;
 }

function sub_query_pagination_ajax(data_array){
	var type = data_array[3].value;
	var selector = ".post."+type+" ";
	 if(type == 'gcb_client_list' || type == 'wsus_client_list' || type == 'antivirus_client_list' || type == 'drip_client_list')  selector = ".post.is_client_list .tab-content."+type+" ";
	 $.ajax({
		 url: '/ajax/sub_query.php',
		 cache: false,
		 dataType:'html',
		 type:'GET',
		 data: data_array,
		 error: function(xhr) {
			 alert('Ajax failed');
		 },success: function(data) {
			 $(selector + '.record_content').html(data);
		 }
	});
	return 0;
 }

function sub_ips_query_ajax(type){
	var selector = ".post.network .tab-content."+type+" ";
	var jsonObj = [];
	
	$(selector + 'span.query_label').each(function () {
		var item = {}
		item["keyword"] = $(this).attr("keyword");
		item["key"] = $(this).attr("key");
		item["operator"] = $(this).attr("operator");
		jsonObj.push(item);
	});
	jsonObj = JSON.stringify(jsonObj);
	
	// Encode a set of form elements as an array of names and values
	var input = $(selector+'form').serializeArray();
	var obj = [	{name : "jsonObj", value: jsonObj},
				{name : "type", value: type}		];
    input = input.concat(obj);

	// input validation
	var v1 = 0, v2 = 0;
	input.forEach(function(item, index, array) {
		if(item.name == "jsonObj" && item.value == "[]"){
			v1 = 1;	
		}else if(item.name != "jsonObj" && item.value == ""){
			v2 = 1;	
		}
	});
	if(v1 && v2){
		alert("沒有輸入");
        return;
	}
		
	//ap='html'
	$(selector + '.ui.inline.loader').addClass('active');
	$.ajax({
		 url: '/ajax/sub_ips_query.php',
		 cache: false,
		 dataType: 'html',
		 type: 'GET',
		 data: input,
		 error: function(xhr) {
			 alert('Ajax failed');
		 },success: function(data) {
			 $(selector + '.ui.inline.loader').removeClass('active');
			 $(selector + '.record_content').html(data);
		 }
	});
	
	return 0;
}

function load_ips_query_ajax(type){
	var selector = ".post.network .tab-content."+type+" ";
	var jsonObj =[];
	jsonObj = JSON.stringify(jsonObj);
	
	var input = [		
		{name : "key", value: "any"},
		{name : "keyword", value: "all"},
		{name : "operator", value: "="},
		{name : "jsonObj", value: jsonObj},
		{name : "type", value: type}		
	];

	// input validation
	var v1 = 0, v2 = 0;
	input.forEach(function(item, index, array) {
		if(item.name == "jsonObj" && item.value == "[]"){
			v1 = 1;	
		}else if(item.name != "jsonObj" && item.value == ""){
			v2 = 1;	
		}
	});
	if(v1 && v2){
		alert("沒有輸入");
        return;
	}
	
	//ap='html'
	$(selector + '.ui.inline.loader').addClass('active');
	$.ajax({
		 url: '/ajax/sub_ips_query.php',
		 cache: false,
		 dataType:'html',
		 type:'GET',
		 data: input,
		 error: function(xhr) {
			 alert('Ajax failed');
		 },success: function(data) {
			 $(selector + '.ui.inline.loader').removeClass('active');
			 $(selector + '.record_content').html(data);
		 }
	});
	
	return 0;
}

function sub_ips_query_pagination_ajax(data_array){
	 var type = data_array[4].value;
	 var selector = ".post.network .tab-content."+type+" ";
	 $.ajax({
		 url: '/ajax/sub_ips_query.php',
		 cache: false,
		 dataType:'html',
		 type:'GET',
		 data: data_array,
		 error: function(xhr) {
			 alert('Ajax failed');
		 },success: function(data) {
			 $(selector + '.record_content').html(data);
		 }
	});
	return 0;
 }

function sub_vul_query_ajax(type,ap){
	var selector = ".post."+type+" ";
	
	var jsonObj = [];
	$('.post.ip_and_url_scanResult span.query_label').each(function () {
		var id = $(this).attr("title");
		var email = $(this).val();
		var item = {}
		item ["keyword"] = $(this).attr("keyword");
		item ["key"] 	 = $(this).attr("key");
		jsonObj.push(item);
	});
	jsonObj = JSON.stringify(jsonObj);
	
	var obj = $(selector + "input[name='status[]']");
	var jsonStatus = {};
	jsonStatus ["overdue_and_unfinish"] = obj[0].checked;
	jsonStatus ["non_overdue_and_unfinish"] = obj[1].checked;
	jsonStatus ["finish"] = obj[2].checked;
	jsonStatus = JSON.stringify(jsonStatus);

	// Encode a set of form elements as an array of names and values
	var input = $(selector+'form').serializeArray();
	
	var key 	= $(selector + '#key').val();
	var keyword = $(selector + '#keyword').val();
	if ( (typeof key != 'undefined' && key !='' && keyword !=''  && type !='') || jsonObj !='[]') {
		//ap='csv'
		if(ap=='csv'){
			window.location.assign("/ajax/sub_vul_query.php?key="+encodeURI(key)+"&keyword="+encodeURI(keyword)+"&type="+type+"&ap="+ap+"&jsonStatus="+encodeURIComponent(jsonStatus)+"&jsonObj="+encodeURIComponent(jsonObj));
		}else{
		//ap='html'
			$.ajax({
				 url: '/ajax/sub_vul_query.php',
				 cache: false,
				 dataType:'html',
				 type:'GET',
				 data: {key:key,keyword:keyword,type:type,ap:ap,jsonStatus:jsonStatus,jsonObj:jsonObj},
				 error: function(xhr) {
					 alert('Ajax failed');
				 },success: function(data) {
					 $(selector + '.record_content').html(data);
				 }
			});
		}
	}else{
			alert("沒有輸入");
	}
	return 0;
}

function sub_vul_query_pagination_ajax(page,key,keyword,type,jsonStatus,jsonObj){
	 var selector = ".post."+type+" ";
	 $.ajax({
		 url: '/ajax/sub_vul_query.php',
		 cache: false,
		 dataType:'html',
		 type:'GET',
		 data: {page:page,key:key,keyword:keyword,type:type,jsonStatus:jsonStatus,jsonObj:jsonObj},
		 error: function(xhr) {
			 alert('Ajax failed');
		 },success: function(data) {
			 $(selector + '.record_content').html(data);
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

function retrieve_ou_vul_ajax(){
	var url;
	if (location.protocol == 'https:') url='https://sdc-iss.tainan.gov.tw/';
	else							   url='http://sdc-iss.tainan.gov.tw/';
	var selector = ".post.vul_overview ";
	$(selector + '.ui.inline.loader').addClass('active');
	$.ajax({
		 url: url+'ajax/fetch_ou_vul.php',
		 cache: false,
		 dataType:'html',
		 type:'GET',
		 data: {key:1,keyword:2},
		 error: function(xhr) {
			 alert('Ajax failed');
		 },success: function(data) {
			 $(selector + '.ui.inline.loader').removeClass('active');
			 $(selector + '.ou_vs_content').html(data);
		 }
	});
	return 0;
 }

function retrieve_gs_ajax(){
	$.ajax({
		 url: '/ajax/fetch_event.php',
		 cache: false,
		 dataType:'html',
		 type:'GET',
		 data: {key:1,keyword:2},
		 error: function(xhr) {
			 alert('Ajax failed');
		 },success: function(response) {
			 $('.retrieve_info').html(response);
		 }
	});
	return 0;
}

function retrieve_ncert_event_gs_ajax(){
	$.ajax({
		 url: '/ajax/fetch_ncert.php',
		 cache: false,
		 dataType:'html',
		 type:'GET',
		 data: {key:1,keyword:2},
		 error: function(xhr) {
			 alert('Ajax failed');
		 },success: function(response) {
			 $('.retrieve_info').html(response);
		 }
	});
	return 0;
}

function retrieve_gcb_ajax(){
	$.ajax({
		 url: '/ajax/fetch_gcb.php',
		 cache: false,
		 dataType:'html',
		 type:'GET',
		 data: {key:1,keyword:2},
		 error: function(xhr) {
			 alert('Ajax failed');
		 },success: function(response) {
			 $('.retrieve_info').html(response);
		 }
	});
	return 0;
}

function retrieve_vul_ajax(){
	$('.ui.inline.loader').addClass('active');
	$.ajax({
		 url: '/ajax/fetch_vul.php',
		 cache: false,
		 dataType:'html',
		 type:'GET',
		 data: {key:1,keyword:2},
		 error: function(xhr) {
			 alert('Ajax failed');
		 },success: function(response) {
			 $('.ui.inline.loader').removeClass('active');
			 $('.retrieve_vul').html(response);
		 }
	});
	return 0;
}

function retrieve_ldap_tree_ajax(){
	var selector = ".post.ldap_computer_tree ";
	var url;
	if (location.protocol == 'https:') url='https://' + location.hostname +'/';
	else							   url='http://' + location.hostname + '/';
    $(selector + '.ui.inline.loader').addClass('active');
	$.ajax({
		 url: url+'ajax/ldap_computer_tree.php',
		 cache: false,
		 dataType:'html',
		 type:'GET',
		 data: {},
		 error: function(xhr) {
			 alert('Ajax failed');
		 },success: function(data) {
             $(selector + '.ui.inline.loader').removeClass('active');
			 $(selector + '.ldap_tree_content').html(data);
		 }
	});
	return 0;
}

function retrieve_hydra_ajax(type){
	var selector = ".post."+type+" ";
	$(selector + '.ui.inline.loader').addClass('active');
	$.ajax({
		 url: '/ajax/sub_hydra.php',
		 cache: false,
		 dataType:'html',
		 type:'GET',
		 data: $(selector + 'form').serializeArray(),
		 error: function(xhr) {
			 alert('Ajax failed');
		 },success: function(data) {
			 $(selector + '.ui.inline.loader').removeClass('active');
			 $(selector + '.record_content').html(data);
		 }
	});
	return 0;
}

function retrieve_nmap_ajax(type){
	var selector = ".post."+type+" ";
	$(selector + '.ui.inline.loader').addClass('active');
	$.ajax({
		 url: '/ajax/sub_nmap.php',
		 cache: false,
		 dataType:'html',
		 type:'GET',
		 data: $(selector + 'form').serializeArray(),
		 error: function(xhr) {
			 alert('Ajax failed');
		 },success: function(data) {
			 $(selector + '.ui.inline.loader').removeClass('active');
			 $(selector + '.record_content').html(data);
		 }
	});
	return 0;
}

function retrieve_ldap_ajax(type){
	var selector = ".post.ldap ";
	$(selector + '.ui.inline.loader').addClass('active');
	$.ajax({
		 url: '/ajax/sub_ldap.php',
		 cache: false,
		 dataType:'html',
		 type:'GET',
		 data: {target:$(selector + '.target').val(),type:type},
		 error: function(xhr) {
			 alert('Ajax failed');
		 },success: function(data) {
			 $(selector + '.ui.inline.loader').removeClass('active');
			 $(selector + '.record_content').html(data);
		 }
	});
	return 0;
}

//change mainpage and subpage
function pageSwitch(){
	var url=location.href;
	var page=location.pathname.split("/");
	console.log(page[1]+page[2]);
	var mainpage = (typeof page[1] != 'undefined')? page[1]:'info';	
	var subpage = (typeof page[2] != 'undefined')?page[2]:'enews';	

	//var mainpage = (getParameterByName('mainpage',url)!= null)?getParameterByName('mainpage',url):'info';	
	//var subpage = (getParameterByName('subpage',url)!= null)?getParameterByName('subpage',url):1;	
	
	var tab = (getParameterByName('tab',url)!= null)?getParameterByName('tab',url):1;	
    switch(true){
		//load ips_query
      	case (mainpage == 'network' && subpage == 'search' ):
			load_ips_query_ajax('yonghua');
			load_ips_query_ajax('minjhih');
			load_ips_query_ajax('idc');
			load_ips_query_ajax('intrayonghua');
        	break;
		//load ldap's tree
      	case (mainpage == 'tool' && subpage == 'ldap' ):
			retrieve_ldap_tree_ajax();
        	break;
	 	//load ou_vs_content
     	case (mainpage == 'vul' && subpage == 'overview' ):
			retrieve_ou_vul_ajax();
        	break;
      	default:
        	break;
    }	
	var num = tab-1;
	var title   = $('.tabular.menu').find('.item')[num];
	var content = $('.ui.attached.segment').find('.tab-content')[num];
	$('.tabular.menu .item').removeClass("active");
	$(title).addClass('active');
	$('.ui.attached.segment .tab-content').removeClass('show').hide();
	$(content).addClass('show').show();
}

