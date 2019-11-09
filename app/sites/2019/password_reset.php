<div class="modal fade" id="forgotModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"><?php echo L::reset_h;?></h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<form action="<?php echo URL; ?>login/forgot" method="post" class="needs-validation" novalidate>
				<div class="modal-body">
					<div class="form-group">
						<label for="email"><?php echo L::login_email;?></label>
						<input class="form-control" type="email" name="email" placeholder="<?php echo L::login_email;?>" required autofocus>
						<div class="invalid-feedback"><?php echo L::account_email_invalid;?></div>
					</div>
					<button type="submit" name="password_reset" class="btn btn-primary btn-block text-uppercase"><?php echo L::reset_button;?></button>
				</div>
			</form>
		</div>
	</div>
</div>
