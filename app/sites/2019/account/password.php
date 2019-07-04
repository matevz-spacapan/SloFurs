<div class="w3-main" style="margin-left:200px">
<div class="w3-blue">
  <button class="w3-button w3-blue w3-xlarge w3-hide-large" onclick="side_open()">&#9776;</button>
  <div class="w3-container">
    <h1>Update your password</h1>
  </div>
</div>

<div class="w3-container w3-col l6 m8"><p>
	<form action="<?php echo URL; ?>account/update/4" method="post">
			<label>Current password</label>
			<input class="w3-input" type="password" name="oldpassword" required>
			<label>New password</label> <i class="w3-opacity w3-small">(must be at least 8 characters long and contain a number and special character)</i>
			<input class="w3-input" id="pwd" type="password" name="newpassword" pattern="^(?=.{8,}$)(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\W_]).*$" title="At least 8 characters, number and special character" required onkeyup="verifyPassword()">
			<label>New password</label> <i class="w3-opacity w3-small">(confirm)</i> <i id="correct" class="far fa-times"></i>
			<input class="w3-input" id="pwdC" type="password" required onkeyup="verifyPassword()"><p>
			<button type="submit" id="btn" name="sign_up_acc" class="w3-button w3-round w3-green" disabled="true">Save</button>
		</form>
</div>

<script>
function side_open(){
	$("#accSidebar").show();
}
function side_close(){
	$("#accSidebar").hide();
}
function onLoad(){
	$("#password").addClass("w3-blue");
}
onLoad();
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