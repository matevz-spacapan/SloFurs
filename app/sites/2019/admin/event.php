<div class="w3-main" style="margin-left:300px">
<header class="container-fluid my-4">
  <h5><b><i class="fal fa-users-cog"></i> <?php echo L::admin_dash_h.': '.L::admin_event_evtManager;?></b></h5>
</header>
<div class="container-fluid">
	<!-- CURRENT/UPCOMING EVENTS -->
	<h4><?php echo L::admin_event_curr;?></h4>
	<!-- COLOUR GUIDE -->
	<?php echo L::admin_event_colors_diff;?>
	<div class="w3-bar">
		<button class="btn btn-secondary"><?php echo L::admin_event_colors_upcoming;?></button>
		<button class="btn btn-info"><?php echo L::admin_event_colors_pre;?></button>
		<button class="btn btn-primary"><?php echo L::admin_event_colors_reg;?></button>
		<button class="btn btn-dark"><?php echo L::admin_event_text_closed;?></button>
	</div>
	<br>
	<div class="row ml-1">
		<?php if(count($cEvents) > 0): ?>
			<?php foreach($cEvents as $event): ?>
				<?php
					if(new DateTime($event->reg_end)<=new DateTime()){
						$color='bg-dark text-light';
						$text=L::admin_event_text_closed;
					}
					elseif(new DateTime($event->reg_start)<=new DateTime()){
						$color='bg-primary';
						$text=L::admin_event_text_reg.'<br>'.$event_model->convertViewable($event->reg_end, 2);
					}
					elseif($event->pre_reg_start!=$event->reg_start && new DateTime($event->pre_reg_start)<=new DateTime() && $account->status>=PRE_REG){
						$color='bg-info';
						$text=L::admin_event_text_pre.'<br>'.$event_model->convertViewable($event->reg_start, 2);
					}
					else{
						$color='bg-secondary';
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

	<!-- PAST EVENTS COLUMN -->
	<h4 class="mt-3"><?php echo L::admin_event_past;?></h4>
	<div class="row ml-1">
		<?php if(count($pEvents) > 0): ?>
			<?php foreach($pEvents as $event): ?>
				<?php
					$color='bg-dark text-light';
					$text=L::admin_event_text_closed;
					require 'app/sites/'.THEME.'/admin/evt.php';
				?>
			<?php endforeach; ?>
		<?php else: ?>
			<p><?php echo L::admin_event_noPast;?></p>
		<?php endif; ?>
	</div>
</div>

<script>
$("#event").addClass("bg-warning");
$("#events_list").addClass("bg-light");
$('#dropdown').addClass("w3-show");
</script>
