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
		require 'app/sites/global/header.php';
		require 'app/sites/global/sidebar.php';
		require 'app/sites/global/alerts.php';
		require 'app/sites/'.THEME.'/account/contact.php';
		require 'app/sites/global/footer.php';
	}
	// Password page
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
	// Fursuits page
	public function fursuit($id=null){
		$account=$this->getSessionAcc();
		if($account==null){
			header('location: '.URL.'login');
		}
		$fursuit_model=$this->loadSQL('FursuitModel');
		$fursuits=$fursuit_model->getAccFursuits($_SESSION['account']);
		require 'app/sites/global/header.php';
		require 'app/sites/global/sidebar.php';
		require 'app/sites/global/alerts.php';
		require 'app/sites/'.THEME.'/account/fursuit.php';
		require 'app/sites/global/footer.php';
	}
	// Update information of the account
	public function update($action, $id=null){
		$account=$this->getSessionAcc();
		if($account==null){
			header('location: '.URL.'login');
		}
		$account_model=$this->loadSQL('AccountModel');
		$fursuit_model=$this->loadSQL('FursuitModel');
		switch($action){
			case 1:
				// Change email
				$change=$account_model->changeEmail($_POST['newemail'], $_POST['verifypassword']);
				$_SESSION['alert']=$change;
				header('location: '.URL.'account/contact');
				break;
			case 2:
				// Change profile picture
				$change=$account_model->changePFP($_FILES['image']);
				$_SESSION['alert']=$change;
				header('location: '.URL.'account/contact');
				break;
			case 3:
				// Update account info
				$change=$account_model->updateProfile($_POST['fname'], $_POST['lname'], $_POST['address'], $_POST['address2'], $_POST['city'], $_POST['postcode'], $_POST['country'], $_POST['phone'], $_POST['dob'], $_POST['gender']);
				$_SESSION['alert']=$change;
				if($id!=null){
					header('location: '.URL.'register');
				}
				else{
					header('location: '.URL.'account/contact');
				}
				break;
			case 4:
				// Change password
				$change=$account_model->changePw($_POST['oldpassword'], $_POST['newpassword']);
				$_SESSION['alert']=$change;
				header('location: '.URL.'account/password');
				break;
			case 5:
				//Add fursuit
				$change=$fursuit_model->addFursuit($_POST['suitname'], $_POST['animal'], $_POST['in_use'], $_FILES['image']);
				$_SESSION['alert']=$change;
				header('location: '.URL.'account/fursuit');
				break;
			case 6:
				//Edit/delete fursuit
				if(isset($_POST['edit_fursuit'])){
					$change=$fursuit_model->editFursuit($id, $_POST['suitname'], $_POST['animal'], $_POST['in_use'], $_FILES['image']);
					$_SESSION['alert']=$change;
				}
				elseif(isset($_POST['delete_fursuit'])){
					$change=$fursuit_model->delFursuit($id);
					$_SESSION['alert']=$change;
				}
				header('location: '.URL.'account/fursuit');
				break;
			default:
				$_SESSION['alert']='dOops, something went wrong [unknown action requested]';
				header('location: '.URL.'account/contact');
		}
	}
}