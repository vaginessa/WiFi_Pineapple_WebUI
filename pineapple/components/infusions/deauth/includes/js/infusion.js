var deauth_auto_refresh;
var deauth_showDots;

var deauth_showLoadingDots = function() {
    clearInterval(deauth_showDots);
	if (!$("#deauth_loadingDots").length>0) return false;
    deauth_showDots = setInterval(function(){            
        var d = $("#deauth_loadingDots");
        d.text().length >= 3 ? d.text('') : d.append('.');
    },300);
}

$.fn.toggleClick=function() {
	var functions=arguments, iteration=0
		return this.click(function(){
			functions[iteration].apply(this,arguments)
			iteration= (iteration+1) %functions.length
		})
}

function deauth_myajaxStart()
{
	$('#deauth_loading').css("background-image", "url(/includes/img/throbber.gif)");
	
	if(deauth_auto_refresh == null)
	{
		$("#deauth.refresh_text").html('<em>Loading<span id="deauth_loadingDots"></span></em>'); 
		deauth_showLoadingDots();
	}
}

function deauth_myajaxStop(msg)
{
	$('#deauth_loading').css("background-image", "url(/includes/img/refresh.png)");
	
	if(deauth_auto_refresh == null)
	{
		$("#deauth.refresh_text").html(msg); 
		clearInterval(deauth_showDots);
	}
}

function deauth_init_small() {
	
	deauth_refresh_tile();
	
}

function deauth_init() {
	
	deauth_refresh();
	deauth_refresh_available_ap('whitelist');
	deauth_refresh_available_ap('blacklist');
	
	deauth_refresh_config();
	
	$("#deauth ul").idTabs();
	
	$("#deauth_auto_refresh").toggleClick(function() {
			$("#deauth_auto_refresh").html('<font color="lime">On</font>');
			$('#auto_time').attr('disabled', 'disabled');
			
			deauth_auto_refresh = setInterval(
			function ()
			{
				deauth_refresh();
			},
			$("#auto_time").val());
		}, function() {
			$("#deauth_auto_refresh").html('<font color="red">Off</font>');
			$('#auto_time').removeAttr('disabled');
							
            clearInterval(deauth_auto_refresh);
			deauth_auto_refresh = null;
	});
}

function deauth_append(what, which) {
	if($('#'+which).val() != "")
		$('#'+which).val($('#'+which).val() + '\n' + what);
	else
		$('#'+which).val(what);
}

function deauth_refresh() {
	$.ajax({
		type: "GET",
		data: "log",
		beforeSend: deauth_myajaxStart(),
		url: "/components/infusions/deauth/includes/data.php",
		success: function(msg){
			$("#deauth_output").val(msg).scrollTop($("#deauth_output")[0].scrollHeight - $("#deauth_output").height());
			
			deauth_myajaxStop('');
		}
	});
}



function deauth_update_conf(data, what) {
	$.ajax({
		type: "POST",
		data: "set_conf="+what+"&newdata="+data,
		beforeSend: deauth_myajaxStart(),
		url: "/components/infusions/deauth/includes/conf.php",
		success: function(msg){
			deauth_myajaxStop(msg);
		}
	});
}

function deauth_toggle(action) {	
	$.get('/components/infusions/deauth/includes/actions.php?deauth&'+action, {int: $("#interfaces_list").val(), mon: $("#monitorInterfaces_list").val()}, function() {
		refresh_small('deauth','infusions');
	});
	
	if(action == 'stop') {
		$("#deauth_link").html('<strong>Start</strong>');
		$("#deauth_status").html('<font color="red"><strong>&#10008;</strong></font>');
		$("#deauth_link").attr("href", "javascript:deauth_toggle('start');");
		$('#deauth_output').val("Stopping WiFi Deauth...");
	}
	else {
		$("#deauth_link").html('<strong>Stop</strong>');
		$("#deauth_status").html('<font color="lime"><strong>&#10004;</strong></font>');
		$("#deauth_link").attr("href", "javascript:deauth_toggle('stop');");
		$('#deauth_output').val("Starting WiFi Deauth...");
	}
}

function deauth_toggle_small(action) {	
	$.get('/components/infusions/deauth/includes/actions.php?deauth&'+action, {int: $("#deauth_interfaces_small").val(), mon: $("#deauth_monitorInterfaces_small").val()});
	
	if(action == 'stop') {
		$("#deauth_link_small").html('<strong>Start</strong>');
		$("#deauth_status_small").html('<font color="red"><strong>&#10008;</strong></font>');
		$("#deauth_link_small").attr("href", "javascript:deauth_toggle_small('start');");
		$('#deauth_output_small').val("Stopping WiFi Deauth...");
	}
	else {
		$("#deauth_link_small").html('<strong>Stop</strong>');
		$("#deauth_status_small").html('<font color="lime"><strong>&#10004;</strong></font>');
		$("#deauth_link_small").attr("href", "javascript:deauth_toggle_small('stop');");
		$('#deauth_output_small').val("Starting WiFi Deauth...");
	}
}

function deauth_boot_toggle(action) {
	$.get('/components/infusions/deauth/includes/actions.php?boot', {action: action});
	
	if(action == 'disable') {
		$("#boot_link").html('<strong>Enable</strong>');
		$("#boot_status").html('<font color="red"><strong>&#10008;</strong></font>');
		$("#boot_link").attr("href", "javascript:deauth_boot_toggle('enable');");
	}
	else {
		$("#boot_link").html('<strong>Disable</strong>');
		$("#boot_status").html('<font color="lime"><strong>&#10004;</strong></font>');
		$("#boot_link").attr("href", "javascript:deauth_boot_toggle('disable');");
	}
}

function deauth_refresh_config() {
	$.ajax({
		type: "GET",
		data: "get_conf",
		beforeSend: deauth_myajaxStart(),
		url: "/components/infusions/deauth/includes/conf.php",
		success: function(msg){
			deauth_myajaxStop('');
			
			$("#deauth_content_conf").html(msg);
		}
	});
}

function deauth_set_config() {
	$.ajax({
		type: "POST",
		data: $("#deauth_form_conf").serialize(),
		beforeSend: deauth_myajaxStart(),
		url: "/components/infusions/deauth/includes/conf.php",
		success: function(msg){
			deauth_myajaxStop(msg);
			
			$('#deauth_output').val('Configuration has been saved.');
		}
	});
}

function deauth_interface_toggle(interface, action) {		
	$.ajax({
		type: "POST",
		data: "interface=1&action="+action+"&int="+interface,
		beforeSend: deauth_myajaxStart(),
		url: "/components/infusions/deauth/includes/actions.php",
		success: function(msg){
			deauth_myajaxStop(msg);
			
			deauth_refresh(); deauth_refresh_interfaces();
			
			deauth_refresh_allinterfaces();
						
			deauth_refresh_tile();
		}
	});

}

function deauth_monitor_toggle(interface, monitor, action) {	
	$.ajax({
		type: "POST",
		data: "monitor=1&action="+action+"&int="+interface+"&mon="+monitor,
		beforeSend: deauth_myajaxStart(),
		url: "/components/infusions/deauth/includes/actions.php",
		success: function(msg){
			deauth_myajaxStop(msg);
			
			deauth_refresh(); deauth_refresh_interfaces(); deauth_refresh_monitors();
			
			deauth_refresh_allinterfaces();
						
			deauth_refresh_tile();
		}
	});
}

function deauth_refresh_allinterfaces() {
	$('#sidePanelContent_int').load('/components/infusions/deauth/includes/interfaces.php?interface');
}

function deauth_refresh_interfaces() {
	var previous_val = $('#interfaces_list option:selected').text();
	$('#interfaces_l').load('/components/infusions/deauth/includes/interfaces.php?interface_l', function() {
		$('#interfaces_list').val(previous_val);
	});
}

function deauth_refresh_monitors() {
	var previous_val = $('#monitorInterfaces_list option:selected').text();
	$('#monitorInterface_l').load('/components/infusions/deauth/includes/interfaces.php?monitor_l', function() {
		$('#monitorInterfaces_list').val(previous_val);
	});
}

function deauth_show_ap(what) {
    $.get('/components/infusions/deauth/includes/ap.php', {w: what}, function(data){
	    $('.popup_content').html(data);
	    $('.popup').css('visibility', 'visible');
    });
}

function deauth_refresh_available_ap(which) {
	$.ajax({
		type: "GET",
		data: "available_ap&mon="+$("#monitorInterfaces_list").val()+"&int="+$("#interfaces_list").val(),
		beforeSend: deauth_myajaxStart(),
		url: "/components/infusions/deauth/includes/data.php",
		success: function(msg){
			$("#list_"+which).html(msg);
			deauth_myajaxStop('');
			$('#list_' + which + ' li').click(function() { 
				var append_value = '# ' + $(this).attr("name") + '\n' + $(this).attr("address");
				deauth_append(append_value,which);
				return false;
			});
		}
	});
}

function deauth_reload() {
	draw_large_tile('deauth', 'infusions');
	refresh_small('deauth','infusions');
}

function deauth_install(where) {
	$.ajax({
		type: "GET",
		data: "install&where=" + where,
		beforeSend: deauth_myajaxStart(),
		url: "/components/infusions/deauth/includes/actions.php",
		success: function(msg){
			$("#deauth_output").val(msg);
			deauth_myajaxStop('');
			
			deauth_reload();
		}
	});
}

function deauth_refresh_tile() {
	$.ajax({
		type: "GET",
		data: "log",
		beforeSend: deauth_myajaxStart(),
		url: "/components/infusions/deauth/includes/data.php",
		success: function(msg){
			deauth_myajaxStop('');
			$("#deauth_output_small").val(msg).scrollTop($("#deauth_output_small")[0].scrollHeight - $("#deauth_output_small").height());
		}
	});
}