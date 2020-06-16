<?php require 'app/sites/'.THEME.'/admin/sidebar.php';?>

<div class="w3-main" style="margin-left:300px;">
	<?php $old_acc=$account; $account=$dash_model->define(filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT)); ?>
	<div class="container-fluid mt-3">
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
							<form action="<?php echo URL;?>admin/users?id=<?php echo filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);?>" method="post" autocomplete="off" class="needs-validation" novalidate>
								<div class="modal-body">
									<div class="form-group">
										<label for="email"><?php echo L::account_email_new;?></label>
										<input class="form-control" type="email" name="email" required>
										<div class="invalid-feedback"><?php echo L::account_email_invalid;?></div>
									</div>
						    </div>
								<div class="modal-footer">
									<button type="submit" name="force_email" class="btn btn-outline-success btn-sm" title="<?php echo L::admin_account_saveForceI;?>"><?php echo L::admin_account_saveForce;?></button>
									<button type="submit" name="change_email" class="btn btn-success" title="<?php echo L::admin_account_saveSendI;?>"><?php echo L::admin_account_saveSend;?></button>
								</div>
							</form>
						</div>
					</div>
				</div>

				<!-- Password -->
				<div class="d-flex flex-row my-3" style="min-width: 350px;">
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
								<div class="modal-body">
									<p><?php echo L::admin_account_pwText;?></p>
						    </div>
								<div class="modal-footer">
									<form action="<?php echo URL;?>admin/users?id=<?php echo filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);?>" method="post">
										<button type="submit" name="reset_pw" class="btn btn-danger"><?php echo L::personalInfo_delete2;?></button>
									</form>
								</div>
						</div>
					</div>
				</div>
				<script>
				$("#reset_pw").click(function(){
					$("#reset_pw").html('<i class="fas fa-spinner-third fa-spin"></i>');
				});
				</script>

				<!-- Profile picture -->
				<h4><?php echo L::account_pfp_h;?></h4>
				<p><?php echo L::account_pfp_desc;?></p>
				<form action="<?php echo URL; ?>admin/users?id=<?php echo filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);?>" method="post" enctype="multipart/form-data" id="updatePFP">
					<div class="w3-display-container" style="max-height:200px;max-width:200px; margin: 0 auto;">
						<?php if(file_exists('public/accounts/'.$account->pfp.'.png')): ?>
							<img src="<?php echo URL.'public/accounts/'.$account->pfp; ?>.png" style="width:100%" id="pfp">
						<?php else: ?>
							<img src="<?php echo URL.'public/img/account.png' ?>" style="width:100%" id="pfp">
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

				<!-- Set user status -->
				<?php if($old_acc->status==OWNER): ?>
					<h4 class="mt-2"><?php echo L::admin_account_privilegesH;?></h4>
					<?php if($old_acc->id==$account->id): ?>
						<h4 class="text-danger"><?php echo L::admin_account_warning;?></h4>
					<?php endif; ?>
					<form action="<?php echo URL;?>admin/users?id=<?php echo filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);?>" method="post">
						<input type="hidden" id="profileStatus" value="<?php echo $account->status; ?>">
						<div class="form-row">
							<div class="col-lg-9 col-md-12 mt-1">
								<select id="status" name="status" class="custom-select" required>
									<option value="<?php echo ATTENDEE;?>"><?php echo L::admin_account_status_attendee;?> (<?php echo ATTENDEE;?>)</option>
									<option value="<?php echo PRE_REG;?>"><?php echo L::admin_account_status_preReg;?> (<?php echo PRE_REG;?>)</option>
									<option value="<?php echo STAFF;?>"><?php echo L::admin_account_status_staff;?> (<?php echo STAFF;?>)</option>
									<option value="<?php echo ADMIN;?>"><?php echo L::admin_account_status_admin;?> (<?php echo ADMIN;?>)</option>
									<option value="<?php echo SUPER;?>"><?php echo L::admin_account_status_super;?> (<?php echo SUPER;?>)</option>
									<option value="<?php echo OWNER;?>"><?php echo L::admin_account_status_owner;?> (<?php echo OWNER;?>)</option>
								</select>
							</div>
							<div class="col text-center mt-1">
								<button type="submit" name="account_status" class="btn btn-success"><?php echo L::reset_save;?></button>
							</div>
						</div>
					</form>
				<?php endif; ?>

				<h4 class="text-danger mt-3"><?php echo L::admin_account_dangerH;?></h4>
				<!-- Ban / Unban -->
				<form action="<?php echo URL; ?>admin/users?id=<?php echo filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);?>" method="post">
					<?php if($account->banned): ?>
						<button type="submit" name="ban_account" class="btn btn-primary"><?php echo L::admin_account_unban;?></button>
					<?php else: ?>
						<button type="submit" name="ban_account" class="btn btn-warning"><?php echo L::admin_account_ban;?></button>
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
	<?php $account=$old_acc; ?>
</div>
<script>
$("#users").addClass("bg-light");
$("#editing").addClass("bg-warning");
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
<?php require 'app/sites/global/validate_form.php'; ?>
