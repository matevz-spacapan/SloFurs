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
			$_SESSION['alert']=$sign_up_model->signupAcc($_POST['username'], $_POST['email'], $_POST['password']);
			header('location: '.URL.'login');
		}
		else{
			require 'app/sites/global/header.php';
			require 'app/sites/global/alerts.php';
			require 'app/sites/'.THEME.'/signup.php';
			require 'app/sites/global/footer.php';
		}
	}
}
