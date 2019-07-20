<div class="w3-container" style="margin-top:20px">
	<div class="w3-half">
		<div class="w3-container w3-blue w3-center">
			<h3><?php echo L::signup_resend_h;?></h3>
		</div>
		<form action="<?php echo URL; ?>signup/resend" method="post">
			<label><?php echo L::signup_email;?></label>
			<input class="w3-input" type="email" name="email" placeholder="<?php echo L::signup_emailP;?>" required autofocus><br>
			<div class="w3-center">
				<button type="submit" id="btn" name="send_email" class="w3-button w3-round w3-border w3-border-blue"><?php echo L::signup_resend_submit;?></button><p>
			</div>
		</form>
	</div>
	<div class="w3-half w3-center">
		<div class="w3-container">
			<h3><?php echo L::signup_welcome;?></h3>
		</div>
		<div class="w3-container">
			<?php echo L::signup_resend_desc1;?><br>
			<?php echo L::signup_resend_desc2;?>
			<h3 class="w3-text-red"><?php echo L::signup_desc4;?></h3>
		</div>
	</div>
</div>
