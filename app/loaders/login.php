<?php
class LogIn extends Connection{
	public function index(){
		$account=$this->getSessionAcc();
		if($account!=null){
			header('location: '.URL); //go to home page if logged in
		}
		else{
			require 'app/sites/global/header.php';
			//require 'app/sites/global/topnavbar.php';
			require 'app/sites/global/alerts.php';
			require 'app/sites/'.THEME.'/login.php';
			require 'app/sites/global/footer.php';
		}
	}
	// Log In Account
	public function logInAcc() {
		if(isset($_POST['log_in_acc'])){
			$log_in_model=$this->loadSQL('LogInModel');
			$log_in_account=$log_in_model->loginAcc($_POST['email'], $_POST['password']);
			if(isset($log_in_account)&&$log_in_account!=null){
				$_SESSION['alert']=$log_in_account;
				header('location: '.URL.'login');
			}
			else{
				header('location: '.URL);
			}
		}
	}
	// Activate Account
	public function activateAcc($email, $activate_token){
		if(isset($email)&&isset($activate_token)){
			$log_in_model=$this->loadSQL('LogInModel');
			$activate_account=$log_in_model->activateAcc($email, $activate_token);
			$_SESSION['alert']=$activate_account;
			header('location: '.URL.'login');
		}
	}
	// Logout
	public function logout(){
		$log_in_model=$this->loadSQL('LogInModel');
		$log_in_model->logoutAcc();
		header('location: '.URL);
	}
}