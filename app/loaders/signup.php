<?php
class SignUp extends Connection{
	public function index(){
		$account=$this->getSessionAcc();
		if($account!=null){
			header('location: '.URL);
		}
		//sign up account
		elseif(isset($_POST['sign_up_acc'])){
			$sign_up_model=$this->loadSQL('SignUpModel');
			$_SESSION['alert']=$sign_up_model->signupAcc(strip_tags($_POST['username']), filter_var($_POST['email'], FILTER_SANITIZE_EMAIL), strip_tags($_POST['password']), strip_tags($_POST['g-recaptcha-response']));
			if(strpos($_SESSION['alert'], 'd')===0){
				header('location: '.URL.'signup');
			}
			else{
				header('location: '.URL.'login');
			}
		}
		else{
			require 'app/sites/global/header.php';
			require 'app/sites/global/alerts.php';
			require 'app/sites/'.THEME.'/signup.php';
			require 'app/sites/global/footer.php';
		}
	}
	public function resend(){
		$account=$this->getSessionAcc();
		if($account!=null){
			header('location: '.URL);
		}
		elseif(isset($_POST['send_email'])){
			$sign_up_model=$this->loadSQL('SignUpModel');
			$_SESSION['alert']=$sign_up_model->resendEmail(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL));
			header('location: '.URL.'login');
		}
		else{
			require 'app/sites/global/header.php';
			require 'app/sites/global/alerts.php';
			require 'app/sites/'.THEME.'/resend_activation.php';
			require 'app/sites/global/footer.php';
		}
	}
}
