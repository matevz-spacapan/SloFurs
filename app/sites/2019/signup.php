<div class="w3-container" style="margin-top:20px">
	<div class="w3-half">
		<div class="w3-container w3-blue w3-center">
			<h3>Create an account</h3>
		</div>
		<form action="<?php echo URL; ?>signup/signupacc" method="post">
			<label>E-mail</label>
			<input class="w3-input" type="email" name="email" placeholder="E-mail address" required autofocus>
			<label>Username</label> <i class="w3-opacity w3-small">(what others will be able to see)</i>
			<input class="w3-input" type="text" name="username" placeholder="Your desired username" required>
			<label>Password</label>
			<input class="w3-input" id="pwd" type="password" name="password" placeholder="Create a password" pattern="^(?=.{8,}$)(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\W_]).*$" title="At least 8 characters, number and special character" required onkeyup="verifyPassword()">
			<label>Password</label> <i class="w3-opacity w3-small">(confirm)</i> <i id="correct" class="far fa-times"></i>
			<input class="w3-input" id="pwdC" type="password" placeholder="Confirm your password" required onkeyup="verifyPassword()">
			<button type="submit" id="btn" name="sign_up_acc" class="w3-button w3-round w3-border w3-border-blue" disabled="true">Register</button>
			<div class="w3-container">
				Already have an account?
				<a href="login">Log in</a>
			</div>
		</form>
	</div>
	<div class="w3-half w3-center">
		<div class="w3-container">
			<h3>Welcome to Slofurs!</h3>
		</div>
		<div class="w3-container">
			If you wish to use all site features you'll neeed to create an account.<br/>
			Once registered you'll be able to add a bit more information about you, choose a profile picture, register for events and more.
		</div>
	</div>
</div>
<script>
function verifyPassword(){
	regex=/^(?=.{8,}$)(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\W_]).*$/;
	if($("#pwd").val()==$("#pwdC").val()&&$("#pwd").val().length>=8&&$("#pwd").val().match(regex)){
		$("#btn").prop("disabled", false);
		if($("#correct").hasClass("fa-times")){
			$("#correct").removeClass("fa-times").addClass("fa-check");
		}
	}
	else{
		$("#btn").prop("disabled", true);
		if($("#correct").hasClass("fa-check")){
			$("#correct").removeClass("fa-check").addClass("fa-times");
		}
	}
}
</script>