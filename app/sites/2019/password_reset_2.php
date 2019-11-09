<div class="container-fluid text-center" style="margin-top:50px">
	<div style=" width: 100%; max-width: 330px; margin: auto;">
		<h2>Ponastavljanje gesla</h2>
		<form action="<?php echo URL; ?>login/forgot" method="post" class="needs-validation" novalidate>
			<div class="form-group">
				<label for="email"><?php echo L::reset_email;?></label>
				<?php $_SESSION['reset_email']=strip_tags($email);?>
				<input class="form-control" type="email" name="email" value="<?php echo $email;?>" disabled>
				<div class="invalid-feedback"><?php echo L::account_email_invalid;?></div>
			</div>
			<div class="form-group">
				<label for="password"><?php echo L::reset_pw;?></label>
				<input class="form-control" type="password" name="password" placeholder="<?php echo L::reset_pwP;?>" pattern="^(?=.{8,}$)(?=.*[a-zA-Z])(?=.*[0-9\W_]).*$" title="<?php echo L::signup_pwT;?>" required>
				<small class="form-text text-muted"><?php echo L::account_password_newI;?></small>
				<div class="invalid-feedback"><?php echo L::account_fillOutField;?></div>
			</div>
			<br>
			<div class="text-center">
				<button type="submit" name="finish_reset" class="btn btn-primary"><?php echo L::reset_save;?></button>
			</div>
		</form>
	</div>
</div>
<script>
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
