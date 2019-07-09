<div class="w3-main" style="margin-left:200px">
<div class="w3-blue">
	<button class="w3-button w3-blue w3-xlarge w3-hide-large" onclick="side_open()">&#9776;</button>
	<div class="w3-container">
		<h1>Account information</h1>
	</div>
</div>
<div class="w3-container w3-col l6 m8">
	<!-- Email -->
	<h3>Account email</h3>
	<i class="w3-text-gray"><?php echo $account->email; ?></i>
	<button onclick="showEmail()" class="w3-button w3-round w3-border w3-border-blue"> Update</button>
	<div class="w3-container w3-hide" id="emailData">
		<form action="<?php echo URL; ?>account/contact" method="post" autocomplete="off">
			<label>New email address</label>
			<input class="w3-input" type="email" name="newemail" required>
			<label>Verify password</label>
			<input class="w3-input" type="password" name="verifypassword" required><p>
			<button type="submit" name="change_email" class="w3-button w3-round w3-green">Save</button>
		</form>
	</div>

	<!-- Profile picture -->
	<h3>Profile photo</h3>
	<form action="<?php echo URL; ?>account/contact" method="post" enctype="multipart/form-data" id="updatePFP">
		<div class="w3-display-container" style="max-height:200px;max-width:200px">
			<?php if(file_exists('public/accounts/'.$account->pfp.'.png')): ?>
				<img src="<?php echo URL.'public/accounts/'.$account->pfp; ?>.png" style="width:100%" id="pfp">
			<?php else: ?>
				<img src="<?php echo URL.'public/img/account.png' ?>" style="width:100%" id="pfp">
			<?php endif; ?>
			<div class="w3-display-middle w3-display-hover">
				<label for="file-upload" class="w3-button w3-round w3-border w3-border-blue w3-white">Change photo</label>
				<input id="file-upload" type="file" style="display:none" name="image" onchange="$('#updatePFP').submit()"/>
			</div>
		</div>
	</form>

	<!-- Contact info -->
	<h3 style="display:inline;">Contact information</h3> <i class="w3-opacity w3-small">This information is kept private and validated at the event by staff. It will also show up on your invoice.</i><br/><br/>
	<?php
		$register=false;
		require 'app/sites/'.THEME.'/account/personal_info.php';
	?>
</div>

<script>
function side_open(){
	$("#accSidebar").show();
}
function side_close(){
	$("#accSidebar").hide();
}
function showEmail(){
	$("#emailData").removeClass("w3-hide");
}
function onLoad(){ //selects the current page in the sidebar, country&gender saved in MySQL
	$("#contact").addClass("w3-blue");
	if($("#profileCountry").val()!=''){
		$("#country").val($("#profileCountry").val());
	}
	if($("#profileGender").val()!=''){
		$("#"+$("#profileGender").val()).prop("checked", true);
	}
}
onLoad();
</script>