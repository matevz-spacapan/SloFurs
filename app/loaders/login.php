<?php
class LogIn extends Connection{
	public function index(){
		$account=$this->getSessionAcc();
		if($account!=null){
			header('location: '.URL.'account');
		}
		//log-in
		elseif(isset($_POST['log_in_acc'])){
			$log_in_model=$this->loadSQL('LogInModel');
			$log_in_account=$log_in_model->login($_POST['email'], $_POST['password']);
			$_SESSION['alert']=$log_in_account;
			header('location: '.URL.'account');
		}
		else{
			require 'app/sites/global/header.php';
			require 'app/sites/global/alerts.php';
			require 'app/sites/'.THEME.'/login.php';
			require 'app/sites/global/footer.php';
		}
	}
	// Activate Account
	public function activate($email=null, $activate_token=null){
		if(isset($email)&&isset($activate_token)){
			$log_in_model=$this->loadSQL('LogInModel');
			$activate_account=$log_in_model->activate($email, $activate_token);
			$_SESSION['alert']=$activate_account;
			header('location: '.URL.'account');
		}
		else{
			$_SESSION['alert']='dInvalid account activation request.';
			header('location: '.URL.'login');
		}
	}
	// Logout
	public function logout(){
		$log_in_model=$this->loadSQL('LogInModel');
		$log_in_model->logout();
		header('location: '.URL);
	}
}
