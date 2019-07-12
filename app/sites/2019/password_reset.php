<div class="w3-container" style="margin-top:20px">
	<div class="w3-half">
		<div class="w3-container w3-blue w3-center">
			<h3>Reset your password</h3>
		</div>
		<form action="<?php echo URL; ?>login/forgot" method="post">
			<label>E-mail</label>
			<input class="w3-input" type="email" name="email" placeholder="E-mail Address" required autofocus><br>
			<div class="w3-center">
				<button type="submit" name="password_reset" class="w3-button w3-round w3-border w3-blue">Reset password</button>
			</div>
		</form>
	</div>
	<div class="w3-half w3-center">
		<div class="w3-container">
			<h3>Welcome back!</h3>
		</div>
		<div class="w3-container">
			If you forgot your password, you can reset it. We'll send you an email with a link so you can choose a new password.
		</div>
	</div>
</div>
