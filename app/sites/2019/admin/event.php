<div class="w3-main" style="margin-left:200px">
<div class="w3-orange">
	<button class="w3-button w3-orange w3-xlarge w3-hide-large" onclick="side_open()">&#9776;</button>
	<div class="w3-container">
		<h1>Events manager</h1>
	</div>
</div>
<div class="w3-container">
	<!-- CURRENT/UPCOMING EVENTS -->
	<div class="w3-container">
		<h3>Current and upcoming events</h3>
		<!-- COLOUR GUIDE -->
		<div>
			To differentiate between the phase of an event, the following colour codes are being used:
			<div class="w3-bar">
				<button class="w3-button w3-round-large w3-light-gray w3-hover-light-gray">Upcoming</button>
				<button class="w3-button w3-round-large w3-light-blue w3-hover-light-blue">Pre-reg</button>
				<button class="w3-button w3-round-large w3-blue w3-hover-blue">Reg</button>
				<button class="w3-button w3-round-large w3-dark-gray w3-hover-dark-gray">Reg closed</button>
			</div>
		</div>
		<br>
		<div class="w3-row">
			<?php if(count($cEvents) > 0): ?>
				<?php foreach($cEvents as $event): ?>
					<?php
						if(new DateTime($event->reg_end)<=new DateTime()){
							$color='w3-light-gray';
							$text='Registration closed.';
						}
						elseif(new DateTime($event->reg_start)<=new DateTime()){
							$color='w3-blue';
							$text='Registration open until<br>'.$reg_model->convertViewable($event->reg_end, 2);
						}
						elseif($event->pre_reg_start!=$event->reg_start && new DateTime($event->pre_reg_start)<=new DateTime() && $account->status>=PRE_REG){
							$color='w3-light-blue';
							$text='Pre-reg open until<br>'.$reg_model->convertViewable($event->reg_start, 2);
						}
						else{
							$color='w3-light-gray';
							$text=($account->status>=PRE_REG)?
								'Registrations closed until<br>'.$event_model->convertViewable($event->pre_reg_start, 2):
								'Registrations closed until<br>'.$event_model->convertViewable($event->reg_start, 2);
						}
						require 'app/sites/'.THEME.'/admin/evt.php';
					?>
				<?php endforeach; ?>
			<?php else: ?>
				<p>There are no current/upcoming events to show.</p>
			<?php endif; ?>
		</div>
	</div>

	<!-- PAST EVENTS COLUMN -->
	<div class="w3-container">
		<h3>Past events</h3>
		<div class="w3-row">
			<?php if(count($pEvents) > 0): ?>
				<?php foreach($pEvents as $event): ?>
					<?php
						$color='w3-light-gray';
						require 'app/sites/'.THEME.'/admin/evt.php';
					?>
				<?php endforeach; ?>
			<?php else: ?>
				<p>There are no past events to show.</p>
			<?php endif; ?>
		</div>
	</div>
</div>

<script>
function side_open(){
	$("#accSidebar").show();
}
function side_close(){
	$("#accSidebar").hide();
}
function editEvent(id){
	$("#event"+id).show();
}
function onLoad(){
	$("#event").addClass("w3-orange");
}
onLoad();
</script>
