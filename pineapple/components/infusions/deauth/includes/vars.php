<?php

putenv('LD_LIBRARY_PATH='.getenv('LD_LIBRARY_PATH').':/sd/lib:/sd/usr/lib');   
putenv('PATH='.getenv('PATH').':/sd/usr/bin:/sd/usr/sbin');

global $directory, $rel_dir;

$wifi_interfaces = array_reverse(explode("\n", trim(shell_exec("iwconfig 2> /dev/null | grep \"wlan*\" | grep -v \"mon*\" | awk '{print $1}'"))));
$monitor_interfaces = array_reverse(explode("\n", trim(shell_exec("iwconfig 2> /dev/null | grep \"mon*\" | awk '{print $1}'"))));

$whitelist_path = $directory."includes/rules/whitelist.lst";
$blacklist_path = $directory."includes/rules/blacklist.lst";
$settings_path = $directory."includes/infusion.conf";

$is_deauth_running = exec("ps auxww | grep deauth | grep -v -e grep | grep -v -e php") != "" ? 1 : 0;
$is_deauth_onboot = exec("cat /etc/rc.local | grep deauth.sh") != "" ? 1 : 0;

$is_mdk3_installed = exec("which mdk3") != "" ? 1 : 0;

$deauth_conf = parse_ini_file($settings_path);
$packet_conf = $deauth_conf['packet'];
$sleep_conf = $deauth_conf['sleep'];
$interface_conf = $deauth_conf['interface'];
$monitor_conf = $deauth_conf['monitor'];
$mode_conf = $deauth_conf['mode'];
$channels_conf = $deauth_conf['channels'];
$method_conf = $deauth_conf['method'];

?>