<?php
class SignUp extends Connection{
	public function index(){
		$account=$this->getSessionAcc();
		if($account!=null){
			echo '<script>window.history.back();</script>';
		}
		//sign up account
		elseif(isset($_POST['sign_up_acc'])){
			$sign_up_model=$this->loadSQL('SignUpModel');
			$_SESSION['alert']=$sign_up_model->signupAcc($_POST['username'], filter_var($_POST['email'], FILTER_SANITIZE_EMAIL), $_POST['password'], $_POST['g-recaptcha-response'], $_POST['newsletter']);
			echo '<script>window.history.back();</script>';
		}
		else{
			echo '<script>window.history.back();</script>';
		}
	}
}
