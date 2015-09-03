<?php

require("/pineapple/components/infusions/deauth/handler.php");

global $directory;

require($directory."includes/vars.php");
require($directory."includes/iwlist_parser.php");

?>

<script type='text/javascript' src='/components/infusions/deauth/includes/js/jquery.idTabs.min.js'></script>
<script type='text/javascript' src='/components/infusions/deauth/includes/js/infusion.js'></script>
<style>@import url('/components/infusions/deauth/includes/css/infusion.css')</style>

<script type="text/javascript">
	$(document).ready(function(){ 
		refresh_available_ap();
	});
	
	function refresh_available_ap() {
		$.ajax({
			type: "GET",
			data: "available_ap&int="+$("#interfaces").val(),
			url: "/components/infusions/deauth/includes/interfaces.php",
			beforeSend: deauth_myajaxStart(),
			success: function(msg){
				$("#list_ap").html(msg);
				deauth_myajaxStop('');
				$('#deauth-survey-grid tr').click(function() { 
					var append_value = '# ' + $(this).attr("name") + '\n' + $(this).attr("address");
					deauth_append(append_value,"<?php echo $_GET['w']?>");
					//close_popup();
					return false;
				});
			}
		});
	}
</script>
	
<?php

echo '<select class="deauth" id="interfaces" name="interfaces">';
foreach($wifi_interfaces as $value) { echo '<option value="'.$value.'">'.$value.'</option>'; }
echo '</select>&nbsp;';
echo '[<a id="refresh" href="javascript:refresh_available_ap();">Refresh</a>] <span id="deauth" class="refresh_text"></span><br/><br/>';

echo '<em>Click on row to add AP to field</em><br/><br/>';

echo '<div id="list_ap"></div>';
	
?>