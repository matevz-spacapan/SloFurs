<?php
class Admin extends Connection{
	public function index(){
		$account=$this->getSessionAcc();
		if($account!=null&&$account->status>1){
			header('location: '.URL.'admin/event');
		}
		elseif($account->status==1){
			$_SESSION['alert']="dYou don't have premissions to view that.";
			header('location: '.URL.'account');
		}
		else{
			header('location: '.URL.'login');
		}
	}
	// Event managing page
	public function event(){
		$account=$this->getSessionAcc();
		if($account==null){
			header('location: '.URL.'login');
		}
		elseif($account->status==3){
			$event_model=$this->loadSQL('EventModel');
			$cEvents=$event_model->getCEvents(); //current/upcoming
			$pEvents=$event_model->getPEvents(); //past
			require 'app/sites/global/header.php';
			require 'app/sites/global/adminsidebar.php';
			require 'app/sites/global/alerts.php';
			require 'app/sites/'.THEME.'/admin/event.php';
			require 'app/sites/global/footer.php';
		}
		else{
			$_SESSION['alert']="dYou don't have premissions to view that.";
			header('location: '.URL.'account/contact');
		}
	}
	// Update data in MySQL
	public function update($action, $id=null){
		$account=$this->getSessionAcc();
		if($account==null){
			header('location: '.URL.'login');
		}
		switch($action){
			case 1:
				//add event
				$event_model=$this->loadSQL('EventModel');
				$change=$event_model->addEvent($_POST['type'], $_POST['name'], $_POST['start'], $_POST['end'], $_POST['reg_start'], $_POST['pre_reg'], $_POST['reg_end'], $_POST['location'], $_POST['desc']);
				$_SESSION['alert']=$change;
				header('location: '.URL.'admin/event');
				break;
			case 2:
				//edit event
				$event_model=$this->loadSQL('EventModel');
				$change=$event_model->editEvent($id, $_POST['type'], $_POST['name'], $_POST['start'], $_POST['end'], $_POST['reg_start'], $_POST['pre_reg'], $_POST['reg_end'], $_POST['location'], $_POST['desc']);
				$_SESSION['alert']=$change;
				header('location: '.URL.'admin/event');
				break;
			default:
				header('location: '.URL.'admin/event');
		}
	}
}