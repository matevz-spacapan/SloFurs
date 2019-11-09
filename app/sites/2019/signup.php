<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<div class="modal fade" id="signupModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"><?php echo L::signup_h;?></h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<form action="<?php echo URL; ?>signup" method="post" class="needs-validation" novalidate>
				<div class="modal-body">
					<div class="form-group">
						<label for="email"><?php echo L::signup_email;?></label>
						<input class="form-control" type="email" name="email" placeholder="<?php echo L::signup_email;?>" required autofocus>
						<div class="invalid-feedback"><?php echo L::account_email_invalid;?></div>
					</div>
					<div class="form-group">
						<label for="username" title="<?php echo L::signup_usernameI;?>"><?php echo L::signup_username;?> <i class="far fa-info-circle"></i></label>
						<input class="form-control" type="text" name="username" placeholder="<?php echo L::signup_usernameP;?>" required>
						<div class="invalid-feedback"><?php echo L::account_fillOutField;?></div>
					</div>
					<div class="form-group">
						<label for="password"><?php echo L::signup_pw;?></label>
						<input class="form-control" type="password" name="password" placeholder="<?php echo L::signup_pwP;?>" pattern="^(?=.{8,}$)(?=.*[a-zA-Z])(?=.*[0-9\W_]).*$" title="<?php echo L::signup_pwT;?>" required>
						<small class="form-text text-muted"><?php echo L::account_password_newI;?></small>
						<div class="invalid-feedback"><?php echo L::account_fillOutField;?></div>
					</div>
					<div class="form-group">
						<div class="custom-control custom-checkbox">
							<input class="custom-control-input" type="checkbox" id="privacy" required>
							<label for="privacy" class="custom-control-label"><?php echo L::signup_privacy1;?> <a href="<?php echo URL;?>privacy" target="_blank"><?php echo L::signup_privacy3;?> <i class="far fa-external-link"></i></a></label>
						</div>
						<div class="custom-control custom-checkbox">
							<input class="custom-control-input" type="checkbox" id="newsletter" name="newsletter" value="1">
							<label for="newsletter" class="custom-control-label"><?php echo L::signup_newsletter1;?> <small class="text-muted"><?php echo L::signup_newsletter2;?></small></label>
						</div>
					</div>
					<div class="form-group">
						<div class="g-recaptcha" data-sitekey="<?php echo $RECAPTCHA_PUBLIC;?>"></div>
					</div>
					<button type="submit" name="sign_up_acc" class="btn btn-primary btn-block text-uppercase"><?php echo L::signup_register;?></button><br>
				</div>
			</form>
		</div>
	</div>
</div>
