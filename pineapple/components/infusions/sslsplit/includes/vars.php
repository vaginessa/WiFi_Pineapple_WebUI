<?php

putenv('LD_LIBRARY_PATH='.getenv('LD_LIBRARY_PATH').':/sd/lib:/sd/usr/lib');   
putenv('PATH='.getenv('PATH').':/sd/usr/bin:/sd/usr/sbin');

global $directory, $rel_dir;

$iptables_rules_path = $directory."includes/rules/iptables";

$installed = file_exists($directory."includes/installed") ? 1 : 0;

$is_sslsplit_running = exec("ps auxww | grep sslsplit | grep -v -e grep | grep -v -e php") != "" ? 1 : 0;
$is_sslsplit_onboot = exec("cat /etc/rc.local | grep sslsplit/includes/autostart.sh") != "" ? 1 : 0;

$is_sslsplit_cert_generated = file_exists($directory."includes/cert/certificate.crt") ? 1 : 0;

$is_executable = exec("if [ -x ".$directory."includes/dep/sslsplit ]; then echo '1'; fi") != "" ? 1 : 0;
if(!$is_executable) exec("chmod +x ".$directory."includes/dep/sslsplit");

?>