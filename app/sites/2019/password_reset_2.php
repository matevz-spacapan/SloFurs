<div class="w3-container" style="margin-top:20px">
	<div class="w3-half">
		<div class="w3-container w3-blue w3-center">
			<h3>Password Reset</h3>
		</div>
		<form action="<?php echo URL; ?>login/forgot" method="post">
			<label>E-mail</label>
			<input class="w3-input" type="email" name="email" placeholder="E-mail Address" value="<?php echo $email;?>" disabled>
			<?php $_SESSION['reset_email']=strip_tags($email);?>
			<label>Password</label>
			<input class="w3-input" id="pwd" type="password" name="password" placeholder="Create a password" pattern="^(?=.{8,}$)(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\W_]).*$" title="At least 8 characters, number and special character" required onkeyup="verifyPassword()">
			<label>Password</label> <i class="w3-opacity w3-small">(confirm)</i> <i id="correct" class="far fa-times"></i>
			<input class="w3-input" id="pwdC" type="password" placeholder="Confirm your password" required onkeyup="verifyPassword()"><br>
			<div class="w3-center">
				<button type="submit" id="btn" name="finish_reset" class="w3-button w3-round w3-border w3-green" disabled>Save</button>
			</div>
		</form>
	</div>
	<div class="w3-half w3-center">
		<div class="w3-container">
			<h3>Select a new password</h3>
		</div>
		<div class="w3-container">
			To finish resetting your password, please fill out the form.
		</div>
		<div class="w3-container">
			<b class="w3-left-align">Your new password needs to be at least 8 characters long and meet all of the following:
				<ul>
					<li>contain at least 1 letter</li>
					<li>contain at least 1 number</li>
					<li>contain at least 1 special character (eg. _ ! / etc.)</li>
				</ul>
			</b>
			<p>If the button to submit the form is disabled, it's for this reason.</p>
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
