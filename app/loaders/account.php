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
		//TODO when a user changes something, update it before loading the website
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
		//TODO when a user changes something, update it before loading the website
		require 'app/sites/'.THEME.'/account/password.php';
		require 'app/sites/global/footer.php';
	}
	// Update information of the account
	public function update($action){
		switch($action){
			case 1:
				// code... remember to redirect to correct page after updating
				$_SESSION['alert']='iUpdate 1';
				header('location: '.URL.'account/contact');
				break;
			case 2:
				// code...
				$_SESSION['alert']='iUpdate 2';
				header('location: '.URL.'account/contact');
				break;
			case 3:
				// code...
				$_SESSION['alert']='iUpdate 3';
				header('location: '.URL.'account/contact');
				break;
			case 4:
				// code...
				$_SESSION['alert']='sUpdate 4';
				header('location: '.URL.'account/password');
				break;
			default:
				// code...
				$_SESSION['alert']='iUpdate default';
				header('location: '.URL.'account/contact');
				break;
		}
	}
}