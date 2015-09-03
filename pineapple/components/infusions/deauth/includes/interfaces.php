<?php

require("/pineapple/components/infusions/deauth/handler.php");

global $directory;

require($directory."includes/vars.php");
require($directory."includes/iwlist_parser.php");

if(isset($_GET['interface']))
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
}

if(isset($_GET['monitor_l']))
{
	echo '<select class="deauth" id="monitorInterfaces_list" name="monitorInterfaces_list">';
	echo '<option value="--">--</option>';
	foreach($monitor_interfaces as $value) { if($value != "") echo '<option value="'.$value.'">'.$value.'</option>'; }
	echo '</select>';
}

if(isset($_GET['interface_l']))
{
	echo '<select class="deauth" id="interfaces_list" name="interfaces_list">';
	foreach($wifi_interfaces as $value) { echo '<option value="'.$value.'">'.$value.'</option>'; }
	echo '</select>';
}

if(isset($_GET['available_ap']))
{
	if (isset($_GET['int'])) $interface = $_GET['int'];
	
	// List APs
	$iwlistparse = new iwlist_parser();
	$p = $iwlistparse->parseScanDev($interface);

	if(!empty($p))
	{
		echo '<table id="deauth-survey-grid" class="grid" cellspacing="0">';
		echo '<tr class="header">';
		echo '<td>SSID</td>';
		echo '<td>BSSID</td>';
		echo '<td>Signal level</td>';
		echo '<td colspan="2">Quality level</td>';
		echo '<td>Ch</td>';
		echo '<td>Encryption</td>';
		echo '<td>Cipher</td>';
		echo '<td>Auth</td>';
		echo '</tr>';
	}
	else
	{
		echo "<em>No data...</em>";
	}

	for($i=1;$i<=count($p[$interface]);$i++)
	{
		$quality = $p[$interface][$i]["Quality"];

		if($quality <= 25) $graph = "red";
		else if($quality <= 50) $graph = "yellow";
		else if($quality <= 100) $graph = "green";

		echo '<tr class="odd" name="'.$p[$interface][$i]["ESSID"].'" address="'.$p[$interface][$i]["Address"].'">';

		echo '<td>'.$p[$interface][$i]["ESSID"].'</td>';

		$MAC_address = explode(":", $p[$interface][$i]["Address"]);
		echo '<td>'.$p[$interface][$i]["Address"].'</td>';
		echo '<td>'.$p[$interface][$i]["Signal level"].'</td>';
		echo "<td>".$quality."%</td>";
		echo "<td width='150'>";
		echo '<div class="graph-border">';
		echo '<div class="graph-bar" style="width: '.$quality.'%; background: '.$graph.';"></div>';
		echo '</div>';
		echo "</td>";
		echo '<td>'.$p[$interface][$i]["Channel"].'</td>';

		if($p[$interface][$i]["Encryption key"] == "on")
		{
			$WPA = strstr($p[$interface][$i]["IE"], "WPA Version 1");
			$WPA2 = strstr($p[$interface][$i]["IE"], "802.11i/WPA2 Version 1");

			$auth_type = str_replace("\n"," ",$p[$interface][$i]["Authentication Suites (1)"]);
			$auth_type = implode(' ',array_unique(explode(' ', $auth_type)));

			$cipher = $p[$interface][$i]["Pairwise Ciphers (2)"] ? $p[$interface][$i]["Pairwise Ciphers (2)"] : $p[$interface][$i]["Pairwise Ciphers (1)"];
			$cipher = str_replace("\n"," ",$cipher);
			$cipher = implode(',',array_unique(explode(' ', $cipher)));

			if($WPA2 != "" && $WPA != "")
				echo '<td>WPA,WPA2</td>';
			else if($WPA2 != "")
				echo '<td>WPA2</td>';
			else if($WPA != "")
				echo '<td>WPA</td>';
			else
				echo '<td>WEP</td>';

			echo '<td>'.$cipher.'</td>';
			echo '<td>'.$auth_type.'</td>';
		}
		else
		{
			echo '<td>None</td>';
			echo '<td>&nbsp;</td>';
			echo '<td>&nbsp;</td>';
		}

		echo '</tr>';
	}
}

?>
