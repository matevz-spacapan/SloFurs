<?php
class Account extends Connection{
	public function index(){
		$account=$this->getSessionAcc();
		if($account!=null){
			header('location: '.URL.'account/contact');
		}
		else{
			header('location: '.URL.'login'); //go to login page if not logged in	
		}
	}
	// Contact info
	public function contact(){
		$account=$this->getSessionAcc();
		if($account==null){
			header('location: '.URL.'login');
		}
		require 'app/sites/global/header.php';
		require 'app/sites/global/sidebar.php';
		require 'app/sites/global/alerts.php';
		require 'app/sites/'.THEME.'/account/contact.php';
		require 'app/sites/global/footer.php';
	}
	// Password
	public function password(){
		$account=$this->getSessionAcc();
		if($account==null){
			header('location: '.URL.'login');
		}
		require 'app/sites/global/header.php';
		require 'app/sites/global/sidebar.php';
		require 'app/sites/global/alerts.php';
		require 'app/sites/'.THEME.'/account/password.php';
		require 'app/sites/global/footer.php';
	}
	// Update information of the account
	public function update($action){
		$account_model=$this->loadSQL('AccountModel');
		switch($action){
			case 1:
				// Change email
				$change_email=$account_model->changeEmail($_POST['newemail'], $_POST['verifypassword']);
				$_SESSION['alert']=$change_email;
				header('location: '.URL.'account/contact');
				break;
			case 2:
				// Change profile picture
				$_SESSION['alert']='iUpdate 2';
				header('location: '.URL.'account/contact');
				break;
			case 3:
				// Update account info
				$_SESSION['alert']='iUpdate 3';
				header('location: '.URL.'account/contact');
				break;
			case 4:
				// Change password
				$_SESSION['alert']='sUpdate 4';
				header('location: '.URL.'account/password');
				break;
			default:
				// code...
				$_SESSION['alert']='dOops, something went wrong [unknown action requested]';
				header('location: '.URL.'account/contact');
				break;
		}
	}
}