<!-- Admin Sidebar -->
<div class="w3-sidebar w3-bar-block w3-collapse w3-card w3-animate-left" style="width:200px;" id="accSidebar">
	<button class="w3-bar-item w3-button w3-large w3-hide-large" onclick="side_close()">Close &times;</button>
	<?php if($account->status==3): ?>
	<a href="<?php echo URL; ?>account/event" class="w3-bar-item w3-button w3-sand" id="event">Events</a>
	<?php endif; ?>
</div>