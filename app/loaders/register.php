<?php
class Register extends Connection{
	public function index(){
		$account=$this->getSessionAcc();
		$reg_model=$this->loadSQL('RegModel');
		$account_model=$this->loadSQL('AccountModel');
		//check if personal info is complete, show warning if not.
		$complete_profile=true;
		$cEvents=$reg_model->getCEvents(true);
		if($account!=null){
			$complete_profile=$reg_model->checkProfile();
			$rEvents=$reg_model->getREvents(); //registered
			if($complete_profile){
				$cEvents=$reg_model->getCEvents(); //upcoming with complete profile - show unregistered
			}
		}
		$pEvents=$reg_model->getPEvents(); //past
		if(isset($_POST['update_personal_info'])){
			$_SESSION['alert']=$account_model->updateProfile(strip_tags($_POST['fname']), strip_tags($_POST['lname']), strip_tags($_POST['address']), strip_tags($_POST['address2']), strip_tags($_POST['city']), strip_tags($_POST['postcode']), strip_tags($_POST['country']), strip_tags($_POST['phone']), strip_tags($_POST['dob']), strip_tags($_POST['gender']), strip_tags($_POST['language']));
			header('location: '.URL.'register');
		}
		else{
			$title=L::title_reg_list;
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
		$id=strip_tags($_GET["id"]); //event ID
		//check if already regged for evt and evt exists
		if($account!=null&&!$reg_model->registered($id, 'event_id')){
			if($reg_model->exists($id)){
				header('location: '.URL.'register/edit?id='.$reg_model->registered($id, 'event_id', false)->id);
			}
			else{
				header('location: '.URL.'register');
			}
		}
		if(!$reg_model->viewable(filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT))){
			header('location: '.URL.'register');
		}
		//if submitting the registration form
		if(isset($_POST['new_registration'])){
			$_SESSION['alert']=$reg_model->doReg($id, $_POST);
			header('location: '.URL.'register/new?id='.$id);
		}
		else{
			$event=$reg_model->newReg($id);
			$evt_id=$event->id;
			$title=L::title_reg_new.$event->name;
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
			header('location: '.URL);
		}
		$id=strip_tags($_GET["id"]);
		$event=$reg_model->existingReg($id);
		$evt_id=$event->event_id;
		//check if actually regged for evt
		if($reg_model->registered($id, 'id')){
			header('location: '.URL.'register');
		}
		//if submitting the registration form
		if(isset($_POST['edit_registration'])){
			$_SESSION['alert']=$reg_model->editReg($id, $_POST);
			header('location: '.URL.'register/edit?id='.$id);
		}
		//if submitting a new car share
		elseif(isset($_POST['new_car_share'])){
			$_SESSION['alert']=$reg_model->newCarShare($evt_id, strip_tags($_POST['direction']), filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT), strip_tags($_POST['outbound']), filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT), strip_tags($_POST['description']));
			header('location: '.URL.'register/edit?id='.$id);
		}
		elseif(isset($_POST['edit_car_share'])){
			$_SESSION['alert']=$reg_model->editCarShare(strip_tags($_GET['carshare']), strip_tags($_POST['direction']), filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT), strip_tags($_POST['outbound']), filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT), strip_tags($_POST['description']));
			header('location: '.URL.'register/edit?id='.$id);
		}
		elseif(isset($_POST['delete_car_share'])){
			$_SESSION['alert']=$reg_model->deleteCarShare(strip_tags($_GET['carshare']));
			header('location: '.URL.'register/edit?id='.$id);
		}
		else{
			//do Stripe payment setup and checks
			require 'app/loaders/support/stripe.php';
			//load site
			$title=L::title_reg_edit.$event->name;
			require 'app/sites/global/header.php';
			require 'app/sites/global/alerts.php';
			require 'app/sites/'.THEME.'/reg/form.php';
			require 'app/sites/global/footer.php';
		}
	}
}
