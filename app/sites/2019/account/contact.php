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
		<form action="<?php echo URL; ?>account/update/1" method="post" autocomplete="off">
			<label>New email address</label>
			<input class="w3-input" type="email" name="newemail" required>
			<label>Verify password</label>
			<input class="w3-input" type="password" name="verifypassword" required><p>
			<button type="submit" class="w3-button w3-round w3-green">Save</button>
		</form>
	</div>

	<!-- Profile picture -->
	<h3>Profile photo</h3>
	<form action="<?php echo URL; ?>account/update/2" method="post" enctype="multipart/form-data" id="updatePFP">
		<div class="w3-display-container" style="max-height:200px;max-width:200px">
			<?php if(file_exists('public/accounts/'.$account->pfp.'.png')): ?>
				<img src="<?php echo URL.'public/accounts/'.$account->pfp; ?>.png" style="width:100%" id="pfp">
			<?php else: ?>
				<img src="<?php echo URL.'public/img/account.png' ?>" style="width:100%" id="pfp">
			<?php endif; ?>
			<div class="w3-display-middle w3-display-hover">
				<label for="file-upload" class="w3-button w3-round w3-border w3-border-blue w3-white">Change photo</label>
				<input id="file-upload" type="file" style="display:none" name="image" onchange="submitPFP()"/>
			</div>
		</div>
	</form>

	<!-- Contact info -->
	<h3 style="display:inline;">Contact information</h3> <i class="w3-opacity w3-small">This information is kept private and validated at the event by staff. It will also show up on your invoice.</i><br/><br/>
	<form action="<?php echo URL; ?>account/update/3" method="post">
		<label>First name</label>
		<input class="w3-input" type="text" name="fname" value="<?php echo $account->fname; ?>" required>

		<label>Last name</label>
		<input class="w3-input" type="text" name="lname" value="<?php echo $account->lname; ?>" required>

		<label>Address</label>
		<input class="w3-input" type="text" name="address" value="<?php echo $account->address; ?>" required>

		<label>Address 2</label> <i class="w3-opacity w3-small">(optional)</i>
		<input class="w3-input" type="text" name="address2" value="<?php echo $account->address2; ?>">

		<label>Town/City</label>
		<input class="w3-input" type="text" name="city" value="<?php echo $account->city; ?>" required>

		<label>Postcode</label>
		<input class="w3-input" type="text" name="postcode" value="<?php echo $account->post; ?>" required>

		<label>Country</label>
		<input type="hidden" id="profileCountry" value="<?php echo $account->country; ?>">
		<?php require 'app/sites/global/countries.html'; ?>

		<label>Phone number</label> <i class="w3-opacity w3-small">Prefferably mobile, in case something happens. Please enter the country code too (eg. +386).</i>
		<input class="w3-input" type="text" name="phone" placeholder="eg. +38641123456" value="<?php echo $account->phone; ?>" required>

		<label>Date of birth</label> <i class="w3-opacity w3-small">Certain events might require you to be of a certain age.</i>
		<input class="w3-input" type="date" name="dob" value="<?php echo $account->dob; ?>" required>

		<label>Gender</label><br/>
		<input type="hidden" id="profileGender" value="<?php echo $account->gender; ?>">
		<input class="w3-radio" type="radio" name="gender" value="male" id="male" required>
		<label>Male</label>
		<input class="w3-radio" type="radio" name="gender" value="female" id="female">
		<label>Female</label>
		<input class="w3-radio" type="radio" name="gender" value="other" id="other">
		<label>Other</label>
		<input class="w3-radio" type="radio" name="gender" value="silent" id="silent">
		<label>Prefer not to say</label><p>

		<button type="submit" class="w3-button w3-round w3-green">Save</button>
	</form>
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
function submitPFP(){
	$("#updatePFP").submit(); //if file is changed then submit the form, to update the picture
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