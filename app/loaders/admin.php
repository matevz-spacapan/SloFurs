<?php
class Admin extends Connection{
	public function index(){
		$account=$this->getSessionAcc();
		if($account!=null&&$account->status>=ADMIN){
			header('location: '.URL.'admin/dash');
		}
		elseif($account->status<STAFF){
			header('location: '.URL.'404');
		}
		else{
			header('location: '.URL.'login');
		}
	}
	// Dashboard
	public function dash(){
		$account=$this->getSessionAcc();
		if($account==null){
			header('location: '.URL.'login');
		}
		elseif($account->status>=STAFF){
			$dash_model=$this->loadSQL('DashboardModel');
			require 'app/sites/global/header.php';
			require 'app/sites/global/alerts.php';
			require 'app/sites/'.THEME.'/admin/dash.php';
			require 'app/sites/global/footer.php';
		}
		else{
			header('location: '.URL.'404');
		}
	}
	// Users
	public function users(){
		$account=$this->getSessionAcc();
		if($account==null){
			header('location: '.URL.'login');
		}
		elseif($account->status>=SUPER){
			$dash_model=$this->loadSQL('UsersDashModel');
			if(isset($_GET['id'])){
				if(isset($_POST['change_email'])){
					$_SESSION['alert']=$dash_model->changeEmail($_POST['email'], $_GET['id'], false, $_SESSION['account']);
					header('location: '.URL.'admin/users?id='.$_GET['id']);
				}
				elseif(isset($_POST['force_email'])){
					$_SESSION['alert']=$dash_model->changeEmail($_POST['email'], $_GET['id'], true, $_SESSION['account']);
					header('location: '.URL.'admin/users?id='.$_GET['id']);
				}
				elseif(isset($_POST['reset_pw'])){
					$_SESSION['alert']=$dash_model->resetPw($_GET['id'], $_SESSION['account']);
					header('location: '.URL.'admin/users?id='.$_GET['id']);
				}
				elseif(isset($_POST['account_status'])){
					$dash_model->setStatus($_POST['status'], $_GET['id'], $_SESSION['account']);
					header('location: '.URL.'admin/users?id='.$_GET['id']);
				}
				elseif(isset($_POST['delete_pfp'])){
					$dash_model->deletePFP($_GET['id'], $_SESSION['account']);
					header('location: '.URL.'admin/users?id='.$_GET['id'], $_SESSION['account']);
				}
				elseif(isset($_FILES['image'])){
					$_SESSION['alert']=$dash_model->changePFP($_FILES['image'], $_GET['id'], $_SESSION['account']);
					header('location: '.URL.'admin/users?id='.$_GET['id']);
				}
				elseif(isset($_POST['update_personal_info'])){
					$_SESSION['alert']=$dash_model->updateProfile($_POST['fname'], $_POST['lname'], $_POST['address'], $_POST['address2'], $_POST['city'], $_POST['postcode'], $_POST['country'], $_POST['phone'], $_POST['dob'], $_POST['gender'], $_POST['language'], $_GET['id'], $_SESSION['account']);
					header('location: '.URL.'admin/users?id='.$_GET['id']);
				}
				elseif(isset($_POST['delete_personal_info'])){
					$dash_model->deleteProfile($_GET['id'], $_SESSION['account']);
					header('location: '.URL.'admin/users?id='.$_GET['id']);
				}
				elseif(isset($_POST['ban_account'])){
					$dash_model->ban($_GET['id'], $_SESSION['account']);
					header('location: '.URL.'admin/users?id='.$_GET['id']);
				}
				else{
					require 'app/sites/global/header.php';
					require 'app/sites/global/alerts.php';
					require 'app/sites/'.THEME.'/admin/users_edit.php';
					require 'app/sites/global/footer.php';
				}
			}
			else{
				require 'app/sites/global/header.php';
				require 'app/sites/global/alerts.php';
				require 'app/sites/'.THEME.'/admin/users.php';
				require 'app/sites/global/footer.php';
			}
		}
		else{
			header('location: '.URL.'404');
		}
	}

	// Fursuits
	public function fursuits(){
		$account=$this->getSessionAcc();
		if($account==null){
			header('location: '.URL.'login');
		}
		elseif($account->status>=SUPER){
			$dash_model=$this->loadSQL('FursuitDashModel');
			if(isset($_GET['id'])){
				if(isset($_POST['edit_fursuit'])){
						$_SESSION['alert']=$dash_model->editFursuit($_GET['id'], $_POST['suitname'], $_POST['animal'], $_POST['in_use'], $_FILES['image']);
						header('location: '.URL.'admin/fursuits');
				}
				elseif(isset($_POST['delete_fursuit'])){
					$_SESSION['alert']=$dash_model->delFursuit($_GET['id']);
					header('location: '.URL.'admin/fursuits');
				}
			}
			else{
				$fursuits=$dash_model->getFursuits();
				require 'app/sites/global/header.php';
				require 'app/sites/global/alerts.php';
				require 'app/sites/'.THEME.'/admin/fursuits.php';
				require 'app/sites/global/footer.php';
			}
		}
		else{
			header('location: '.URL.'404');
		}
	}
	// Event managing page
	public function event($action=null){
		$account=$this->getSessionAcc();
		$event_model=$this->loadSQL('EventModel');
		if($account==null){
			header('location: '.URL.'login');
		}
		elseif($account->status>=STAFF){
			//create new event
			if(isset($_POST['new_event'])){
				$event_model=$this->loadSQL('EventModel');
				$_SESSION['alert']=$event_model->addEvent($_POST, $_FILES['image']);
				header('location: '.URL.'admin/event');
			}
			//edit event with given ID
			elseif(isset($_POST['edit_event'])){
				$event_model=$this->loadSQL('EventModel');
				$_SESSION['alert']=$event_model->editEvent($_GET['id'], $_POST, $_FILES['image']);
				header('location: '.URL.'admin/event?id='.$_GET['id']);
			}
			//delete event photo with given ID
			elseif(isset($_POST['delete_photo'])){
				$event_model=$this->loadSQL('EventModel');
				$_SESSION['alert']=$event_model->deletePhoto($_GET['id']);
				header('location: '.URL.'admin/event?id='.$_GET['id']);
			}
			//edit confirmed users
			elseif(isset($_POST['confirm_attendees'])){
				$_SESSION['alert']=$event_model->editConfirm($_GET['id'], $_POST);
				header('location: '.URL.'admin/event?id='.$_GET['id']);
			}
			elseif(isset($_POST['export_confirmed'])){
				$event_model->exportForms($_GET['id'], false);
				//header('location: '.URL.'admin/event?id='.$_GET['id']);
			}
			elseif(isset($_POST['export_all'])){
				$event_model->exportForms($_GET['id'], true);
				//header('location: '.URL.'admin/event?id='.$_GET['id']);
			}
			else{
				require 'app/sites/global/header.php';
				require 'app/sites/global/alerts.php';
				//go to new event creation page
				if($action=='new'){
					require 'app/sites/'.THEME.'/admin/sidebar.php';
					require 'app/sites/'.THEME.'/admin/newevent.php';
				}
				//go to edit/view event page
				else{
					if(isset($_GET['id'])){
						$event=$event_model->getEvent($_GET['id']);
						$attendees=$event_model->getRegistered($_GET['id']);
						//$rooms=$event_model->getRooms($_GET['id']);
						$fursuits=$event_model->getFursuits($_GET['id']);
						require 'app/sites/'.THEME.'/admin/event_overview.php';
					}
					//list events
					else{
						$cEvents=$event_model->getCEvents(); //current/upcoming
						$pEvents=$event_model->getPEvents(); //past
						require 'app/sites/'.THEME.'/admin/sidebar.php';
						require 'app/sites/'.THEME.'/admin/event.php';
					}
				}
				require 'app/sites/global/footer.php';
			}
		}
		//user without admin privileges goes to 404
		else{
			header('location: '.URL.'404');
		}
	}
}
