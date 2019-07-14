<!-- Sidebar -->
<div class="w3-sidebar w3-bar-block w3-collapse w3-card w3-animate-left" style="width:200px;" id="accSidebar">
	<button class="w3-bar-item w3-button w3-large w3-hide-large" onclick="$('#accSidebar').hide()"><?php echo L::account_sidebar_close;?> &times;</button>
	<a href="<?php echo URL; ?>account/contact" class="w3-bar-item w3-button" id="contact"><?php echo L::account_sidebar_contact;?></a>
	<a href="<?php echo URL; ?>account/password" class="w3-bar-item w3-button" id="password"><?php echo L::account_sidebar_pw;?></a>
	<a href="<?php echo URL; ?>account/fursuit" class="w3-bar-item w3-button" id="fursuit"><?php echo L::account_sidebar_fursuits;?></a>
</div>
