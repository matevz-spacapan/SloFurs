<div class="w3-main" style="margin-left:200px">
<div class="bg-primary text-white">
	<button class="btn btn-primary btn-lg d-lg-none" onclick="$('#accSidebar').show()">&#9776;</button>
	<div class="container-fluid">
	</div>
</div>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-5 col-lg-3" style="min-width: 350px;">
			<!-- Email -->
			<div class="d-flex flex-row">
				<div class=""><p></p>
					<h5><?php echo $account->email; ?></h5>
				</div>
				<div class="ml-auto">
					<button type="button" data-toggle="modal" data-target="#emailData" class="btn btn-link"><?php echo L::account_email_update;?></button>
				</div>
			</div>
			<div class="modal fade" id="emailData">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
			        <h4 class="modal-title"><?php echo L::account_email_h;?></h4>
			        <button type="button" class="close" data-dismiss="modal">&times;</button>
			      </div>
						<form action="<?php echo URL; ?>account/contact" method="post" autocomplete="off" class="needs-validation" novalidate>
							<div class="modal-body">
								<div class="form-group">
									<label for="newemail"><?php echo L::account_email_new;?></label>
									<input class="form-control" type="email" name="newemail" required>
									<div class="invalid-feedback"><?php echo L::account_email_invalid;?></div>
								</div>
								<div class="form-group">
									<label for="verifypassword"><?php echo L::account_email_pw;?></label>
									<input class="form-control" type="password" name="verifypassword" required>
									<div class="invalid-feedback"><?php echo L::account_fillOutField;?></div>
								</div>
					    </div>
							<div class="modal-footer">
								<button type="submit" name="change_email" id="submitemail" class="btn btn-success"><?php echo L::account_email_save;?></button>
							</div>
						</form>
					</div>
				</div>
			</div>
			<script>
			$("#submitemail").click(function(){
				$("#submitemail").html('<i class="fas fa-spinner-third fa-spin"></i>');
			});
			</script>

			<!-- Newsletter -->
			<div class="d-flex flex-row mb-3">
				<div class=""><p></p>
					<?php if($account->newsletter==1): ?>
						<p><?php echo L::account_email_newsletterSub;?></p>
					<?php else: ?>
						<p><?php echo L::account_email_newsletterNotSub;?></p>
					<?php endif; ?>
				</div>
				<div class="ml-auto">
					<form action="<?php echo URL; ?>account/contact" method="post">
						<?php if($account->newsletter==1): ?>
							<button type="submit" name="newsletter" class="btn btn-link text-secondary"><?php echo L::account_email_bUnsub;?></button>
						<?php else: ?>
							<button type="submit" name="newsletter" class="btn btn-link text-success"><?php echo L::account_email_bSub;?></button>
						<?php endif; ?>

					</form>
				</div>
			</div>

			<!-- Password -->
			<div class="d-flex flex-row mb-4" style="min-width: 350px;">
				<div class="">
					<h5 style="display:inline;"><?php echo L::account_password_password;?></h5>
				</div>
				<div class="ml-auto">
					<button type="button" data-toggle="modal" data-target="#passwordData" class="btn btn-link"><?php echo L::account_email_update;?></button>
				</div>
			</div>
			<div class="modal fade" id="passwordData">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
			        <h4 class="modal-title"><?php echo L::account_password_h;?></h4>
			        <button type="button" class="close" data-dismiss="modal">&times;</button>
			      </div>
						<form id="passwordForm" action="<?php echo URL; ?>account/contact" method="post" class="needs-validation" novalidate>
							<div class="modal-body">
								<div class="form-group">
									<label for="oldpassword"><?php echo L::account_password_current;?></label>
									<input class="form-control" type="password" name="oldpassword" required>
									<div class="invalid-feedback"><?php echo L::account_fillOutField;?></div>
								</div>
								<div class="form-group">
									<label for="newpassword"><?php echo L::account_password_new;?></label>
									<input class="form-control" id="pwd" type="password" name="newpassword" pattern="^(?=.{8,}$)(?=.*[a-zA-Z])(?=.*[0-9\W_]).*$" required>
									<small class="form-text text-muted"><?php echo L::account_password_newI;?></small>
									<div class="invalid-feedback"><?php echo L::account_password_invalid;?></div>
								</div>
					    </div>
							<div class="modal-footer">
								<button type="submit" id="submitpw" name="change_password" class="btn btn-success"><?php echo L::account_password_save;?></button>
							</div>
						</form>
					</div>
				</div>
			</div>
			<script>
			$("#submitpw").click(function(){
				$("#submitpw").html('<i class="fas fa-spinner-third fa-spin"></i>');
			});
			</script>

			<!-- Profile picture -->
			<h4><?php echo L::account_pfp_h;?></h4>
			<p><?php echo L::account_pfp_desc;?></p>
			<form action="<?php echo URL; ?>account/contact" method="post" enctype="multipart/form-data" id="updatePFP">
				<div class="w3-display-container" style="max-height:200px;max-width:200px; margin: 0 auto;">
					<?php if(file_exists('public/accounts/'.$account->pfp.'.jpg')): ?>
						<img src="<?php echo URL.'public/accounts/'.$account->pfp; ?>.jpg" style="width:100%" id="pfp">
					<?php else: ?>
						<img src="<?php echo URL.'public/img/account.jpg' ?>" style="width:100%" id="pfp">
					<?php endif; ?>
					<div class="w3-display-middle w3-display-hover">
						<label for="file-upload" class="btn btn-light"><?php echo L::account_pfp_label;?></label>
						<input id="file-upload" type="file" style="display:none" name="image" onchange="$('#updatePFP').submit()"/>
					</div>
				</div>
				<?php if($account->pfp!=null): ?>
					<div class="container text-center mt-3">
						<button type="button" class="btn btn-outline-danger" id="delpfp" onclick="delData('pfp')"><?php echo L::admin_account_removePFP;?></button>
						<button type="submit" name="delete_pfp" class="btn btn-danger" id="delconfpfp" style="display:none;"><?php echo L::personalInfo_delete2;?></button>
					</div>
				<?php endif; ?>
			</form>

		</div>
		<div class="col">
			<!-- Contact info -->
			<h4><?php echo L::account_contact_h;?></h4>
			<?php
				$register=false;
				require 'app/sites/'.THEME.'/account/personal_info.php';
			?>
		</div>
	</div>

</div>

<script>
$("#contact").removeClass("text-body btn-link");
$("#contact").addClass("btn-primary");
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
<?php require 'app/sites/global/validate_form.php'; ?>
