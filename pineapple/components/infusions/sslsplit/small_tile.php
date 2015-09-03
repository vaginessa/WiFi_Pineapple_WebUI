<?php

global $directory, $rel_dir, $version, $name;
require($directory."includes/vars.php");

?>

<script type='text/javascript' src='/components/infusions/sslsplit/includes/js/infusion.js'></script>
<style>@import url('/components/infusions/sslsplit/includes/css/infusion.css')</style>

<script type="text/javascript">
	$(document).ready(function(){ sslsplit_init_small(); });
</script>

<div style='text-align:right'><a href="#" id="sslsplit_loading" class="refresh" onclick='javascript:sslsplit_refresh_tile();'></a></div>

<?php

if($installed)
{
	if ($is_sslsplit_running)
	{
		echo "sslsplit <span id=\"sslsplit_status_small\"><font color=\"lime\"><strong>&#10004;</strong></font></span>";
		echo " | <a id=\"sslsplit_link_small\" href=\"javascript:sslsplit_toggle_small('stop');\"><strong>Stop</strong></a> ";
	}
	else
	{ 
		echo "sslsplit <span id=\"sslsplit_status_small\"><font color=\"red\"><strong>&#10008;</strong></font></span>";
		echo " | <a id=\"sslsplit_link_small\" href=\"javascript:sslsplit_toggle_small('start');\"><strong>Start</strong></a> "; 
	}
	
	echo "<textarea readonly class='sslsplit' id='sslsplit_output_small' name='sslsplit_output_small'></textarea>";
}
else
{
	echo "All required dependencies have to be installed first. This may take a few minutes.<br /><br />";
		
	echo "Please wait, do not leave or refresh this page. Once the install is complete, this page will refresh automatically.<br /><br />";
		
	echo '[<a id="Install" href="javascript:sslsplit_install();">Install</a>]';
	
	echo '<script type="text/javascript">notify("sslsplit dependencies are not installed", "sslsplit", "red");</script>';
	
	exit();
}

?>