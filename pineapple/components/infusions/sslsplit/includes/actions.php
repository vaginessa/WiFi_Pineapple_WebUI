<?php

require("/pineapple/components/infusions/sslsplit/handler.php");

global $directory;

require($directory."includes/vars.php");

if (isset($_GET['sslsplit']))
{
	if (isset($_GET['start']))
	{
		exec("echo ".$directory."includes/sslsplit_start.sh | at now");
	}

	if (isset($_GET['stop']))
	{
		exec("echo ".$directory."includes/sslsplit_stop.sh | at now");
	}
}

if (isset($_GET['cert']))
{
	if (isset($_GET['remove']))
	{
		exec("rm -rf ".$directory."includes/cert/certificate.*");
	}

	if (isset($_GET['generate']))
	{
		exec("echo ".$directory."includes/sslsplit_generate.sh | at now");
	}
}

if (isset($_GET['load']))
{
	if (isset($_GET['file']))
	{
		$log_date = gmdate("F d Y H:i:s", filemtime($directory."includes/log/".$_GET['file']));
		echo "<strong>sslsplit log ".$_GET['file']." [".$log_date."]</strong><br/><br/>";
		
		echo '<textarea class="sslsplit" cols="85" rows="29">';
		echo file_get_contents($directory."includes/log/".$_GET['file']);
		echo '</textarea>';
	}
}

if (isset($_GET['download']))
{
	if (isset($_GET['file']))
	{
		$file = $directory."includes/log/".$_GET['file'];
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename="'.basename($file).'"'); 
		header('Content-Length: ' . filesize($file));
		readfile($file);
	}
}

if (isset($_GET['delete']))
{
	if (isset($_GET['file']))
	{
		if (isset($_GET['log']))
			exec("rm -rf ".$directory."includes/log/".$_GET['file']);
	}
}

if (isset($_GET['deleteall']))
{
	exec("rm -rf ".$directory."includes/log/*");
	
	echo '<font color="lime"><strong>done</strong></font>';
}

if (isset($_GET['clearlog']))
{
	exec("touch ".$directory."includes/connections.log");
	
	echo '<font color="lime"><strong>done</strong></font>';
}

if (isset($_GET['install_dep']))
{
	exec("echo \"<?php echo 'working'; ?>\" > ".$directory."includes/status.php");
	exec("echo \"sh ".$directory."includes/install.sh\" | at now");
}

if (isset($_GET['boot']))
{
	if (isset($_GET['action']))
	{
		$action = $_GET['action'];
		
		switch($action)
		{
			case 'enable':
				exec("sed -i '/exit 0/d' /etc/rc.local"); 
				exec("echo ".$directory."includes/autostart.sh >> /etc/rc.local");
				exec("echo exit 0 >> /etc/rc.local");
			break;
			
			case 'disable': 
				exec("sed -i '/sslsplit\/includes\/autostart.sh/d' /etc/rc.local");
			break;
		}
	}	
}

?>