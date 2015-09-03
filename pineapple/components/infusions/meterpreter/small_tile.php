<div style='text-align: right'><a href='#' class="refresh" onclick='refresh_small("meterpreter","user")'> </a></div>

<?php

touch("/etc/config/meterpreter");


$meterpreterrunning = exec("ps | grep [m]eterpreter");
if (empty($meterpreterrunning)) {
  echo "Meterpreter <font color='red'>disabled</font>. | <a href='#usr/meterpreter/action/startmeterpreter/refresh_meterpreter'>Start</a>";
} else {
  echo "Meterpreter <font color='lime'>enabled</font>.  | <a href='#usr/meterpreter/action/stopmeterpreter/refresh_meterpreter'>Stop</a>";
}


$autostartstatus=exec("grep meterpreter /etc/rc.local");
if (empty($autostartstatus)) { 
  echo "<br/>Autostart <font color='red'>disbaled</font>.   | <a href='#usr/meterpreter/action/enableautostart/refresh_meterpreter'>Enable</a>"; 
} else { 
  echo "<br/>Autostart <font color='lime'>enabled</font>.    | <a href='#usr/meterpreter/action/disableautostart/refresh_meterpreter'>Disable</a>"; }


$lhost = trim(exec("uci get meterpreter.host"));
$lport = trim(exec("uci get meterpreter.port"));

echo "<br/><br/>Host: $lhost";
echo "<br/>Port: $lport";

if (empty($lhost) || empty($lport)) { 
  echo "<br/><br/>Meterpreter <font color='red'>not configured</font>."; 
}


?>






<script type="text/javascript">
  function refresh_meterpreter(){
    refresh_small("meterpreter");
  }
</script>
