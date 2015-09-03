</br></br><div class="my_large_tile_content">

<?php

global $directory, $rel_dir, $version, $name;
require($directory."includes/vars.php");

?>

<script type='text/javascript' src='/components/infusions/deauth/includes/js/jquery.idTabs.min.js'></script>
<script type='text/javascript' src='/components/infusions/deauth/includes/js/infusion.js'></script>

<style>@import url('/components/infusions/deauth/includes/css/infusion.css')</style>

<script type="text/javascript">
	$(document).ready(function(){ deauth_init(); });
</script>

<div class=sidePanelLeft>
<div class=sidePanelTitle><?php echo $name; ?> - v<?php echo $version; ?>&nbsp;<span id="deauth" class="refresh_text"></span></div>
<div class=sidePanelContent>
<div id=sidePanelContent_int>
<?php
if($is_mdk3_installed)
{
	echo '<fieldset class="deauth">';
	echo '<legend class="deauth">Logical Interfaces</legend>';

	echo '<table class="interfaces">';

	for ($i=0;$i<count($wifi_interfaces);$i++)
	{
		$disabled = exec("ifconfig | grep ".$wifi_interfaces[$i]." | awk '{ print $1}'") == "" ? 1 : 0;
		$mode = exec("uci get wireless.@wifi-iface[".$i."].mode");
	
		echo '<tr>';
	
		echo '<td>'.$wifi_interfaces[$i].'</td>';
	
		echo '<td>';
		if(!$disabled)
			echo '<font color="lime"><strong>&#10004;</strong></font>';
		else
			echo '<font color="red"><strong>&#10008;</strong></font>';
		echo '</td>';
	
		echo '<td>';
		if(!$disabled)
			echo '<a id="disable" href="javascript:deauth_interface_toggle(\''.$wifi_interfaces[$i].'\',\'stop\');">[Disable]</a>';
		else
			echo '<a id="enable" href="javascript:deauth_interface_toggle(\''.$wifi_interfaces[$i].'\',\'start\');">[Enable]</a>';
		echo '</td>';
	
		echo '<td>';
			echo '<a id="enable" href="javascript:deauth_monitor_toggle(\''.$wifi_interfaces[$i].'\',\'\',\'start\');">[Start Monitor]</a>';
		echo '</td>';
	
		echo '<td>&nbsp;</td>';
	
		echo '</tr>';
	}

	echo "</table>";
	echo '</fieldset>';
	echo '<br />';
	echo '<fieldset class="deauth">';
	echo '<legend class="deauth">Monitor Interfaces</legend>';

	echo '<table class="interfaces">';

	for ($i=0;$i<count($monitor_interfaces);$i++)
	{
		if($monitor_interfaces[$i] != "")
		{
			$disabled = exec("ifconfig | grep ".$monitor_interfaces[$i]." | awk '{ print $1}'") == "" ? 1 : 0;
	
			echo '<tr>';
	
			echo '<td>'.$monitor_interfaces[$i].'</td>';
	
			echo '<td>';
			if(!$disabled)
				echo '<font color="lime"><strong>&#10004;</strong></font>';
			else
				echo '<font color="red"><strong>&#10008;</strong></font>';
			echo '</td>';
	
			echo '<td>';
			if(!$disabled)
				echo '<a id="disable" href="javascript:deauth_interface_toggle(\''.$monitor_interfaces[$i].'\',\'stop\');">[Disable]</a>';
			else
				echo '<a id="enable" href="javascript:deauth_interface_toggle(\''.$monitor_interfaces[$i].'\',\'start\');">[Enable]</a>';
			echo '</td>';
	
			echo '<td>';
				echo '<a id="disable" href="javascript:deauth_monitor_toggle(\'\',\''.$monitor_interfaces[$i].'\',\'stop\');">[Stop Monitor]</a>';
			echo '</td>';
	
			echo '<td></td>';
	
			echo '</tr>';
		}
	}

	echo "</table>";
	echo '</fieldset>';
	echo "</div><br/>";
	
	echo '<fieldset class="deauth">';
	echo '<legend class="deauth">Dependencies</legend>';
	
	echo "mdk3";
	echo "&nbsp;<font color=\"lime\"><strong>&#10004;</strong></font><br />";
	
	echo '</fieldset>';
	echo "<br/>";
	
	echo '<fieldset class="deauth">';
	echo '<legend class="deauth">Controls</legend>';

	if ($is_deauth_running)
	{
		echo "WiFi Deauth <span id=\"deauth_status\"><font color=\"lime\"><strong>&#10004;</strong></font></span>";
		echo " | <a id=\"deauth_link\" href=\"javascript:deauth_toggle('stop');\"><strong>Stop</strong></a> ";
	}
	else
	{ 
		echo "WiFi Deauth <span id=\"deauth_status\"><font color=\"red\"><strong>&#10008;</strong></font></span>";
		echo " | <a id=\"deauth_link\" href=\"javascript:deauth_toggle('start');\"><strong>Start</strong></a> "; 
	}
	
	echo "<span id=\"interfaces_l\">";
	echo '<select class="deauth" id="interfaces_list" name="interfaces_list">';
	for ($i=0;$i<count($wifi_interfaces);$i++)
	{ 
		if($interface_conf == $wifi_interfaces[$i])
			echo '<option selected value="'.$wifi_interfaces[$i].'">'.$wifi_interfaces[$i].'</option>'; 
		else
			echo '<option value="'.$wifi_interfaces[$i].'">'.$wifi_interfaces[$i].'</option>'; 
	}
	echo '</select>';
	echo "</span>";
	echo "<span id=\"monitorInterface_l\">";
	echo '<select class="deauth" id="monitorInterfaces_list" name="monitorInterfaces_list">';
	echo '<option value="--">--</option>';
	foreach($monitor_interfaces as $value)
	{
		if($value != "")
		{
			if($monitor_conf != "" && $monitor_conf == $value)
				echo '<option selected value="'.$value.'">'.$value.'</option>';
			else
				echo '<option value="'.$value.'">'.$value.'</option>';
		}
	}
	echo '</select>';
	echo "</span><br /><br />";

	if ($is_deauth_onboot) 
	{
		echo "Autostart <span id=\"boot_status\"><font color=\"lime\"><strong>&#10004;</strong></font></span>";
		echo " | <a id=\"boot_link\" href=\"javascript:deauth_boot_toggle('disable');\"><strong>Disable</strong></a><br />";
	}
	else 
	{ 
		echo "Autostart <span id=\"boot_status\"><font color=\"red\"><strong>&#10008;</strong></font></span>";
		echo " | <a id=\"boot_link\" href=\"javascript:deauth_boot_toggle('enable');\"><strong>Enable</strong></a><br />"; 
	}
	
	echo '</fieldset>';
}
else
{
	echo "mdk3";
	echo "&nbsp;<font color=\"red\"><strong>&#10008;</strong></font><br /><br />";
	
	echo "Install to <a id=\"install_int\" href=\"javascript:deauth_install('internal');\">Internal Storage</a> or <a id=\"install_sd\" href=\"javascript:deauth_install('sd');\">SD Storage</a>";
		
	exit();
}
?>
</div>
</div>

<div id="deauth" class="tab">
	<ul>
		<li><a id="Output_link" class="selected" href="#Output">Output</a></li>
		<li><a id="Whitelist_link" href="#Whitelist">Whitelist</a></li>
		<li><a id="Blacklist_link" href="#Blacklist">Blacklist</a></li>
		<li><a id="Configuration_link" href="#Conf">Configuration</a></li>
	</ul>

<div id="Output">
	[<a id="refresh" href="javascript:deauth_refresh();">Refresh</a>]<br /><br />
	<textarea readonly class="deauth" id='deauth_output' name='deauth_output' cols='85' rows='29'></textarea>
</div>

<div id="Whitelist">
	[<a href="javascript:deauth_update_conf($('#whitelist').val(), 'whitelist');">Save</a>] [<a href="javascript:$('#whitelist').val(''); void(0);">Clear</a>] [<a id="show_ap" href="javascript:deauth_show_ap('whitelist');">Available AP</a>] [<a id="show_ap" href="javascript:show_help('deauth','whitelist');">Help</a>] <br /><br />
	<textarea class="deauth" id='whitelist' name='whitelist' cols='85' rows='29'><?php echo file_get_contents($whitelist_path); ?></textarea>
</div>

<div id="Blacklist">	
	[<a href="javascript:deauth_update_conf($('#blacklist').val(), 'blacklist');">Save</a>] [<a href="javascript:$('#blacklist').val(''); void(0);">Clear</a>] [<a id="show_ap" href="javascript:deauth_show_ap('blacklist');">Available AP</a>] [<a id="show_ap" href="javascript:show_help('deauth','blacklist');">Help</a>] <br /><br />
    <textarea class="deauth" id='blacklist' name='blacklist' cols='85' rows='29'><?php echo file_get_contents($blacklist_path); ?></textarea>
</div>

<div id="Conf">
	[<a id="config" href="javascript:deauth_set_config();">Save</a>]<br />
	<div id="deauth_content_conf"></div>
</div>

</div>
<br />
Auto-refresh <select class="deauth" id="auto_time">
	<option value="1000">1 sec</option>
	<option value="5000">5 sec</option>
	<option value="10000">10 sec</option>
	<option value="15000">15 sec</option>
	<option value="20000">20 sec</option>
	<option value="25000">25 sec</option>
	<option value="30000">30 sec</option>
</select> <a id="deauth_auto_refresh" href="javascript:void(0);"><font color="red">Off</font></a>

</div>