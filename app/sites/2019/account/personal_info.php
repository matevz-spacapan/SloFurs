<form action="<?php
	echo URL;
	if(!$register){
		echo 'account/contact';
	}
	else{
		echo 'register';
	} ?>" method="post">
	<label>First name</label>
	<input class="w3-input" type="text" name="fname" pattern="^([A-Ž][a-ž]+\ *){1,}$" value="<?php echo $account->fname; ?>" required>

	<label>Last name</label>
	<input class="w3-input" type="text" name="lname" pattern="^([A-Ž][a-ž]+\ *){1,}$" value="<?php echo $account->lname; ?>" required>

	<label>Address</label>
	<input class="w3-input" type="text" name="address" pattern="^([A-Ž][a-ž]+\ *){1,} [1-9][0-9]*$" value="<?php echo $account->address; ?>" required>

	<label>Address 2</label> <i class="w3-opacity w3-small">(optional)</i>
	<input class="w3-input" type="text" name="address2" pattern="^([A-Ž][a-ž]+\ *){1,} [1-9][0-9]*$" value="<?php echo $account->address2; ?>">

	<label>Town/City</label>
	<input class="w3-input" type="text" name="city" pattern="^([A-Ž][a-ž]+\ *){1,}$" value="<?php echo $account->city; ?>" required>

	<label>Postcode</label>
	<input class="w3-input" type="text" name="postcode" value="<?php echo $account->post; ?>" required>

	<label>Country</label>
	<input type="hidden" id="profileCountry" value="<?php echo $account->country; ?>">
	<?php require 'app/sites/global/countries.html'; ?>

	<label>Phone number</label> <i class="w3-opacity w3-small">Prefferably mobile, in case something happens. Please enter the country code too (eg. +386), no spaces.</i>
	<input class="w3-input" type="text" name="phone" placeholder="eg. +38641123456" pattern="^\+([0-9]){9,}$" value="<?php echo $account->phone; ?>" required>

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

	<div class="w3-center">
		<button type="submit" name="update_personal_info" class="w3-button w3-round w3-green">Save</button>
		<?php	if(!$register): ?>
			<button type="button" class="w3-button w3-round w3-border w3-border-red" id="del1" onclick="delData()">Delete all</button>
			<button type="submit" name="delete_personal_info" id="delconf" class="w3-button w3-red w3-round" style="display: none;">Are you sure?</button>
			<script>
			function delData(){
				$("#del1").addClass("scale-out-center");
				setTimeout(function(){
					contDel();
				}, 500);
			}
			function contDel(){
				$("#del1").hide();
				$("#delconf").css("display", "inline-block");
				$("#delconf").addClass("scale-in-center");
				setTimeout(function(){
					$("#delconf").removeClass("scale-in-center");
				}, 500);
			}
			var d= new Date();
			d.setDate(d.getDate() - 1);
			document.getElementsByName("dob")[0].setAttribute("max", d.toISOString().split('T')[0]);
			</script>
		<?php endif; ?>
	</div>
</form>
