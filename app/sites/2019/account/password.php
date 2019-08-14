<div class="w3-main" style="margin-left:200px">
<div class="w3-blue">
  <button class="w3-button w3-blue w3-xlarge w3-hide-large" onclick="$('#accSidebar').show()">&#9776;</button>
  <div class="w3-container">
    <h1><?php echo L::account_password_h;?></h1>
  </div>
</div>

<div class="w3-container w3-col l6 m8"><p>
	<form action="<?php echo URL; ?>account/password" method="post">
			<label><?php echo L::account_password_current;?></label>
			<input class="w3-input" type="password" name="oldpassword" required placeholder="<?php echo L::account_password_current;?>">
			<label><?php echo L::account_password_new;?></label> <i id="correct1" class="far fa-times"></i> <i class="w3-opacity w3-small"><?php echo L::account_password_newI;?></i>
			<input class="w3-input" id="pwd" type="password" placeholder="<?php echo L::signup_pwP;?>" name="newpassword" pattern="^(?=.{8,}$)(?=.*[a-zA-Z])(?=.*[0-9\W_]).*$" title="<?php echo L::signup_pwT;?>" required onkeyup="verifyPassword()">
			<label><?php echo L::account_password_new;?></label> <i id="correct2" class="far fa-times"></i> <i class="w3-opacity w3-small"><?php echo L::account_password_confirm;?></i>
			<input class="w3-input" id="pwdC" type="password" placeholder="<?php echo L::signup_confirmP;?>" required onkeyup="verifyPassword()"><p>
			<button type="submit" id="btn" name="change_password" class="w3-button w3-round w3-green" disabled="true"><?php echo L::account_password_save;?></button>
		</form>
</div>

<script>
$("#password").addClass("w3-blue");
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
