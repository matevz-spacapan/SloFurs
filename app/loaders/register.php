<?php
class Register extends Connection{
	public function index(){
		$account=$this->getSessionAcc();
		$reg_model=$this->loadSQL('RegModel');
		if($account==null){
			$_SESSION['alert']="iYou need to be logged in to register for events.";
			header('location: '.URL.'login');
		}
		//check if personal info is complete, show warning if not.
		$complete_profile=$reg_model->checkProfile();
		//view registered, upcoming and user's past registered events
		$rEvents=$reg_model->getREvents(); //registered
		$cEvents=$reg_model->getCEvents(); //upcoming
		$pEvents=$reg_model->getPEvents(); //past
		require 'app/sites/global/header.php';
		require 'app/sites/global/alerts.php';
		require 'app/sites/'.THEME.'/reg/view.php';
		require 'app/sites/global/footer.php';
	}
	// Register for a new event
	public function new(){
		$account=$this->getSessionAcc();
		$reg_model=$this->loadSQL('RegModel');
		if($account==null){
			$_SESSION['alert']="iYou need to be logged in to register for events.";
			header('location: '.URL.'login');
		}
		$id=$_GET["id"]; //event ID
		//check if not already regged for evt
		if(!$reg_model->registered($id, 'event_id')||!$reg_model->exists($id)){
			header('location: '.URL.'register');
		}
		$event=$reg_model->newEvent($id);
		require 'app/sites/global/header.php';
		require 'app/sites/global/alerts.php';
		require 'app/sites/'.THEME.'/reg/new.php';
		require 'app/sites/global/footer.php';
	}
	// Edit an already registered event
	public function edit(){
		$account=$this->getSessionAcc();
		$reg_model=$this->loadSQL('RegModel');
		if($account==null){
			$_SESSION['alert']="iYou need to be logged in to register for events.";
			header('location: '.URL.'login');
		}
		$id=$_GET["id"];
		//check if actually regged for evt
		if($reg_model->registered($id, 'id')){
			header('location: '.URL.'register');
		}
		require 'app/sites/global/header.php';
		require 'app/sites/global/alerts.php';
		require 'app/sites/'.THEME.'/reg/new.php'; //or /edit?
		require 'app/sites/global/footer.php';
	}
}