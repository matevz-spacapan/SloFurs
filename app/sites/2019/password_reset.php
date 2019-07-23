<div class="w3-container" style="margin-top:20px">
	<div class="w3-half">
		<div class="w3-container w3-blue w3-center">
			<h3><?php echo L::reset_h;?></h3>
		</div>
		<form action="<?php echo URL; ?>login/forgot" method="post">
			<label><?php echo L::reset_email;?></label>
			<input class="w3-input" type="email" name="email" placeholder="<?php echo L::reset_emailP;?>" required autofocus><br>
			<div class="w3-center">
				<button type="submit" name="password_reset" class="w3-button w3-round w3-border w3-border-blue"><?php echo L::reset_button;?></button>
			</div>
		</form>
	</div>
	<div class="w3-half w3-center">
		<div class="w3-container">
			<h3><?php echo L::reset_welcome;?></h3>
		</div>
		<div class="w3-container">
			<?php echo L::reset_desc;?>
			<h5><b><?php echo L::signup_desc4;?></b></h5>
		</div>
	</div>
</div>
