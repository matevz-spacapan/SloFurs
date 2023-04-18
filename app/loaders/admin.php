<?php
class Admin extends Connection{
	public function index(){
		$account=$this->getSessionAcc();
		if($account!=null&&$account->status>=STAFF){
			header('location: '.URL.'admin/dash');
		}
		elseif($account->status<STAFF){
			header('location: '.URL.'404');
		}
		else{
			header('location: '.URL);
		}
	}
	// Dashboard
	public function dash(){
		$account=$this->getSessionAcc();
		if($account==null){
			header('location: '.URL);
		}
		elseif($account->status>=STAFF){
			$dash_model=$this->loadSQL('DashboardModel');
			$title=L::title_admin_dash;
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
			header('location: '.URL);
		}
		elseif($account->status>=SUPER){
			$dash_model=$this->loadSQL('UsersDashModel');
			if(isset($_GET['id'])){
				$filtered_id=filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
				if(isset($_POST['change_email'])){
					$_SESSION['alert']=$dash_model->changeEmail(strip_tags($_POST['email']), $filtered_id, false);
					header('location: '.URL.'admin/users?id='.$filtered_id);
				}
				elseif(isset($_POST['force_email'])){
					$_SESSION['alert']=$dash_model->changeEmail(strip_tags($_POST['email']), $filtered_id, true);
					header('location: '.URL.'admin/users?id='.$filtered_id);
				}
				elseif(isset($_POST['reset_pw'])){
					$_SESSION['alert']=$dash_model->resetPw($filtered_id);
					header('location: '.URL.'admin/users?id='.$filtered_id);
				}
				elseif(isset($_POST['account_status'])){
					$dash_model->setStatus(strip_tags($_POST['status']), $filtered_id);
					header('location: '.URL.'admin/users?id='.$filtered_id);
				}
				elseif(isset($_POST['delete_pfp'])){
					$dash_model->deletePFP($filtered_id);
					header('location: '.URL.'admin/users?id='.$filtered_id);
				}
				elseif(isset($_FILES['image'])){
					$_SESSION['alert']=$dash_model->changePFP($_FILES['image'], $filtered_id);
					header('location: '.URL.'admin/users?id='.$filtered_id);
				}
				elseif(isset($_POST['update_personal_info'])){
					$_SESSION['alert']=$dash_model->updateProfile(strip_tags($_POST['fname']), strip_tags($_POST['lname']), strip_tags($_POST['address']), strip_tags($_POST['address2']), strip_tags($_POST['city']), strip_tags($_POST['postcode']), strip_tags($_POST['country']), filter_var($_POST['phone'], FILTER_SANITIZE_NUMBER_INT), strip_tags($_POST['dob']), strip_tags($_POST['gender']), strip_tags($_POST['language']), $filtered_id);
					header('location: '.URL.'admin/users?id='.$filtered_id);
				}
				elseif(isset($_POST['delete_personal_info'])){
                    $_SESSION['alert']=$dash_model->deleteProfile($filtered_id);
					header('location: '.URL.'admin/users?id='.$filtered_id);
				}
				elseif(isset($_POST['ban_account'])){
                    $_SESSION['alert']=$dash_model->ban($filtered_id);
					header('location: '.URL.'admin/users?id='.$filtered_id);
				}
				else{
					$title=L::title_admin_usersedit;
					require 'app/sites/global/header.php';
					require 'app/sites/global/alerts.php';
					require 'app/sites/'.THEME.'/admin/users_edit.php';
					require 'app/sites/global/footer.php';
				}
			}
			else{
				$title=L::title_admin_users;
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
			header('location: '.URL);
		}
		elseif($account->status>=SUPER){
			$dash_model=$this->loadSQL('FursuitDashModel');
			if(isset($_GET['id'])){
				$filtered_id=filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
				if(isset($_POST['edit_fursuit'])){
						$_SESSION['alert']=$dash_model->editFursuit($filtered_id, strip_tags($_POST['suitname']), strip_tags($_POST['animal']), strip_tags($_POST['in_use']), $_FILES['image']);
						header('location: '.URL.'admin/fursuits');
				}
				elseif(isset($_POST['delete_fursuit'])){
					$_SESSION['alert']=$dash_model->delFursuit($filtered_id);
					header('location: '.URL.'admin/fursuits');
				}
			}
			else{
				$fursuits=$dash_model->getFursuits();
				$title=L::title_admin_fursuits;
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
			header('location: '.URL);
		}
		elseif($account->status>=STAFF){
			if($account->status>=ADMIN){
				if(isset($_GET['id'])){
					$filtered_id=filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
				}
				//create new event
				if(isset($_POST['new_event'])){
					$_SESSION['alert']=$event_model->addEvent($_POST, $_FILES['image']);
					header('location: '.URL.'admin/event');
				}
				//edit event with given ID
				elseif(isset($_POST['edit_event'])){
					$_SESSION['alert']=$event_model->editEvent($filtered_id, $_POST, $_FILES['image']);
					header('location: '.URL.'admin/event?id='.$filtered_id);
				}
				//delete event photo with given ID
				elseif(isset($_POST['delete_photo'])){
					$_SESSION['alert']=$event_model->deletePhoto($filtered_id);
					header('location: '.URL.'admin/event?id='.$filtered_id);
				}
				//edit confirmed users
				elseif(isset($_POST['confirm_attendees'])){
					$_SESSION['alert']=$event_model->editConfirm($filtered_id, $_POST);
					header('location: '.URL.'admin/event?id='.$filtered_id);
				}
				//add manually received payment
				elseif(isset($_POST['pay_reg'])){
					$_SESSION['alert']=$event_model->addPayment($_POST['reg_id'], $_POST['amount']);
					header('location: '.URL.'admin/event?id='.$filtered_id);
				}
				elseif(isset($_POST['export_confirmed'])){
					$event_model->exportForms($filtered_id, false);
				}
				elseif(isset($_POST['export_all'])){
					$event_model->exportForms($filtered_id, true);
				}
				elseif(isset($_POST['export_invoices'])){
					$event_model->exportInvoices($filtered_id, true);
				}
				elseif(isset($_POST['export_contacts'])){
					$event_model->exportContactData($filtered_id);
				}
                elseif(isset($_POST['export_drustvo'])){
                    $event_model->exportDrustvoData($filtered_id);
                }
				elseif(isset($_POST['delete_reg'])){
					$_SESSION['alert']=$event_model->deleteReg(filter_var($_POST['delete_reg'], FILTER_SANITIZE_NUMBER_INT));
					header('location: '.URL.'admin/event?id='.$filtered_id);
				}
				elseif(isset($_POST['unverify_payment'])){
					$_SESSION['alert']=$event_model->unverifyPayment(filter_var($_POST['unverify_payment'], FILTER_SANITIZE_NUMBER_INT));
					header('location: '.URL.'admin/event?id='.$filtered_id);
				}
				elseif(isset($_POST['verify_payment'])){
					$_SESSION['alert']=$event_model->verifyPayment(filter_var($_POST['verify_payment'], FILTER_SANITIZE_NUMBER_INT));
					header('location: '.URL.'admin/event?id='.$filtered_id);
				}
				elseif(isset($_POST['delete_payment'])){
					$_SESSION['alert']=$event_model->deletePayment(filter_var($_POST['delete_payment'], FILTER_SANITIZE_NUMBER_INT));
					header('location: '.URL.'admin/event?id='.$filtered_id);
				}
				else{
					goto noactions;
				}
			}
			else{
				noactions:
				require 'app/sites/global/header.php';
				require 'app/sites/global/alerts.php';
				//go to new event creation page
				if($account->status>=ADMIN&&$action=='new'){
					//require 'app/sites/'.THEME.'/admin/sidebar.php';
					require 'app/sites/'.THEME.'/admin/newevent.php';
					echo "<script>document.title='".L::title_admin_event_new."';</script>";
				}
				//go to edit/view event page
				else{
					if(isset($_GET['id'])){
						$filtered_id=filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
						$event=$event_model->getEvent($filtered_id);
						$attendees=$event_model->getRegistered($filtered_id);
						//$rooms=$event_model->getRooms($_GET['id']);
						$fursuits=$event_model->getFursuits($filtered_id);
						$payments=$event_model->getPayments($filtered_id);
						require 'app/sites/'.THEME.'/admin/event_overview.php';
						echo "<script>document.title='".L::title_admin_event_edit.$event->name."';</script>";
					}
					//list events
					else{
						$cEvents=$event_model->getCEvents(); //current/upcoming
						$pEvents=$event_model->getPEvents(); //past
						require 'app/sites/'.THEME.'/admin/sidebar.php';
						require 'app/sites/'.THEME.'/admin/event.php';
						echo "<script>document.title='".L::title_admin_event_list."';</script>";
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
