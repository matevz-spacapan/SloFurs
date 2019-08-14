<div class="w3-container" style="margin-top:20px">
	<div class="w3-half">
		<div class="w3-container w3-blue w3-center">
			<h3><?php echo L::reset_h;?></h3>
		</div>
		<form action="<?php echo URL; ?>login/forgot" method="post">
			<label><?php echo L::reset_email;?></label>
			<input class="w3-input" type="email" name="email" value="<?php echo $email;?>" disabled>
			<?php $_SESSION['reset_email']=strip_tags($email);?>
			<label><?php echo L::reset_pw;?></label> <i id="correct1" class="far fa-times"></i>
			<input class="w3-input" id="pwd" type="password" name="password" placeholder="<?php echo L::reset_pwP;?>" pattern="^(?=.{8,}$)(?=.*[a-zA-Z])(?=.*[0-9\W_]).*$" title="<?php echo L::signup_pwT;?>" required onkeyup="verifyPassword()">
			<label><?php echo L::reset_pw;?></label> <i id="correct2" class="far fa-times"></i> <i class="w3-opacity w3-small"><?php echo L::reset_confirm;?></i>
			<input class="w3-input" id="pwdC" type="password" placeholder="<?php echo L::reset_confirmP;?>" required onkeyup="verifyPassword()"><br>
			<div class="w3-center">
				<button type="submit" id="btn" name="finish_reset" class="w3-button w3-round w3-border w3-green" disabled><?php echo L::reset_save;?></button>
			</div>
		</form>
	</div>
	<div class="w3-half w3-center">
		<div class="w3-container">
			<h3><?php echo L::reset_select;?></h3>
		</div>
		<div class="w3-container">
			<?php echo L::reset_desc1;?>
		</div>
		<div class="w3-container">
			<b class="w3-left-align"><?php echo L::signup_desc2;?>
				<ul>
					<li><?php echo L::signup_pwCond1;?></li>
					<li><?php echo L::signup_pwCond2;?></li>
				</ul>
			</b>
			<p><?php echo L::signup_desc3;?></p>
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
