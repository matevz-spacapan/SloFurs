<?php if($new_reg): ?>
<div class="bg-primary text-light">
<?php else: ?>
	<?php
		$data=$reg_model->getColorText($id);
		$color=$data['color'];
		$text=$data['text'];
	?>
<div class="<?php echo $color; ?>">
<?php endif;?>
	<div class="container-fluid py-2 mb-3">
		<?php if($new_reg): ?>
			<h1><?php echo $event->name;?></h1>
		<?php else: ?>
			<h1><?php echo "{$event->name} – $text";?></h1>
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
		<?php if(count($attendees)>0):?>
			<li class="nav-item">
				<a class="nav-link" data-toggle="tab" href="#Stats"><?php echo L::register_form_statistics;?></a>
			</li>
			<li class="nav-item">
				<a class="nav-link" data-toggle="tab" href="#Attendees"><?php echo L::register_form_attendees . ' (' . count($attendees) . ')';?></a>
			</li>
		<?php endif; ?>
		<?php $carSharing=true; ?>
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
