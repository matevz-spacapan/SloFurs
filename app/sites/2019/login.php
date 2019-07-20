<div class="w3-container" style="margin-top:20px">
	<div class="w3-half">
		<div class="w3-container w3-blue w3-center">
			<h3><?php echo L::login_h;?></h3>
		</div>
		<form action="<?php echo URL; ?>login" method="post">
			<label><?php echo L::login_email;?></label>
			<input class="w3-input" type="email" name="email" placeholder="<?php echo L::login_emailP;?>" required autofocus>
			<label><?php echo L::login_pw;?></label>
			<input class="w3-input" type="password" name="password" placeholder="<?php echo L::login_pwP;?>" required><br>
			<button type="submit" name="log_in_acc" class="w3-button w3-round w3-border w3-border-blue"><?php echo L::login_button;?></button><p>
			<div>
				<?php echo L::login_noAcc;?> <a href="<?php echo URL;?>signup"><?php echo L::login_noAccButton;?></a><br>
				<?php echo L::login_forgot;?> <a href="<?php echo URL;?>login/forgot"><?php echo L::login_forgotButton;?></a><br>
				Didn't receive the account confirmation email? <a href="<?php echo URL;?>signup/resend">Request a resend</a>
			</div>
		</form>
	</div>
	<div class="w3-half w3-center">
		<div class="w3-container">
			<h3><?php echo L::login_welcome;?></h3>
		</div>
		<div class="w3-container">
			<?php echo L::login_desc;?>
		</div>
	</div>
</div>
