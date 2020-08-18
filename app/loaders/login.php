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
			$_SESSION['alert']=$log_in_model->login(strip_tags($_POST['email']), strip_tags($_POST['password']));
			if(isset($_SESSION['account'])){
				echo '<script>window.history.back();</script>';
			}
			else{
				echo '<script>window.history.back();</script>';
			}
		}
		else{
			echo '<script>window.history.back();</script>';
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
			header('location: '.URL);
		}
	}

	//Password reset
	public function forgot($email=null, $token=null){
		$account=$this->getSessionAcc();
		if($account!=null){
			header('location: '.URL.'account/password');
		}
		$log_in_model=$this->loadSQL('LogInModel');
		if(isset($_POST['password_reset'])){
			$_SESSION['alert']=$log_in_model->passwordReset1(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL));
			echo '<script>window.history.back();</script>';
		}
		elseif(isset($email)&&isset($token)){
			$account=$this->getSessionAcc();
			//check validity of submitted data
			$valid=$log_in_model->passwordReset2(filter_var($email, FILTER_SANITIZE_EMAIL), strip_tags($token));
			if(!$valid){
				$_SESSION['alert']=L::alerts_d_pw;
				header('location: '.URL);
			}
			else{
				require 'app/sites/global/header.php';
				require 'app/sites/global/alerts.php';
				require 'app/sites/'.THEME.'/password_reset_2.php';
	    	echo "<script>document.title='".L::title_passwordreset."';</script>";
				require 'app/sites/global/footer.php';
			}
		}
		elseif(isset($_POST['finish_reset'])){
			$_SESSION['alert']=$log_in_model->passwordReset3(strip_tags($_POST['password']));
			header('location: '.URL);
		}
		else{
			echo '<script>window.history.back();</script>';
		}
	}
	// Logout
	public function logout(){
		$log_in_model=$this->loadSQL('LogInModel');
		$log_in_model->logout();
		echo '<script>window.history.back();</script>';
	}
}
