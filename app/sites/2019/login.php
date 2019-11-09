<div class="modal fade" id="loginModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"><?php echo L::login_h;?></h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<form action="<?php echo URL; ?>login" method="post" class="needs-validation" novalidate>
				<div class="modal-body">
					<div class="form-group">
						<label for="email"><?php echo L::login_email;?></label>
						<input class="form-control" type="email" name="email" placeholder="<?php echo L::login_email;?>" required autofocus>
						<div class="invalid-feedback"><?php echo L::account_email_invalid;?></div>
					</div>
					<div class="form-group">
						<label for="password"><?php echo L::login_pw;?></label>
						<input class="form-control" type="password" name="password" placeholder="<?php echo L::login_pwP;?>" required>
						<div class="invalid-feedback"><?php echo L::account_fillOutField;?></div>
					</div>
					<button type="submit" name="log_in_acc" class="btn btn-primary btn-block text-uppercase"><?php echo L::login_button;?></button><br>
					<div class="text-center">
						<i class="fas fa-lock-alt"></i> <button type="button" data-toggle="modal" data-target="#forgotModal" data-dismiss="modal" class="btn btn-link"><?php echo L::login_forgot;?></button><br>
						<?php echo L::login_noAcc;?> <button type="button" data-toggle="modal" data-target="#signupModal" data-dismiss="modal" class="btn btn-link"><?php echo L::login_noAccButton;?></button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<script type="text/javascript">
(function() {
	'use strict';
	window.addEventListener('load', function() {
		var forms = document.getElementsByClassName('needs-validation');
		var validation = Array.prototype.filter.call(forms, function(form) {
			form.addEventListener('submit', function(event) {
				if (form.checkValidity() === false) {
					event.preventDefault();
					event.stopPropagation();
				}
				form.classList.add('was-validated');
			}, false);
		});
	}, false);
})();
</script>
