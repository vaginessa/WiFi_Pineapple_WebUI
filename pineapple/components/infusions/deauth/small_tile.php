<?php

global $directory, $rel_dir, $version, $name;
require($directory."includes/vars.php");

?>

<script type='text/javascript' src='/components/infusions/deauth/includes/js/infusion.js'></script>
<style>@import url('/components/infusions/deauth/includes/css/infusion.css')</style>

<script type="text/javascript">
	$(document).ready(function(){ deauth_init_small(); });
</script>

<div style='text-align:right'><a href="#" id="deauth_loading" class="refresh" onclick='javascript:deauth_refresh_tile();'></a></div>

<?php

if($is_mdk3_installed)
{
	echo "WiFi Deauth ";

	if ($is_deauth_running)
	{
		echo "<span id=\"deauth_status_small\"><font color=\"lime\"><strong>&#10004;</strong></font></span>";
		echo " | <a id=\"deauth_link_small\" href=\"javascript:deauth_toggle_small('stop');\"><strong>Stop</strong></a>&nbsp;";
	}
	else 
	{ 
		echo "<span id=\"deauth_status_small\"><font color=\"red\"><strong>&#10008;</strong></font></span>";
		echo " | <a id=\"deauth_link_small\" href=\"javascript:deauth_toggle_small('start');\"><strong>Start</strong></a>&nbsp;"; 
	}
	
	echo '<select class="deauth" id="deauth_interfaces_small" name="deauth_interfaces_small">';
	for ($i=0;$i<count($wifi_interfaces);$i++)
	{
		if($interface_conf == $wifi_interfaces[$i])
			echo '<option selected value="'.$wifi_interfaces[$i].'">'.$wifi_interfaces[$i].'</option>'; 
		else
			echo '<option value="'.$wifi_interfaces[$i].'">'.$wifi_interfaces[$i].'</option>'; 
	}
	echo '</select>';

	echo '<select class="deauth" id="deauth_monitorInterfaces_small" name="deauth_interfaces_small">';
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
	echo '</select><br/><br/>';

	echo "<textarea readonly class='deauth' id='deauth_output_small' name='deauth_output_small'></textarea>";
}
else
{
	echo "mdk3";
	echo "&nbsp;<font color=\"red\"><strong>&#10008;</strong></font><br /><br />";
	
	echo "Install to <a id=\"install_int\" href=\"javascript:deauth_install('internal');\">Internal Storage</a> or <a id=\"install_sd\" href=\"javascript:deauth_install('sd');\">SD Storage</a>";
	
	echo '<script type="text/javascript">notify("mdk3 is not installed", "deauth", "red");</script>';
		
	exit();	
}

?>