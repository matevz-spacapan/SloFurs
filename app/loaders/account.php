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
}