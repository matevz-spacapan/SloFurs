<?php
class SignUp extends Connection{
	public function index(){
		$account = $this->getSessionAcc();
		if($account!=null){
			header('location: '.URL); //go to home page if logged in
		}
		else{
			require 'app/sites/global/header.php';
			require 'app/sites/global/alerts.php';
			require 'app/sites/'.THEME.'/signup.php';
			require 'app/sites/global/footer.php';
		}
	}
	// Sign Up Account
	public function signUpAcc(){
		if(isset($_POST['sign_up_acc'])){
			$sign_up_model=$this->loadSQL('SignUpModel');
			$sign_up_account=$sign_up_model->signupAcc($_POST['username'], $_POST['email'], $_POST['password'], URL);
			if(isset($sign_up_account)&&$sign_up_account!=null){
				$_SESSION['alert']=$sign_up_account;
				header('location: '.URL.'signup');
			}
			else{
				header('location: '.URL.'login');
			}
		}
	}
}