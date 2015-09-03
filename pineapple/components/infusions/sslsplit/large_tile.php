<?php

global $directory, $rel_dir, $version, $name;
require($directory."includes/vars.php");

?>

<script type='text/javascript' src='/components/infusions/sslsplit/includes/js/jquery.idTabs.min.js'></script>
<script type='text/javascript' src='/components/infusions/sslsplit/includes/js/infusion.js'></script>

<style>@import url('/components/infusions/sslsplit/includes/css/infusion.css')</style>

<script type="text/javascript">
	$(document).ready(function(){ sslsplit_init(); });
</script>

<div class=sidePanelLeft>
<div class=sidePanelTitle><?php echo $name; ?> - v<?php echo $version; ?>&nbsp;<span id="sslsplit" class="refresh_text"></span></div>
<div class=sidePanelContent>
<?php
if($installed)
{
	echo '<fieldset class="sslsplit">';
	echo '<legend class="sslsplit">Controls</legend>';
	
	if($is_sslsplit_running)
	{
		echo "sslsplit <span id=\"sslsplit_status\"><font color=\"lime\"><strong>&#10004;</strong></font></span>";
		echo " | <a id=\"sslsplit_link\" href=\"javascript:sslsplit_toggle('stop');\"><strong>Stop</strong></a><br />";
	}
	else
	{ 
		echo "sslsplit <span id=\"sslsplit_status\"><font color=\"red\"><strong>&#10008;</strong></font></span>";
		echo " | <a id=\"sslsplit_link\" href=\"javascript:sslsplit_toggle('start');\"><strong>Start</strong></a><br />"; 
	}
	
	echo '</fieldset><br/>';
	
	echo '<fieldset class="sslsplit">';
	echo '<legend class="sslsplit">Configuration</legend>';
	
	if($is_sslsplit_cert_generated)
	{
		echo "Certificate <span id=\"sslsplit_cert_status\"><font color=\"lime\"><strong>&#10004;</strong></font></span>";
		echo " | <a id=\"sslsplit_cert_link\" href=\"javascript:sslsplit_cert('remove');\"><strong>Delete</strong></a><br />";
	}
	else
	{ 
		echo "Certificate <span id=\"sslsplit_cert_status\"><font color=\"red\"><strong>&#10008;</strong></font></span>";
		echo " | <a id=\"sslsplit_cert_link\" href=\"javascript:sslsplit_cert('generate');\"><strong>Generate</strong></a><br />"; 
	}

	if($is_sslsplit_onboot)
	{
		echo "Autostart <span id=\"boot_status\"><font color=\"lime\"><strong>&#10004;</strong></font></span>";
		echo " | <a id=\"boot_link\" href=\"javascript:sslsplit_boot_toggle('disable');\"><strong>Disable</strong></a>";
	}
	else
	{ 
		echo "Autostart <span id=\"boot_status\"><font color=\"red\"><strong>&#10008;</strong></font></span>";
		echo " | <a id=\"boot_link\" href=\"javascript:sslsplit_boot_toggle('enable');\"><strong>Enable</strong></a>"; 
	}
	
	echo '</fieldset>';
}
else
{
	echo "All required dependencies have to be installed first. This may take a few minutes.<br /><br />";
		
	echo "Please wait, do not leave or refresh this page. Once the install is complete, this page will refresh automatically.<br /><br />";
		
	echo '[<a id="Install" href="javascript:sslsplit_install();">Install</a>]';
				
	exit();
}
?>
</div>
</div>

<div id="sslsplit" class="tab">
	<ul>
		<li><a id="Output_link" class="selected" href="#Output">Output</a></li>
		<li><a id="History_link" href="#History">History</a></li>
		<li><a id="Conf_link" href="#Conf">Configuration</a></li>
	</ul>

<div id="Output">
	[<a id="refresh" href="javascript:sslsplit_refresh();">Refresh</a>] [<a id="refresh" href="javascript:sslsplit_clear_log();">Clear connections log</a>]<br /><br />
	<textarea readonly class='sslsplit' id='sslsplit_output' name='output' cols='85' rows='29'></textarea>
</div>

<div id="History">
	[<a id="refresh" href="javascript:sslsplit_refresh_history();">Refresh</a>] [<a id="refresh" href="javascript:sslsplit_deleteall_history();">Delete All</a>]<br />
	<div id="sslsplit_content_history"></div>
</div>

<div id="Conf">
	<strong>iptables</strong> [<a href="javascript:sslsplit_update_conf($('#sslsplit_conf').val(), 'iptables');">Save</a>]<br /><br />
	<textarea class="sslsplit" id='sslsplit_conf' name='match' cols='85' rows='29'><?php echo file_get_contents($iptables_rules_path); ?></textarea>
</div>

</div>
<br />
Auto-refresh <select class="sslsplit" id="auto_time">
	<option value="1000">1 sec</option>
	<option value="5000">5 sec</option>
	<option value="10000">10 sec</option>
	<option value="15000">15 sec</option>
	<option value="20000">20 sec</option>
	<option value="25000">25 sec</option>
	<option value="30000">30 sec</option>
</select> <a id="sslsplit_auto_refresh" href="javascript:void(0);"><font color="red">Off</font></a>