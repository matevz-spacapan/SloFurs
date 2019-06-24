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
			<button type="submit" name="update_email" class="w3-button w3-round w3-blue">Save</button>
		</form>
	</div>

	<!-- Profile picture -->
	<h3>Profile photo</h3>
	<form action="<?php echo URL; ?>account/update/2" method="post" id="updatePFP">
		<div class="w3-display-container" style="max-height:200px;max-width:200px">
			<?php if(file_exists('public/accounts/'.$_SESSION['account'].'.png')): ?>
				<img src="<?php echo URL.'public/accounts/'.$_SESSION['account']; ?>.png" style="width:100%" id="pfp">
			<?php else: ?>
				<img src="<?php echo URL.'public/img/account.png' ?>" style="width:100%" id="pfp">
			<?php endif; ?>
			<div class="w3-display-middle w3-display-hover">
				<label for="file-upload" class="w3-button w3-round w3-border w3-border-blue w3-white">Change photo</label>
				<input id="file-upload" type="file" style="display:none" name="pfp" onchange="submitPFP()"/>
			</div>
		</div>
	</form>

	<!-- Contact info -->
	<h3 style="display:inline;">Contact information</h3> <i class="w3-opacity w3-small">This information is kept private and validated at the event by staff. It will also show up on your invoice.</i><br/><br/>
	<form action="<?php echo URL; ?>account/update/3" method="post">
		<label>First name</label>
		<input class="w3-input" type="text" name="fname" value="<?php echo $account->fname; ?>">

		<label>Last name</label>
		<input class="w3-input" type="text" name="lname" value="<?php echo $account->lname; ?>">

		<label>Address</label>
		<input class="w3-input" type="text" name="address" value="<?php echo $account->address; ?>">

		<label>Address 2</label> <i class="w3-opacity w3-small">(optional)</i>
		<input class="w3-input" type="text" name="address2" value="<?php echo $account->address2; ?>">

		<label>Town/City</label>
		<input class="w3-input" type="text" name="city" value="<?php echo $account->city; ?>">

		<label>Postcode</label>
		<input class="w3-input" type="text" name="postcode" value="<?php echo $account->post; ?>">

		<label>Country</label>
		<input type="hidden" id="profileCountry" value="<?php echo $account->country; ?>">
		<?php require 'app/sites/global/countries.html'; ?>

		<label>Phone number</label> <i class="w3-opacity w3-small">Prefferably mobile, in case something happens. Please enter the country code too (eg. +386).</i>
		<input class="w3-input" type="text" name="phone" placeholder="eg. +38641123456" value="<?php echo $account->phone; ?>">

		<label>Date of birth</label> <i class="w3-opacity w3-small">Certain events might require you to be of a certain age.</i>
		<input class="w3-input" type="date" name="dob" value="<?php echo $account->dob; ?>">

		<label>Gender</label><br/>
		<input type="hidden" id="profileGender" value="<?php echo $account->gender; ?>">
		<input class="w3-radio" type="radio" name="gender" value="male" id="male">
		<label>Male</label>
		<input class="w3-radio" type="radio" name="gender" value="female" id="female">
		<label>Female</label>
		<input class="w3-radio" type="radio" name="gender" value="other" id="other">
		<label>Other</label>
		<input class="w3-radio" type="radio" name="gender" value="silent" id="silent">
		<label>Prefer not to say</label><p>

		<button type="submit" name="update_contact" class="w3-button w3-round w3-blue">Save</button>
	</form>
</div>

<script>
function side_open() {
	document.getElementById("accSidebar").style.display="block";
}

function side_close() {
	document.getElementById("accSidebar").style.display="none";
}
function showEmail(){
	var element=document.getElementById("emailData");
	element.className=element.className.replace("w3-hide", "");
}
function submitPFP(){
	document.getElementById("updatePFP").submit(); //if file is changed then submit the form, to update the picture
}
function onLoad(){ //selects the current page in the sidebar, country&gender saved in MySQL
	document.getElementById("contact").classList.add("w3-blue");
	if(document.getElementById("profileCountry").value!=''){
		document.getElementById("country").value=document.getElementById("profileCountry").value;
	}
	if(document.getElementById("profileGender").value!=''){
		document.getElementById(document.getElementById("profileGender").value).checked=true;
	}
}
onLoad();
</script>