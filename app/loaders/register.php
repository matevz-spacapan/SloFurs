<?php
class Register extends Connection{
	public function index(){
		$account=$this->getSessionAcc();
		$reg_model=$this->loadSQL('RegModel');
		$account_model=$this->loadSQL('AccountModel');
		/*if($account==null){
			$_SESSION['alert']=L::alerts_i_loggedIn;
			header('location: '.URL.'login');
		}*/
		//check if personal info is complete, show warning if not.
		$complete_profile=true;
		if($account!=null){
			$complete_profile=$reg_model->checkProfile();
		}
		//view registered, upcoming and user's past registered events
		if($account!=null){
			$rEvents=$reg_model->getREvents(); //registered
			$cEvents=$reg_model->getCEvents(); //upcoming
			$pEvents=$reg_model->getPEvents(); //past
		}
		else{
			$cEvents=$reg_model->getCEvents(true); //upcoming with no acc
		}
		if(isset($_POST['update_personal_info'])){
			$_SESSION['alert']=$account_model->updateProfile($_POST['fname'], $_POST['lname'], $_POST['address'], $_POST['address2'], $_POST['city'], $_POST['postcode'], $_POST['country'], $_POST['phone'], $_POST['dob'], $_POST['gender'], $_POST['language']);
			header('location: '.URL.'register');
		}
		else{
			require 'app/sites/global/header.php';
			require 'app/sites/global/alerts.php';
			require 'app/sites/'.THEME.'/reg/view.php';
			require 'app/sites/global/footer.php';
		}
	}
	// Register for a new event
	public function new(){
		$new_reg=true;
		$account=$this->getSessionAcc();
		$reg_model=$this->loadSQL('RegModel');
		/*if($account==null){
			$_SESSION['alert']=L::alerts_i_loggedIn;
			header('location: '.URL.'login');
		}*/
		$id=$_GET["id"]; //event ID
		//check if already regged for evt and evt exists
		if($account!=null&&!$reg_model->registered($id, 'event_id')){
			if($reg_model->exists($id)){
				header('location: '.URL.'register/edit?id='.$reg_model->registered($id, 'event_id', false)->id);
			}
			else{
				header('location: '.URL.'register');
			}
		}
		//check if profile is complete
		/*if($account!=null&&!$reg_model->checkProfile()){
			$_SESSION['alert']=L::alerts_d_personal;
			header('location: '.URL.'register');
		}*/
		if(!$reg_model->viewable($_GET['id'])){
			header('location: '.URL.'register');
		}
		//if submitting the registration form
		if(isset($_POST['new_registration'])){
			$_SESSION['alert']=$reg_model->doReg($_GET['id'],$_POST);
			header('location: '.URL.'register');
		}
		else{
			$event=$reg_model->newReg($id);
			$evt_id=$event->id;
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
			$_SESSION['alert']=L::alerts_i_loggedIn;
			header('location: '.URL.'login');
		}
		$id=$_GET["id"];
		$event=$reg_model->existingReg($id);
		$evt_id=$event->event_id;
		//check if actually regged for evt
		if($reg_model->registered($id, 'id')){
			header('location: '.URL.'register');
		}
		//if submitting the registration form
		if(isset($_POST['edit_registration'])){
			$_SESSION['alert']=$reg_model->editReg($evt_id, $_POST);
			header('location: '.URL.'register/edit?id='.$id);
		}
		//if submitting a new car share
		elseif(isset($_POST['new_car_share'])){
			$_SESSION['alert']=$reg_model->newCarShare($evt_id, $_POST['direction'], $_POST['passengers'], $_POST['outbound'], $_POST['price'], $_POST['description']);
			header('location: '.URL.'register/edit?id='.$id);
		}
		elseif(isset($_POST['edit_car_share'])){
			$_SESSION['alert']=$reg_model->editCarShare($_GET['carshare'], $_POST['direction'], $_POST['passengers'], $_POST['outbound'], $_POST['price'], $_POST['description']);
			header('location: '.URL.'register/edit?id='.$id);
		}
		elseif(isset($_POST['delete_car_share'])){
			$_SESSION['alert']=$reg_model->deleteCarShare($_GET['carshare']);
			header('location: '.URL.'register/edit?id='.$id);
		}
		else{
			//$event=$reg_model->existingReg($id);
			require 'app/sites/global/header.php';
			require 'app/sites/global/alerts.php';
			require 'app/sites/'.THEME.'/reg/form.php';
			require 'app/sites/global/footer.php';
		}
	}
	public function pay(){
		$account=$this->getSessionAcc();
		$inv_model=$this->loadSQL('InvoiceModel');
		$invoice=$inv_model->getInvoice($_GET['id']);
		if($invoice==null){
			header('location: '.URL.'register');
		}
		else{
			if(isset($_POST['download'])){
				$inv_model->download($_GET['id']);
			}
			require 'app/sites/global/header.php';
			require 'app/sites/global/alerts.php';
			require 'app/sites/'.THEME.'/reg/invoice.php';
			require 'app/sites/global/footer.php';
		}
	}
}
