<!-- Admin Sidebar -->
<div class="w3-sidebar w3-bar-block w3-collapse w3-card w3-animate-left" style="width:200px;" id="accSidebar">
	<button class="w3-bar-item w3-button w3-large w3-hide-large" onclick="$('#accSidebar').hide()"><?php echo L::admin_close;?> &times;</button>
	<?php if($account->status==ADMIN): ?>
	<a href="<?php echo URL; ?>admin/event" class="w3-bar-item w3-button" id="event"><?php echo L::admin_events;?></a>
	<a href="<?php echo URL; ?>admin/event/new" class="w3-bar-item w3-button" id="newevent" style="padding-left: 35px;"><?php echo L::admin_newEvt;?></a>
	<?php endif; ?>
</div>
