<?php

require("/pineapple/components/infusions/sslsplit/handler.php");
require("/pineapple/components/infusions/sslsplit/functions.php");

global $directory, $rel_dir;

require($directory."includes/vars.php");

if (isset($_GET['history']))
{
	$log_list = array_reverse(glob($directory."includes/log/*"));

	if(count($log_list) == 0)
		echo "<em>No log history...</em>";
	
	for($i=0;$i<count($log_list);$i++)
	{
		$info = explode("_", basename($log_list[$i]));
		echo gmdate('Y-m-d H-i-s', $info[1])." [";
		echo "<a href=\"javascript:sslsplit_load_file('".basename($log_list[$i])."');\">view</a> | ";
		echo "<a href=\"/components/infusions/sslsplit/includes/actions.php?_csrfToken=".$_SESSION['_csrfToken']."&download&file=".basename($log_list[$i])."\">download</a> | ";
		echo "<a href=\"javascript:sslsplit_delete_file('log','".basename($log_list[$i])."');\">delete</a>]<br />";
	}
}

if (isset($_GET['lastlog']))
{
	if ($is_sslsplit_running)
	{
		if(file_exists($directory."includes/connections.log")) 
		{
			$log_date = gmdate("F d Y H:i:s", filemtime($directory."includes/connections.log"));
			echo "sslsplit log connections [".$log_date."]\n";
			
			echo file_get_contents($directory."includes/connections.log");
		}
		else
		{
			echo "No connections log...";
		}
	}
	else
	{
		echo "sslsplit is not running...";
	}
}

?>