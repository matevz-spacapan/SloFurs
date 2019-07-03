<div class="w3-container" style="margin-top:20px">
	<div class="w3-half">
		<div class="w3-container w3-blue w3-center">
			<h3>Log in to your account</h3>
		</div>
		<form action="<?php echo URL; ?>login/loginacc" method="post">
			<label>E-mail</label>
			<input class="w3-input" type="email" name="email" placeholder="E-mail Address" required autofocus>
			<label>Password</label>
			<input class="w3-input" type="password" name="password" placeholder="Account Password" required>
			<button type="submit" name="log_in_acc" class="w3-button w3-round w3-border w3-border-blue">Log In</button>
			<div class="w3-container">
				Don't have an account?
				<a href="signup">Sign Up</a>
			</div>
		</form>
	</div>
	<div class="w3-half w3-center">
		<div class="w3-container">
			<h3>Welcome back!</h3>
		</div>
		<div class="w3-container">
			To use all the website features, such as registering for an event you'll need to be logged in.
		</div>
	</div>
</div>