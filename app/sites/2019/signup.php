<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<div class="w3-container" style="margin-top:20px">
	<div class="w3-half">
		<div class="w3-container w3-blue w3-center">
			<h3><?php echo L::signup_h;?></h3>
		</div>
		<form action="<?php echo URL; ?>signup" method="post">
			<label><?php echo L::signup_email;?></label>
			<input class="w3-input" type="email" name="email" placeholder="<?php echo L::signup_emailP;?>" required autofocus>
			<label><?php echo L::signup_username;?></label> <i class="far fa-info-circle" title="<?php echo L::signup_usernameI;?>"></i>
			<input class="w3-input" type="text" name="username" placeholder="<?php echo L::signup_usernameP;?>" required>
			<label><?php echo L::signup_pw;?></label> <i id="correct1" class="far fa-times"></i>
			<input class="w3-input" id="pwd" type="password" name="password" placeholder="<?php echo L::signup_pwP;?>" pattern="^(?=.{8,}$)(?=.*[a-zA-Z])(?=.*[0-9\W_]).*$" title="<?php echo L::signup_pwT;?>" required onkeyup="verifyPassword()">
			<label><?php echo L::signup_pw;?></label> <i class="w3-opacity w3-small"><?php echo L::signup_confirm;?></i> <i id="correct2" class="far fa-times"></i>
			<input class="w3-input" id="pwdC" type="password" placeholder="<?php echo L::signup_confirmP;?>" required onkeyup="verifyPassword()">
			<input class="w3-check" type="checkbox" required>
			<label><?php echo L::signup_privacy1;?> <a href="<?php echo URL;?>privacy" target="_blank"><?php echo L::signup_privacy3;?> <i class="far fa-external-link"></i></a>.</label><br><br>
			<div class="g-recaptcha" data-sitekey="6Leegq0UAAAAAKUi7Uy68xw2wqrLQ83LymIwISSy"></div><br>
			<div class="w3-center">
				<button type="submit" id="btn" name="sign_up_acc" class="w3-button w3-round w3-border w3-border-blue" disabled="true"><?php echo L::signup_register;?></button><p>
			</div>
			<div>
				<?php echo L::signup_login;?> <a href="<?php echo URL;?>login"><?php echo L::signup_loginButton;?></a><br>
				<?php echo L::signup_noActivation;?> <a href="<?php echo URL;?>signup/resend"><?php echo L::signup_noActivationButton;?></a>
			</div>
		</form>
	</div>
	<div class="w3-half w3-center">
		<div class="w3-container">
			<h3><?php echo L::signup_welcome;?></h3>
		</div>
		<div class="w3-container">
			<?php echo L::signup_desc1;?>
		</div>
		<div class="w3-container">
			<b class="w3-left-align"><?php echo L::signup_desc2;?>
				<ul>
					<li><?php echo L::signup_pwCond1;?></li>
					<li><?php echo L::signup_pwCond2;?></li>
				</ul>
			</b>
			<p><?php echo L::signup_desc3;?></p>
			<b><?php echo L::signup_desc4;?></b>
		</div>
	</div>
</div>
<script>
function verifyPassword(){
	regex=/^(?=.{8,}$)(?=.*[a-zA-Z])(?=.*[0-9\W_]).*$/;
	if($("#pwd").val()==$("#pwdC").val()&&$("#pwd").val().match(regex)){
		$("#btn").prop("disabled", false);
		if($("#correct2").hasClass("fa-times")){
			$("#correct2").removeClass("fa-times").addClass("fa-check");
		}
	}
	else{
		$("#btn").prop("disabled", true);
		if($("#correct2").hasClass("fa-check")){
			$("#correct2").removeClass("fa-check").addClass("fa-times");
		}
	}
	if($("#pwd").val().match(regex)){
		if($("#correct1").hasClass("fa-times")){
			$("#correct1").removeClass("fa-times").addClass("fa-check");
		}
	}
	else{
		if($("#correct1").hasClass("fa-check")){
			$("#correct1").removeClass("fa-check").addClass("fa-times");
		}
	}
}
</script>
