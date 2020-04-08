	<?php
		$editEvent=false;
		$event_model=$this->loadSQL('AdminEventModel');
		$type='new';
		$page=null;
		if(isset($_POST['pg2'])){
			$page=2;
		}
		elseif(isset($_POST['pg3'])){
			$page=3;
		}
		elseif(isset($_POST['pg4'])){
			$page=4;
		}
		elseif(isset($_POST['pg5'])){
			$page=5;
		}
		if(isset($_GET['id'])){
			$id=filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
		}
		switch ($step) {
			case 1:
				if(isset($page)){
					//edit event
					if(isset($id)){
						$_SESSION['alert']=$event_model->editEvent1($id, $_POST, $_FILES['image']);
						header('location: '.URL."admin/event/$type/$page?id=$id");
					}
					//create new event
					else{
						$returned=$event_model->addEvent($_POST, $_FILES['image']);
						$_SESSION['alert']=$returned['err'];
						$id=$returned['id'];
						header('location: '.URL."admin/event/$type/$page?id=$id");
					}
				}
				else{
					//display page 1
					if(isset($id)){
						$event=$event_model->getEvent($id);
					}
					require 'app/sites/'.THEME.'/admin/sidebar.php';
					require 'app/sites/'.THEME.'/admin/event_form/form1.php';
				}
				break;
			case 2:
				if(isset($page)){

				}
				else{
					//display page 2
					if(isset($id)){
						$event=$event_model->getEvent($id);
					}
					else{
						$_SESSION['alert']='dMissing event ID.';
						header('location: '.URL."admin/event/new/1");
					}
					require 'app/sites/'.THEME.'/admin/sidebar.php';
					require 'app/sites/'.THEME.'/admin/event_form/form2.php';
				}
				break;
			case 3:
				if(isset($id)){
					$event=$event_model->getEvent($id);
				}
				else{
					$_SESSION['alert']='dMissing event ID.';
					header('location: '.URL."admin/event/new/1");
				}
				require 'app/sites/'.THEME.'/admin/sidebar.php';
				require 'app/sites/'.THEME.'/admin/event_form/form3.php';
				break;
			case 4:
				if(isset($id)){
					$event=$event_model->getEvent($id);
				}
				else{
					$_SESSION['alert']='dMissing event ID.';
					header('location: '.URL."admin/event/new/1");
				}
				require 'app/sites/'.THEME.'/admin/sidebar.php';
				require 'app/sites/'.THEME.'/admin/event_form/form4.php';
				break;
			case 5:
				if(isset($id)){
					$event=$event_model->getEvent($id);
				}
				else{
					$_SESSION['alert']='dMissing event ID.';
					header('location: '.URL."admin/event/new/1");
				}
				require 'app/sites/'.THEME.'/admin/sidebar.php';
				require 'app/sites/'.THEME.'/admin/event_form/form5.php';
				break;
			default:
				header('location: '.URL."admin/event/new/1");
				break;
		}
	?>
</div>
