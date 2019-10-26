<?php
class LogIn extends Connection{
	public function index(){
		$account=$this->getSessionAcc();
		if($account!=null){
			header('location: '.URL.'account');
		}
		//log-in
		elseif(isset(strip_tags($_POST['log_in_acc']))){
			$log_in_model=$this->loadSQL('LogInModel');
			$_SESSION['alert']=$log_in_model->login(strip_tags($_POST['email']), strip_tags($_POST['password']));
			if(isset($_SESSION['account'])){
				header('location: '.URL);
			}
			else{
				header('location: '.URL.'login');
			}
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
			$_SESSION['alert']=$log_in_model->activate(strip_tags($email), strip_tags($activate_token));
			header('location: '.URL.'account');
		}
		else{
			$_SESSION['alert']=L::alerts_d_activate;
			header('location: '.URL.'login');
		}
	}

	//Password reset
	public function forgot($email=null, $token=null){
		$account=$this->getSessionAcc();
		if($account!=null){
			header('location: '.URL.'account/password');
		}
		$log_in_model=$this->loadSQL('LogInModel');
		if(isset(strip_tags($_POST['password_reset']))){
			$_SESSION['alert']=$log_in_model->passwordReset1(strip_tags($_POST['email']));
			header('location: '.URL.'login');
		}
		elseif(isset($email)&&isset($token)){
			$account=$this->getSessionAcc();
			//check validity of submitted data
			$valid=$log_in_model->passwordReset2(strip_tags($email), strip_tags($token));
			if(!$valid){
				$_SESSION['alert']=L::alerts_d_pw;
				header('location: '.URL.'login');
			}
			else{
				require 'app/sites/global/header.php';
				require 'app/sites/global/alerts.php';
				require 'app/sites/'.THEME.'/password_reset_2.php';
				require 'app/sites/global/footer.php';
			}
		}
		elseif(isset(strip_tags($_POST['finish_reset']))){
			$_SESSION['alert']=$log_in_model->passwordReset3(strip_tags($_POST['password']));
			header('location: '.URL.'login');
		}
		else{
			$account=$this->getSessionAcc();
			require 'app/sites/global/header.php';
			require 'app/sites/global/alerts.php';
			require 'app/sites/'.THEME.'/password_reset.php';
			require 'app/sites/global/footer.php';
		}
	}
	// Logout
	public function logout(){
		$log_in_model=$this->loadSQL('LogInModel');
		$log_in_model->logout();
		header('location: '.URL);
	}
}
