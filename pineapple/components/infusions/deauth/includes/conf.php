<?php

require("/pineapple/components/infusions/deauth/handler.php");

global $directory;

require($directory."includes/vars.php");

if (isset($_POST['set_conf']))
{	
	if($_POST['set_conf'] == "whitelist")
	{
		$filename = $whitelist_path;
		$newdata = $_POST['newdata'];
		file_put_contents($filename, str_replace("\r", "", $newdata));
	}
	
	if($_POST['set_conf'] == "blacklist")
	{
		$filename = $blacklist_path;
		$newdata = $_POST['newdata'];
		file_put_contents($filename, str_replace("\r", "", $newdata));
	}
	
	if($_POST['set_conf'] == "settings")
	{
		$filename = $settings_path;
		
		$new_packet = $_POST['packet'];
		$new_sleep = $_POST['sleep'];
		$new_channels = $_POST['channels'];
		$new_mode = $_POST['mode'];
		$new_method = $_POST['method'];

		$newdata = "packet=".$new_packet."\n"."sleep=".$new_sleep."\n"."interface=".$interface_conf."\n"."monitor=".$monitor_conf."\n"."channels=".$new_channels."\n"."mode=".$new_mode."\n"."method=".$new_method;
		file_put_contents($filename, str_replace("\r", "", $newdata));
	}
	
	echo '<font color="lime"><strong>saved</strong></font>';
}

if (isset($_GET['get_conf']))
{
	echo "<form id='deauth_form_conf'>";
	echo "<input class='deauth' type='hidden' name='set_conf' value='settings'/>";
	echo '<table id="deauth" class="grid">';
	echo "<tr><td>Method</td>";
	echo '<td><select class="deauth" id="method" name="method">';
	if($method_conf == "mdk3")
	{
		echo '<option selected value="mdk3">mdk3</option>'; 
		echo '<option value="aireplay-ng">aireplay-ng</option>'; 
	}
	else
	{
		echo '<option value="mdk3">mdk3</option>'; 
		echo '<option selected value="aireplay-ng">aireplay-ng</option>'; 
	}
	echo '</td></tr>';
	echo "<tr><td>&nbsp;</td>";
	echo "<tr><td><strong>Aireplay-ng</strong></td>";
	echo "<tr><td>Number of deauths to send</td>";
	echo '<td><input class="deauth" type="text" id="packet" name="packet" value="'.$packet_conf.'" size="5"> (Leave empty for default. 0 means send them continuously)</td></tr>';
	echo "<tr><td>Sleeping time in seconds</td>";
	echo '<td><input class="deauth" type="text" id="sleep" name="sleep" value="'.$sleep_conf.'" size="5"> (Leave empty for default)</td></tr>';
	echo "<tr><td>&nbsp;</td>";
	echo "<tr><td><strong>Mdk3</strong></td>";
	echo "<tr><td>Channels</td>";
	echo '<td><input class="deauth" type="text" id="channels" name="channels" value="'.$channels_conf.'" size="20"></td></tr>';
	echo "<tr><td>Mode</td>";
	echo '<td><select class="deauth" id="mode" name="mode">';
	if($mode_conf == "whitelist")
	{
		echo '<option selected value="whitelist">whitelist</option>'; 
		echo '<option value="blacklist">blacklist</option>'; 
	}
	else
	{
		echo '<option value="whitelist">whitelist</option>'; 
		echo '<option selected value="blacklist">blacklist</option>'; 
	}
	echo '</td></tr>';
	echo '</table>';
	echo "</form>";
}

?>