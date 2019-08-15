<div class="w3-main" style="margin-left:200px">
<div class="w3-blue">
	<button class="w3-button w3-blue w3-xlarge w3-hide-large" onclick="$('#accSidebar').show()">&#9776;</button>
	<div class="w3-container">
		<h1><?php echo L::account_h;?></h1>
	</div>
</div>
<div class="w3-container">
	<!-- Email -->
	<h3><?php echo L::account_email_h;?></h3>
	<i class="w3-text-gray"><?php echo $account->email; ?></i>
	<button onclick="$('#emailData').removeClass('w3-hide')" class="w3-button w3-round w3-border w3-border-blue"><?php echo L::account_email_update;?></button>
	<div class="w3-container w3-hide" id="emailData">
		<form action="<?php echo URL; ?>account/contact" method="post" autocomplete="off">
			<label><?php echo L::account_email_new;?></label>
			<input class="w3-input" type="email" name="newemail" required>
			<label><?php echo L::account_email_pw;?></label>
			<input class="w3-input" type="password" name="verifypassword" required><p>
			<button type="submit" name="change_email" class="w3-button w3-round w3-green"><?php echo L::account_email_save;?></button>
		</form>
	</div>

	<!-- Profile picture -->
	<h3><?php echo L::account_pfp_h;?></h3>
	<p><?php echo L::account_pfp_desc;?></p>
	<form action="<?php echo URL; ?>account/contact" method="post" enctype="multipart/form-data" id="updatePFP">
		<div class="w3-display-container" style="max-height:200px;max-width:200px">
			<?php if(file_exists('public/accounts/'.$account->pfp.'.png')): ?>
				<img src="<?php echo URL.'public/accounts/'.$account->pfp; ?>.png" style="width:100%" id="pfp">
			<?php else: ?>
				<img src="<?php echo URL.'public/img/account.png' ?>" style="width:100%" id="pfp">
			<?php endif; ?>
			<div class="w3-display-middle w3-display-hover">
				<label for="file-upload" class="w3-button w3-round w3-border w3-border-blue w3-white"><?php echo L::account_pfp_label;?></label>
				<input id="file-upload" type="file" style="display:none" name="image" onchange="$('#updatePFP').submit()"/>
			</div>
		</div>
		<?php if($account->pfp!=null): ?>
			<p>
			<button type="button" class="w3-button w3-round w3-border w3-border-red" id="delpfp" onclick="delData('pfp')" style="margin-left:30px;"><?php echo L::admin_account_removePFP;?></button>
			<button type="submit" name="delete_pfp" class="w3-button w3-round w3-red" id="delconfpfp" style="display:none; margin-left:37px;"><?php echo L::personalInfo_delete2;?></button>
		<?php endif; ?>
	</form>

	<!-- Contact info -->
	<h3 style="display:inline;"><?php echo L::account_contact_h;?></h3> <i class="w3-opacity w3-small"><?php echo L::account_contact_hInfo;?></i><br/><br/>
	<?php
		$register=false;
		require 'app/sites/'.THEME.'/account/personal_info.php';
	?>
</div>

<script>
$("#contact").addClass("w3-blue");
if($("#profileCountry").val()!=''){
	$("#country").val($("#profileCountry").val());
}
if($("#profileGender").val()!=''){
	$("#"+$("#profileGender").val()).prop("checked", true);
}
if($("#profileLanguage").val()!=''){
	$("#"+$("#profileLanguage").val()).prop("checked", true);
}
</script>
