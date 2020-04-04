<div class="w3-main" style="margin-left:300px">
<div class="w3-container w3-orange">
	<h1><?php echo L::admin_event_evtManager;?></h1>
</div>
<div class="w3-container">
	<!-- CURRENT/UPCOMING EVENTS -->
	<div class="w3-container">
		<h3><?php echo L::admin_event_curr;?></h3>
		<!-- COLOUR GUIDE -->
		<div>
			<?php echo L::admin_event_colors_diff;?>
			<div class="w3-bar">
				<button class="w3-button w3-round-large w3-light-gray w3-hover-light-gray"><?php echo L::admin_event_colors_upcoming;?></button>
				<button class="w3-button w3-round-large w3-light-blue w3-hover-light-blue"><?php echo L::admin_event_colors_pre;?></button>
				<button class="w3-button w3-round-large w3-blue w3-hover-blue"><?php echo L::admin_event_colors_reg;?></button>
				<button class="w3-button w3-round-large w3-dark-gray w3-hover-dark-gray"><?php echo L::admin_event_text_closed;?></button>
			</div>
		</div>
		<br>
		<div class="w3-row">
			<?php if(count($cEvents) > 0): ?>
				<?php foreach($cEvents as $event): ?>
					<?php
						if(new DateTime($event->reg_end)<=new DateTime()){
							$color='w3-light-gray';
							$text=L::admin_event_text_closed;;
						}
						elseif(new DateTime($event->reg_start)<=new DateTime()){
							$color='w3-blue';
							$text=L::admin_event_text_reg.'<br>'.$event_model->convertViewable($event->reg_end, 2);
						}
						elseif($event->pre_reg_start!=$event->reg_start && new DateTime($event->pre_reg_start)<=new DateTime() && $account->status>=PRE_REG){
							$color='w3-light-blue';
							$text=L::admin_event_text_pre.'<br>'.$event_model->convertViewable($event->reg_start, 2);
						}
						else{
							$color='w3-light-gray';
							$text=L::admin_event_text_until.'<br>';
							$date=($account->status>=PRE_REG)?$event_model->convertViewable($event->pre_reg_start, 2):$event_model->convertViewable($event->reg_start, 2);
							$text=$text.$date;
						}
						require 'app/sites/'.THEME.'/admin/evt.php';
					?>
				<?php endforeach; ?>
			<?php else: ?>
				<p><?php echo L::admin_event_noUpcoming;?></p>
			<?php endif; ?>
		</div>
	</div>

	<!-- PAST EVENTS COLUMN -->
	<div class="w3-container">
		<h3><?php echo L::admin_event_past;?></h3>
		<div class="w3-row">
			<?php if(count($pEvents) > 0): ?>
				<?php foreach($pEvents as $event): ?>
					<?php
						$color='w3-light-gray';
						$text='';
						require 'app/sites/'.THEME.'/admin/evt.php';
					?>
				<?php endforeach; ?>
			<?php else: ?>
				<p><?php echo L::admin_event_noPast;?></p>
			<?php endif; ?>
		</div>
	</div>
</div>

<script>
$("#event").addClass("w3-orange");
$("#events_list").addClass("w3-sand");
$('#dropdown').addClass("w3-show");
</script>
