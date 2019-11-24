<?php if($new_reg): ?>
<div class="bg-primary text-light">
<?php elseif($event->confirmed==1): ?>
<div class="bg-success text-light">
<?php else: ?>
<div class="bg-warning">
<?php endif;?>
	<div class="container-fluid py-2 mb-3">
		<?php if($new_reg): ?>
			<h1><?php echo L::register_form_h.": {$event->name}";?></h1>
		<?php else: ?>
			<h1><?php
				$text=($event->confirmed==1)?L::register_view_registered_confirmed:L::register_view_registered_notConfirmed;
				echo "{$event->name} ($text)";?></h1>
		<?php endif; ?>
	</div>
</div>

<div class="container-fluid">
	<!-- NAVIGATION / TABS -->
	<ul class="nav nav-tabs">
		<li class="nav-item">
			<a class="nav-link active" data-toggle="tab" href="#Event"><?php echo L::register_form_details;?></a>
		</li>
		<?php $attendees=$reg_model->getAttendees($evt_id); ?>
		<?php if($attendees>0):?>
			<li class="nav-item">
				<a class="nav-link" data-toggle="tab" href="#Stats"><?php echo L::register_form_statistics;?></a>
			</li>
			<li class="nav-item">
				<a class="nav-link" data-toggle="tab" href="#Attendees"><?php echo L::register_form_attendees;?></a>
			</li>
		<?php endif; ?>
		<?php $carSharing=false; ?>
		<?php if($carSharing): ?>
			<li class="nav-item">
				<a class="nav-link" data-toggle="tab" href="#Rides"><?php echo L::register_form_car_h;?></a>
			</li>
		<?php endif; ?>
	</ul>
	<div class="tab-content">
		<!-- EVENT DETAILS / REGISTRATION -->
		<?php require 'form/event_tab.php'; ?>
		<!-- STATS -->
		<?php require 'form/stats_tab.php'; ?>
		<?php require 'form/attendees_tab.php'; ?>
		<?php
			//CAR SHARING
			if($carSharing){
				require 'form/carsharing_tab.php';
			}
		?>
	</div>
</div>
