<?php

require("/pineapple/components/infusions/sslsplit/handler.php");

global $directory;

require($directory."includes/vars.php");

if (isset($_POST['set_conf']))
{
	if($_POST['set_conf'] == "iptables")
	{
		$filename = $iptables_rules_path;
		$newdata = $_POST['newdata'];

		$newdata = ereg_replace(13,  "", $newdata);
		$fw = fopen($filename, 'w');
		$fb = fwrite($fw,stripslashes($newdata));
		fclose($fw);
	}
	
	echo '<font color="lime"><strong>updated</strong></font>';
}

?>