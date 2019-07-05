<?php
class Register extends Connection{
	public function index(){
		$account=$this->getSessionAcc();
		if($account==null){
			header('location: '.URL.'login');
		}
		//view registered, upcoming and user's past registered events
		$reg_model=$this->loadSQL('RegModel');
		$rEvents=$reg_model->getREvents(); //registered
		$cEvents=$reg_model->getCEvents(); //upcoming
		$pEvents=$reg_model->getPEvents(); //past
		require 'app/sites/global/header.php';
		require 'app/sites/global/alerts.php';
		require 'app/sites/'.THEME.'/reg/view.php';
		require 'app/sites/global/footer.php';
	}
	// Register for a new event
	public function new($id){
		$account=$this->getSessionAcc();
		if($account==null){
			header('location: '.URL.'login');
		}
		require 'app/sites/global/header.php';
		require 'app/sites/global/alerts.php';
		require 'app/sites/'.THEME.'/reg/new.php';
		require 'app/sites/global/footer.php';
	}
	// Edit an already registered event
	public function edit($id){
		$account=$this->getSessionAcc();
		if($account==null){
			header('location: '.URL.'login');
		}
		require 'app/sites/global/header.php';
		require 'app/sites/global/alerts.php';
		require 'app/sites/'.THEME.'/reg/new.php'; //or /edit?
		require 'app/sites/global/footer.php';
	}
}