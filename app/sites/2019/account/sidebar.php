<!-- Sidebar -->
<div class="w3-sidebar w3-bar-block w3-collapse w3-card w3-animate-left" style="width:200px;" id="accSidebar">
	<button class="btn btn-light btn-block btn-lg w3-hide-large" onclick="$('#accSidebar').hide()"><?php echo L::account_sidebar_close;?> &times;</button>
	<div class="container">
    <div class="text-center">
			<?php
			$src=(file_exists('public/accounts/'.$account->pfp.'.jpg')) ? URL.'public/accounts/'.$account->pfp.'.jpg' : URL.'public/img/account.jpg';
			echo '<img src="'.$src.'" class="rounded-circle mr-1" style="height:80px;">';
			?>
			<br><?php echo L::account_sidebar_welcome;?>, <strong><?php echo $account->username; ?></strong><br><br>
    </div>
  </div>
	<a href="<?php echo URL; ?>account/contact" class="btn btn-link btn-block btn-lg text-body" id="contact"><?php echo L::account_sidebar_contact;?></a>
	<a href="<?php echo URL; ?>account/fursuit" class="btn btn-link btn-block btn-lg text-body" id="fursuit"><?php echo L::account_sidebar_fursuits;?></a>
</div>
