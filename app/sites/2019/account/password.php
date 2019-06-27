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
function side_open() {
	document.getElementById("accSidebar").style.display = "block";
}

function side_close() {
	document.getElementById("accSidebar").style.display = "none";
}
function selector(){
	document.getElementById("password").classList.add("w3-blue");
}
selector(); //selects the current page in the sidebar
function verifyPassword(){
	if(document.getElementById('pwd').value==document.getElementById('pwdC').value&&document.getElementById('pwd').value.length>=8){
		document.getElementById("btn").disabled = false;
		if(document.getElementById("correct").classList.contains('fa-times')){
			document.getElementById("correct").classList.replace('fa-times','fa-check');
		}
	}
	else{
		document.getElementById("btn").disabled = true;
		if(document.getElementById("correct").classList.contains('fa-check')){
			document.getElementById("correct").classList.replace('fa-check','fa-times');
		}
	}
}
</script>