<div class="w3-main" style="margin-left:200px">
<div class="w3-orange">
	<button class="w3-button w3-orange w3-xlarge w3-hide-large" onclick="$('#accSidebar').show();">&#9776;</button>
	<div class="w3-container">
		<h1><?php echo L::admin_newEvt;?></h1>
	</div>
</div>
<div class="w3-container">
	<?php $editEvent=false; require 'app/sites/'.THEME.'/admin/form.php'; ?>
</div>
