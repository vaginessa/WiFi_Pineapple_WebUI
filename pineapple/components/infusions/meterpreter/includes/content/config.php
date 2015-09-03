<?php 
include_once('/pineapple/includes/api/tile_functions.php'); 
touch ("/etc/config/meterpreter");
$lport = exec("uci get meterpreter.port");
$lhost = exec("uci get meterpreter.host");
?>


<fieldset>
  <legend>Meterpreter</legend>
  <form method="POST" action="/components/infusions/meterpreter/functions.php?action=updatemeterpreter" id="updatemeterpreter" onSubmit="$(this).AJAXifyForm(notify); refresh_meterpreter(); return false;">
    <input type="text" name="lhost" value="<?=$lhost?>"> Host<br/>
    <input type="text" name="lport" value="<?=$lport?>"> Port<br/><br/>
    <input type='submit' name='submit' value='Update Meterpreter Config'>
  </form>
</fieldset>


