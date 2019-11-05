<form class="needs-validation" novalidate action="<?php
	echo URL;
	if($account->id!=$_SESSION['account']){
		echo "admin/users?id=".filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
	}
	elseif(!$register){
		echo 'account/contact';
	}
	else{
		echo 'register';
	} ?>" method="post">

	<div class="form-row">
		<!-- Name -->
		<div class="col-lg-6 col-md-12">
			<div class="form-group">
				<label for="fname"><?php echo L::personalInfo_fname;?></label>
				<input class="form-control" type="text" name="fname" placeholder="<?php echo L::personalInfo_fnameP;?>" pattern="^([A-Ž][a-ž]+\ *){1,}$" value="<?php echo $account->fname; ?>" required>
			</div>
		</div>
		<!-- Surname -->
		<div class="col">
			<div class="form-group">
				<label for="lname"><?php echo L::personalInfo_lname;?></label>
				<input class="form-control" type="text" name="lname" placeholder="<?php echo L::personalInfo_lnameP;?>" pattern="^([A-Ž][a-ž]+\ *){1,}$" value="<?php echo $account->lname; ?>" required>
			</div>
		</div>
	</div>

	<div class="form-row">
		<!-- Address -->
		<div class="col-lg-6 col-md-12">
			<div class="form-group">
				<label for="address"><?php echo L::personalInfo_address;?></label>
				<input class="form-control" type="text" name="address" placeholder="<?php echo L::personalInfo_addressP;?>" pattern="^([A-Ž][a-ž]+\ *){1,} [1-9][0-9]*$" value="<?php echo $account->address; ?>" required>
			</div>
		</div>
		<!-- Address 2 -->
		<div class="col">
			<div class="form-group">
				<label for="address2"><?php echo L::personalInfo_address2;?></label> <small class="text-muted"><?php echo L::personalInfo_optional;?></small>
				<input class="form-control" type="text" name="address2" placeholder="<?php echo L::personalInfo_address2P;?>" pattern="^([A-Ž][a-ž]+\ *){1,} [1-9][0-9]*$" value="<?php echo $account->address2; ?>">
			</div>
		</div>
	</div>

	<div class="form-row">
		<!-- City -->
		<div class="col-lg-5 col-md-12">
			<div class="form-group">
				<label for="city"><?php echo L::personalInfo_city;?></label>
				<input class="form-control" type="text" name="city" placeholder="<?php echo L::personalInfo_cityP;?>" pattern="^([A-Ž][a-ž]+\ *){1,}$" value="<?php echo $account->city; ?>" required>
			</div>
		</div>
		<!-- Postcode -->
		<div class="col-lg-2 col-md-12">
			<div class="form-group">
				<label for="postcode"><?php echo L::personalInfo_postcode;?></label>
				<input class="form-control" type="text" name="postcode" placeholder="<?php echo L::personalInfo_postcodeP;?>" value="<?php echo $account->post; ?>" required>
			</div>
		</div>
		<!-- Country -->
		<div class="col">
			<div class="form-group">
				<label for="country"><?php echo L::personalInfo_country;?></label>
				<input type="hidden" id="profileCountry" value="<?php echo $account->country; ?>">
				<?php require 'app/sites/global/countries.php'; ?>
			</div>
		</div>
	</div>

	<div class="form-row">
		<!-- DOB -->
		<div class="col-lg-6 col-md-12">
			<script src="<?php echo URL;?>/public/datepicker/js/bootstrap-datepicker.min.js"></script>
			<link rel="stylesheet" href="<?php echo URL;?>/public/datepicker/css/bootstrap-datepicker3.min.css">
			<div class="form-group">
				<label for="dob"><?php echo L::personalInfo_dob;?></label>
				<input data-provide="datepicker" data-date-autoclose="true" class="form-control datepicker" name="dob" value="<?php echo $account->dob; ?>" required style="background-color: #fff !important; cursor: text !important;" required>
				<small class="form-text text-muted"><?php echo L::personalInfo_dobI;?></small>
			</div>
			<script type="text/javascript">
			$('.datepicker').datepicker({
				format: "yyyy-mm-dd",
		    endDate: "-1d",
		    language: "sl"
			});
			$(document).on("focusin", ".datepicker", function() {
			  $(this).prop('readonly', true);
			});

			$(document).on("focusout", ".datepicker", function() {
			  $(this).prop('readonly', false);
			});
			</script>
		</div>
		<!-- Phone -->
		<div class="col">
			<div class="form-group">
				<label for="phone"><?php echo L::personalInfo_phone;?></label>
				<input class="form-control" type="text" name="phone" placeholder="<?php echo L::personalInfo_phoneP;?>" pattern="^\+([0-9]){9,}$" value="<?php echo $account->phone; ?>" required>
				<small class="form-text text-muted"><?php echo L::personalInfo_phoneI;?></small>
			</div>
		</div>
	</div>

	<div class="form-row">
		<!-- Sex -->
		<div class="col-lg-6 col-md-12">
			<div class="form-group">
				<label><?php echo L::personalInfo_gender;?></label><br/>
				<input type="hidden" id="profileGender" value="<?php echo $account->gender; ?>">
				<div class="custom-control custom-radio custom-control-inline">
					<input class="custom-control-input" type="radio" name="gender" value="male" id="male" required>
					<label for="male" class="custom-control-label"><?php echo L::personalInfo_male;?></label>
				</div>
				<div class="custom-control custom-radio custom-control-inline">
					<input class="custom-control-input" type="radio" name="gender" value="female" id="female">
					<label for="female" class="custom-control-label"><?php echo L::personalInfo_female;?></label>
				</div>
				<div class="custom-control custom-radio custom-control-inline">
					<input class="custom-control-input" type="radio" name="gender" value="silent" id="silent">
					<label for="silent" class="custom-control-label"><?php echo L::personalInfo_silent;?></label>
				</div>
			</div>
		</div>
		<!-- Language -->
		<div class="col">
			<div class="form-group">
				<label><?php echo L::personalInfo_language;?></label> <small class="text-muted"><?php echo L::personalInfo_languageI;?></small><br/>
				<input type="hidden" id="profileLanguage" value="<?php echo $account->language; ?>">
				<div class="custom-control custom-radio custom-control-inline">
					<input class="custom-control-input" type="radio" name="language" value="si" id="si" required>
					<label for="en" class="custom-control-label"><img src="<?php echo URL?>public/img/si.png" alt="Slovenščina" height="40" class="rounded-circle"></label>
				</div>
				<div class="custom-control custom-radio custom-control-inline">
					<input class="custom-control-input" type="radio" name="language" value="en" id="en">
					<label for="en" class="custom-control-label"><img src="<?php echo URL?>public/img/en.png" alt="English" height="40" class="rounded-circle"></label>
				</div>
			</div>
		</div>
	</div>

	<div class="text-center">
		<button type="submit" name="update_personal_info" class="btn btn-success"><?php echo L::personalInfo_save;?></button>
		<?php	if(!$register): ?>
			<button type="button" class="btn btn-outline-danger" id="delcontact" onclick="delData('contact')"><?php echo L::personalInfo_delete1;?></button>
			<button type="submit" name="delete_personal_info" id="delconfcontact" class="btn btn-danger" style="display: none;"><?php echo L::personalInfo_delete2;?></button>
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
			</script>
		<?php endif; ?>
	</div>
</form>
