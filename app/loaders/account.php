<?php
class Account extends Connection{
	public function index(){
		$account=$this->getSessionAcc();
		if($account!=null){
			header('location: '.URL.'account/contact');
		}
		else{
			header('location: '.URL.'login');
		}
	}
	// Contact info page
	public function contact(){
		$account=$this->getSessionAcc();
		if($account==null){
			header('location: '.URL.'login');
		}
		$account_model=$this->loadSQL('AccountModel');
		if(isset($_POST['change_email'])){
			$_SESSION['alert']=$account_model->changeEmail(strip_tags($_POST['newemail']), strip_tags($_POST['verifypassword']));
			header('location: '.URL.'account/contact');
		}
		elseif(isset($_POST{'delete_pfp'})){
			$_SESSION['alert']=$account_model->deletePFP();
			header('location: '.URL.'account/contact');
		}
		elseif(isset($_FILES['image'])){
			$_SESSION['alert']=$account_model->changePFP($_FILES['image']);
			header('location: '.URL.'account/contact');
		}
		elseif(isset($_POST['update_personal_info'])){
			$_SESSION['alert']=$account_model->updateProfile(strip_tags($_POST['fname']), strip_tags($_POST['lname']), strip_tags($_POST['address']), strip_tags($_POST['address2']), strip_tags($_POST['city']), strip_tags($_POST['postcode']), strip_tags($_POST['country']), strip_tags($_POST['phone']), strip_tags($_POST['dob']), strip_tags($_POST['gender']), strip_tags($_POST['language']));
			header('location: '.URL.'account/contact');
		}
		elseif(isset($_POST['delete_personal_info'])){
			$_SESSION['alert']=$account_model->deleteProfile();
			header('location: '.URL.'account/contact');
		}
		else{
			require 'app/sites/global/header.php';
			require 'app/sites/'.THEME.'/account/sidebar.php';
			require 'app/sites/global/alerts.php';
			require 'app/sites/'.THEME.'/account/contact.php';
			require 'app/sites/global/footer.php';
		}
	}
	// Password page
	public function password(){
		$account=$this->getSessionAcc();
		$account_model=$this->loadSQL('AccountModel');
		if($account==null){
			header('location: '.URL.'login');
		}
		if(isset($_POST['change_password'])){
			$_SESSION['alert']=$account_model->changePw(strip_tags($_POST['oldpassword']), strip_tags($_POST['newpassword']));
			header('location: '.URL.'account/password');
		}
		else{
			require 'app/sites/global/header.php';
			require 'app/sites/'.THEME.'/account/sidebar.php';
			require 'app/sites/global/alerts.php';
			require 'app/sites/'.THEME.'/account/password.php';
			require 'app/sites/global/footer.php';
		}
	}
	// Fursuits page
	public function fursuit($id=null){
		$account=$this->getSessionAcc();
		if($account==null){
			header('location: '.URL.'login');
		}
		$fursuit_model=$this->loadSQL('FursuitModel');
		if(isset($_POST['new_fursuit'])){
			$_SESSION['alert']=$fursuit_model->addFursuit(strip_tags($_POST['suitname']), strip_tags($_POST['animal']), strip_tags($_POST['in_use']), $_FILES['image']);
			header('location: '.URL.'account/fursuit');
		}
		elseif(isset($_POST['edit_fursuit'])){
				$_SESSION['alert']=$fursuit_model->editFursuit(strip_tags($_GET['id']), strip_tags($_POST['suitname']), strip_tags($_POST['animal']), strip_tags($_POST['in_use']), $_FILES['image']);
				header('location: '.URL.'account/fursuit');
		}
		elseif(isset($_POST['delete_fursuit'])){
			$_SESSION['alert']=$fursuit_model->delFursuit(strip_tags($_GET['id']));
			header('location: '.URL.'account/fursuit');
		}
		else{
			$fursuits=$fursuit_model->getAccFursuits($_SESSION['account']);
			require 'app/sites/global/header.php';
			require 'app/sites/'.THEME.'/account/sidebar.php';
			require 'app/sites/global/alerts.php';
			require 'app/sites/'.THEME.'/account/fursuit.php';
			require 'app/sites/global/footer.php';
		}
	}
}
