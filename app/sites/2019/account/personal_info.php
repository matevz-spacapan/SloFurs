<form action="<?php echo URL; ?>account/update/3<?php if($register){echo '/1';} ?>" method="post">
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
		
	<div class="w3-center">
		<button type="submit" class="w3-button w3-round w3-green">Save</button>
	</div>
</form>