var sslsplit_auto_refresh;
var sslsplit_showDots;

var sslsplit_showLoadingDots = function() {
    clearInterval(sslsplit_showDots);
	if (!$("#sslsplit_loadingDots").length>0) return false;
    sslsplit_showDots = setInterval(function(){            
        var d = $("#sslsplit_loadingDots");
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

function sslsplit_myajaxStart()
{
	$('#sslsplit_loading').css("background-image", "url(/includes/img/throbber.gif)");
	
	if(sslsplit_auto_refresh == null)
	{
		$("#sslsplit.refresh_text").html('<em>Loading<span id="sslsplit_loadingDots"></span></em>'); 
		sslsplit_showLoadingDots();
	}
}

function sslsplit_myajaxStop(msg)
{
	$('#sslsplit_loading').css("background-image", "url(/includes/img/refresh.png)");
	
	if(sslsplit_auto_refresh == null)
	{
		$("#sslsplit.refresh_text").html(msg); 
		clearInterval(sslsplit_showDots);
	}
}

function sslsplit_init_small() {
	
	sslsplit_refresh_tile();
}

function sslsplit_init() {

	sslsplit_refresh();
	sslsplit_refresh_history();
	
	$("#sslsplit ul").idTabs();
				
    $("#sslsplit_auto_refresh").toggleClick(function() {
			$("#sslsplit_auto_refresh").html('<font color="lime">On</font>');
			$('#auto_time').attr('disabled', 'disabled');
			
			sslsplit_auto_refresh = setInterval(
			function ()
			{
				sslsplit_refresh();
			},
			$("#auto_time").val());
		}, function() {
			$("#sslsplit_auto_refresh").html('<font color="red">Off</font>');
			$('#auto_time').removeAttr('disabled');
				
            clearInterval(sslsplit_auto_refresh);
			sslsplit_auto_refresh = null;
		});	
}

function sslsplit_toggle(action) {
	
	$.get('/components/infusions/sslsplit/includes/actions.php?sslsplit&'+action, function() { refresh_small('sslsplit','infusions'); });

	if(action == 'stop') {
		$("#sslsplit_link").html('<strong>Start</strong>');
		$("#sslsplit_status").html('<font color="red"><strong>&#10008;</strong></font>');
		$("#sslsplit_link").attr("href", "javascript:sslsplit_toggle('start');");
		$('#sslsplit_output').val('sslsplit has been stopped...');	
				
		sslsplit_refresh_history();
	}
	else {
		$("#sslsplit_link").html('<strong>Stop</strong>');
		$("#sslsplit_status").html('<font color="lime"><strong>&#10004;</strong></font>');
		$("#sslsplit_link").attr("href", "javascript:sslsplit_toggle('stop');");
		$('#sslsplit_output').val('sslsplit is running...');
				
		sslsplit_refresh_history();
	}
}

function sslsplit_cert(action) {
	
	$.get('/components/infusions/sslsplit/includes/actions.php?cert&'+action);

	if(action == 'remove') {
		$("#sslsplit_cert_link").html('<strong>Generate</strong>');
		$("#sslsplit_cert_status").html('<font color="red"><strong>&#10008;</strong></font>');
		$("#sslsplit_cert_link").attr("href", "javascript:sslsplit_cert('generate');");
		$('#sslsplit_output').val('certificate has been deleted...');			
	}
	else {
		$("#sslsplit_cert_link").html('<strong>Remove</strong>');
		$("#sslsplit_cert_status").html('<font color="lime"><strong>&#10004;</strong></font>');
		$("#sslsplit_cert_link").attr("href", "javascript:sslsplit_cert('remove');");
		$('#sslsplit_output').val('certificate has been generated...');	
	}
}

function sslsplit_toggle_small(action) {
	
	$.get('/components/infusions/sslsplit/includes/actions.php?sslsplit&'+action);

	if(action == 'stop') {
		$("#sslsplit_link_small").html('<strong>Start</strong>');
		$("#sslsplit_status_small").html('<font color="red"><strong>&#10008;</strong></font>');
		$("#sslsplit_link_small").attr("href", "javascript:sslsplit_toggle_small('start');");
		$('#sslsplit_output_small').val('sslsplit has been stopped...');	
	}
	else {
		$("#sslsplit_link_small").html('<strong>Stop</strong>');
		$("#sslsplit_status_small").html('<font color="lime"><strong>&#10004;</strong></font>');
		$("#sslsplit_link_small").attr("href", "javascript:sslsplit_toggle_small('stop');");
		$('#sslsplit_output_small').val('sslsplit is running...');
	}
}

function sslsplit_refresh() {	
	$.ajax({
		type: "GET",
		data: "lastlog",
		beforeSend: sslsplit_myajaxStart(),
		url: "/components/infusions/sslsplit/includes/data.php",
		success: function(msg){
			$("#sslsplit_output").val(msg).scrollTop($("#sslsplit_output")[0].scrollHeight - $("#sslsplit_output").height());
			
			sslsplit_myajaxStop('');
		}
	});
}

function sslsplit_refresh_history() {
	$.ajax({
		type: "GET",
		data: "history",
		beforeSend: sslsplit_myajaxStart(),
		url: "/components/infusions/sslsplit/includes/data.php",
		success: function(msg){
			$("#sslsplit_content_history").html(msg);
			sslsplit_myajaxStop('');
		}
	});
}

function sslsplit_load_file(which) {
    $.get('/components/infusions/sslsplit/includes/actions.php?load', {file: which}, function(data){
	    $('.popup_content').html(data);
	    $('.popup').css('visibility', 'visible');
    });
}

function sslsplit_delete_file(what, which) {
	$.ajax({
		type: "GET",
		data: "delete&file=" + which + "&" + what,
		beforeSend: sslsplit_myajaxStart(),
		url: "/components/infusions/sslsplit/includes/actions.php",
		success: function(msg){
			sslsplit_myajaxStop('');
			sslsplit_refresh_history();
		}
	});
}

function sslsplit_deleteall_history() {
	$.ajax({
		type: "GET",
		data: "deleteall",
		beforeSend: sslsplit_myajaxStart(),
		url: "/components/infusions/sslsplit/includes/actions.php",
		success: function(msg){
			sslsplit_myajaxStop('');
			sslsplit_refresh_history();
		}
	});
}

function sslsplit_clear_log() {
	$.ajax({
		type: "GET",
		data: "clearlog",
		beforeSend: sslsplit_myajaxStart(),
		url: "/components/infusions/sslsplit/includes/actions.php",
		success: function(msg){
			sslsplit_myajaxStop('');
			sslsplit_refresh();
		}
	});
}

function sslsplit_boot_toggle(action) {
	$.get('/components/infusions/sslsplit/includes/actions.php?boot', {action: action});

	if(action == 'disable') {
		$("#boot_link").html('<strong>Enable</strong>');
		$("#boot_status").html('<font color="red"><strong>&#10008;</strong></font>');
		$("#boot_link").attr("href", "javascript:sslsplit_boot_toggle('enable');");
	}
	else {
		$("#boot_link").html('<strong>Disable</strong>');
		$("#boot_status").html('<font color="lime"><strong>&#10004;</strong></font>');
		$("#boot_link").attr("href", "javascript:sslsplit_boot_toggle('disable');");
	}
}

function sslsplit_update_conf(data, what) {
	$.ajax({
		type: "POST",
		data: "set_conf="+what+"&newdata="+data,
		beforeSend: sslsplit_myajaxStart(),
		url: "/components/infusions/sslsplit/includes/conf.php",
		success: function(msg){
			sslsplit_myajaxStop(msg);
		}
	});
}

function sslsplit_reload() {
	draw_large_tile('sslsplit', 'infusions');
	refresh_small('sslsplit','infusions');
}

function sslsplit_install() {
	$.ajax({
		type: "GET",
		data: "install_dep",
		beforeSend: sslsplit_myajaxStart(),
		url: "/components/infusions/sslsplit/includes/actions.php",
		cache: false,
		success: function(msg){
		}
	});

    var loop=self.setInterval(
	function ()
	{
	    $.ajax({
			url: '/components/infusions/sslsplit/includes/status.php',
			cache: false,
			success: function(msg){
				if(msg != 'working')
				{
					sslsplit_reload();
					clearInterval(loop);
				}
			}
		});
	}
	,5000);
}

function sslsplit_refresh_tile() {
	$.ajax({
		type: "GET",
		data: "lastlog",
		beforeSend: sslsplit_myajaxStart(),
		url: "/components/infusions/sslsplit/includes/data.php",
		success: function(msg){
			sslsplit_myajaxStop('');
			$("#sslsplit_output_small").val(msg).scrollTop($("#sslsplit_output_small")[0].scrollHeight - $("#sslsplit_output_small").height());
		}
	});
}