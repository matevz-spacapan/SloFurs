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
		$new_reg=true;
		$account=$this->getSessionAcc();
		$reg_model=$this->loadSQL('RegModel');
		if($account==null){
			$_SESSION['alert']="iYou need to be logged in to register for events.";
			header('location: '.URL.'login');
		}
		$id=$_GET["id"]; //event ID
		//check if already regged for evt and evt exists
		if(!$reg_model->registered($id, 'event_id')||!$reg_model->exists($id)){
			header('location: '.URL.'register');
		}
		//check if profile is complete
		if(!$reg_model->checkProfile()){
			$_SESSION['alert']="dComplete your personal information before registering.";
			header('location: '.URL.'register');
		}
		//if submitting the registration form
		if(isset($_POST['new_registration'])){
			$_SESSION['alert']=$reg_model->doReg($_GET['id'],$_POST);
			header('location: '.URL.'register');
		}
		else{
			$event=$reg_model->newReg($id);
			require 'app/sites/global/header.php';
			require 'app/sites/'.THEME.'/reg/form.php';
			require 'app/sites/global/footer.php';
		}
	}
	// Edit an already registered event
	public function edit(){
		$new_reg=false;
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
		//if submitting the registration form
		if(isset($_POST['edit_registration'])){
			$_SESSION['alert']=$reg_model->editReg($_GET['id'],$_POST);
			header('location: '.URL.'register/edit?id='.$id);
		}
		else{
			$event=$reg_model->existingReg($id);
			require 'app/sites/global/header.php';
			require 'app/sites/global/alerts.php';
			require 'app/sites/'.THEME.'/reg/form.php'; //or /edit?
			require 'app/sites/global/footer.php';
		}
	}
}