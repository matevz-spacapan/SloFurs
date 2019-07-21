<form action="<?php
	echo URL;
	if($account->id!=$_SESSION['account']){
		echo "admin/users?id=".$_GET['id'];
	}
	elseif(!$register){
		echo 'account/contact';
	}
	else{
		echo 'register';
	} ?>" method="post">
	<label><?php echo L::personalInfo_fname;?></label>
	<input class="w3-input" type="text" name="fname" placeholder="<?php echo L::personalInfo_fnameP;?>" pattern="^([A-Ž][a-ž]+\ *){1,}$" value="<?php echo $account->fname; ?>" required>

	<label><?php echo L::personalInfo_lname;?></label>
	<input class="w3-input" type="text" name="lname" placeholder="<?php echo L::personalInfo_lnameP;?>" pattern="^([A-Ž][a-ž]+\ *){1,}$" value="<?php echo $account->lname; ?>" required>

	<label><?php echo L::personalInfo_address;?></label>
	<input class="w3-input" type="text" name="address" placeholder="<?php echo L::personalInfo_addressP;?>" pattern="^([A-Ž][a-ž]+\ *){1,} [1-9][0-9]*$" value="<?php echo $account->address; ?>" required>

	<label><?php echo L::personalInfo_address2;?></label> <i class="w3-opacity w3-small"><?php echo L::personalInfo_optional;?></i>
	<input class="w3-input" type="text" name="address2" placeholder="<?php echo L::personalInfo_address2P;?>" pattern="^([A-Ž][a-ž]+\ *){1,} [1-9][0-9]*$" value="<?php echo $account->address2; ?>">

	<label><?php echo L::personalInfo_city;?></label>
	<input class="w3-input" type="text" name="city" placeholder="<?php echo L::personalInfo_cityP;?>" pattern="^([A-Ž][a-ž]+\ *){1,}$" value="<?php echo $account->city; ?>" required>

	<label><?php echo L::personalInfo_postcode;?></label>
	<input class="w3-input" type="text" name="postcode" placeholder="<?php echo L::personalInfo_postcodeP;?>" value="<?php echo $account->post; ?>" required>

	<label><?php echo L::personalInfo_country;?></label>
	<input type="hidden" id="profileCountry" value="<?php echo $account->country; ?>">
	<?php require 'app/sites/global/countries.php'; ?>

	<label><?php echo L::personalInfo_phone;?></label> <i class="w3-opacity w3-small"><?php echo L::personalInfo_phoneI;?></i>
	<input class="w3-input" type="text" name="phone" placeholder="<?php echo L::personalInfo_phoneP;?>" pattern="^\+([0-9]){9,}$" value="<?php echo $account->phone; ?>" required>

	<label><?php echo L::personalInfo_dob;?></label> <i class="w3-opacity w3-small"><?php echo L::personalInfo_dobI;?></i>
	<input class="w3-input" type="date" name="dob" value="<?php echo $account->dob; ?>" required>

	<label><?php echo L::personalInfo_gender;?></label><br/>
	<input type="hidden" id="profileGender" value="<?php echo $account->gender; ?>">
	<input class="w3-radio" type="radio" name="gender" value="male" id="male" required>
	<label><?php echo L::personalInfo_male;?></label>
	<input class="w3-radio" type="radio" name="gender" value="female" id="female">
	<label><?php echo L::personalInfo_female;?></label>
	<input class="w3-radio" type="radio" name="gender" value="other" id="other">
	<label><?php echo L::personalInfo_other;?></label>
	<input class="w3-radio" type="radio" name="gender" value="silent" id="silent">
	<label><?php echo L::personalInfo_silent;?></label><p>

	<label><?php echo L::personalInfo_language;?></label><br/>
	<input type="hidden" id="profileLanguage" value="<?php echo $account->language; ?>">
	<input class="w3-radio" type="radio" name="language" value="si" id="si" required>
	<label><img src="<?php echo URL?>public/img/si.png" alt="Slovenščina" height="40" class="w3-circle"></label>
	<input class="w3-radio" type="radio" name="language" value="en" id="en">
	<label><img src="<?php echo URL?>public/img/en.png" alt="English" height="40" class="w3-circle"></label>

	<div class="w3-center">
		<button type="submit" name="update_personal_info" class="w3-button w3-round w3-green"><?php echo L::personalInfo_save;?></button>
		<?php	if(!$register): ?>
			<button type="button" class="w3-button w3-round w3-border w3-border-red" id="delcontact" onclick="delData('contact')"><?php echo L::personalInfo_delete1;?></button>
			<button type="submit" name="delete_personal_info" id="delconfcontact" class="w3-button w3-red w3-round" style="display: none;"><?php echo L::personalInfo_delete2;?></button>
			<script>
			function delData(id){
				$("#del"+id).addClass("scale-out-center");
				setTimeout(function(){
					contDel(id);
				}, 500);
			}
			function contDel(id){
				$("#del"+id).hide();
				$("#delconf"+id).css("display", "inline-block");
				$("#delconf"+id).addClass("scale-in-center");
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
