<?php



if(isset($_GET['action'])){
  if($_GET['action'] == "startmeterpreter"){
    echo start_meterpreter();
  }
  elseif($_GET['action'] == "stopmeterpreter"){
    echo stop_meterpreter();
  }
  elseif($_GET['action'] == "updatemeterpreter"){
    echo update_meterpreter();
  }
  elseif($_GET['action'] == "enableautostart"){
    echo enable_autostart();
  }
  elseif($_GET['action'] == "disableautostart"){
    echo disable_autostart();
  }

}

function update_meterpreter(){
  if(!isset($_POST['lhost']) || !isset($_POST['lport'])){
    return "<font color='red'>Host or Port Missing. Cannot Update Meterpreter Configuration</font>";
  } else {
    exec("uci set meterpreter.host={$_POST['lhost']}");
    exec("uci set meterpreter.port={$_POST['lport']}");
    exec("uci commit meterpreter");
    return "<font color='lime'>Meterpreter Configuration Updated</font>";
  }
}

function start_meterpreter(){
  $lhost = exec("uci get meterpreter.host");
  $lport = exec("uci get meterpreter.port");
  if(!isset($lhost) || !isset($lport)){
    return "<font color='red'>Host or Port Missing. Cannot Start Meterpreter. Please Update Configuration Before Attempting To Start.</font>";
  } else {
    exec("echo 'pineapple infusion meterpreter' | at now");
    return "<font color='lime'>Meterpreter Started</font>";
  }
}

function stop_meterpreter(){
  touch("/tmp/killmeterpreter");
  exec("kill `ps | grep [m]eterpreter | awk {print'$1'} | tail -n1`");
  return "<font color='lime'>Meterpreter Stopped.</font>";
}

function enable_autostart(){
  exec("sed -i '/meterpreter/d' /etc/rc.local");
  exec("sed -i '/exit 0/d' /etc/rc.local");
  exec("echo 'pineapple infusion meterpreter &' >> /etc/rc.local");
  exec("echo exit 0 >> /etc/rc.local");
  return "<font color='lime'>Metasploit Auto Start Enabled.</font>";
}

function disable_autostart(){
  exec("sed -i '/meterpreter/d' /etc/rc.local");
  return "<font color='red'>Metasploit Auto Start Disabled.</font>";
}

?>
