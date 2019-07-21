<body class="w3-light-gray">

<?php require 'app/sites/'.THEME.'/admin/sidebar.php';?>

<div class="w3-main" style="margin-left:300px;">
	<?php $old_acc=$account; $account=$dash_model->define($_GET['id']); ?>
	<div class="w3-container w3-col l6 m8">
		<!-- Email -->
		<h3><?php echo L::account_email_h;?></h3>
		<p>Current: <i class="w3-text-gray"><?php echo $account->email; ?></i></p>
		<form action="<?php echo URL;?>admin/users?id=<?php echo $_GET['id'];?>" method="post" autocomplete="off">
			<label>Change email address</label>
			<input class="w3-input" type="email" name="email" required><p>
			<button type="submit" name="change_email" class="w3-button w3-round w3-green" title="This will save the email and send the user and email to confirm the email.">Save and send confirmation</button>
			<button type="submit" name="force_email" class="w3-button w3-round w3-border w3-border-green" title="This will save and immediately change the email with no confirmation.">Save and force</button>
		</form>

		<!-- Password -->
		<h3>Account password</h3>
		<p>On suspicious activity or if the user asked for a password reset and didn't do it themselves. This will send the user an email to set a new password <b>and invalidate the current account password</b>! As such they will be unable to log-in until a new password is set.</p>
		<form action="<?php echo URL;?>admin/users?id=<?php echo $_GET['id'];?>" method="post">
			<button type="button" class="w3-button w3-round w3-border w3-border-red" id="delpw" onclick="delData('pw')">Reset password</button>
			<button type="submit" name="reset_pw" class="w3-button w3-round w3-red" id="delconfpw" style="display:none;">Are you sure?</button>
		</form>

		<!-- Set user status -->
		<?php if($old_acc->status==OWNER): ?>
			<h3>Account privileges</h3>
			<form action="<?php echo URL;?>admin/users?id=<?php echo $_GET['id'];?>" method="post">
				<input type="hidden" id="profileStatus" value="<?php echo $account->status; ?>">
				<select id="status" name="status" class="w3-select" required>
					<option value="<?php echo ATTENDEE;?>">Attendee (<?php echo ATTENDEE;?>)</option>
					<option value="<?php echo PRE_REG;?>">Pre-reg available (<?php echo PRE_REG;?>)</option>
					<option value="<?php echo STAFF;?>">Staff (<?php echo STAFF;?>)</option>
					<option value="<?php echo ADMIN;?>">Administrator (<?php echo ADMIN;?>)</option>
					<option value="<?php echo SUPER;?>">Super admin (<?php echo SUPER;?>)</option>
					<option value="<?php echo OWNER;?>">OWNER (<?php echo OWNER;?>)</option>
				</select><p>
				<button type="submit" name="account_status" class="w3-button w3-round w3-green">Save</button>
			</form>
		<?php endif; ?>

		<!-- Profile picture -->
		<h3><?php echo L::account_pfp_h;?></h3>
		<p><?php echo L::account_pfp_desc;?></p>
		<form action="<?php echo URL; ?>admin/users?id=<?php echo $_GET['id'];?>" method="post" enctype="multipart/form-data" id="updatePFP">
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
				<button type="button" class="w3-button w3-round w3-border w3-border-red" id="delpfp" onclick="delData('pfp')">Remove picture</button>
				<button type="submit" name="delete_pfp" class="w3-button w3-round w3-red" id="delconfpfp" style="display:none;">Are you sure?</button>
			<?php endif; ?>
		</form>

		<!-- Contact info -->
		<h3 style="display:inline;"><?php echo L::account_contact_h;?></h3><br/><br/>
		<?php
			$register=false;
			require 'app/sites/'.THEME.'/account/personal_info.php';
		?>

		<h3 class="w3-text-red">Danger zone</h3>
		<form action="<?php echo URL; ?>admin/users?id=<?php echo $_GET['id'];?>" method="post">
			<?php if($account->banned): ?>
				<button type="submit" name="ban_account" class="w3-button w3-round w3-green">Unban account</button>
			<?php else: ?>
				<button type="submit" name="ban_account" class="w3-button w3-round w3-orange">Ban account</button>
			<?php endif; ?>
		</form>
	</div>
	<?php $account=$old_acc; ?>
</div>
<script>
$("#users").addClass("w3-sand");
$("#editing").addClass("w3-orange");
$("#editing").removeClass("w3-hide");

if($("#profileStatus").val()!=''){
	$("#status").val($("#profileStatus").val());
}

if($("#profileCountry").val()!=''){
	$("#country").val($("#profileCountry").val());
}
if($("#profileGender").val()!=''){
	$("#"+$("#profileGender").val()).prop("checked", true);
}
if($("#profileLanguage").val()!=''){
	$("#"+$("#profileLanguage").val()).prop("checked", true);
}

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
}
</script>
