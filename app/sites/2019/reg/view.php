<div class="w3-blue">
	<div class="w3-container">
		<h1>Event registration</h1>
	</div>
</div>
<div class="w3-container w3-col l6 m8">
	<!-- REGISTERED EVENTS -->
	<div class="w3-container">
		<h3>Upcoming events you've registered for</h3>
		<?php if(count($rEvents) > 0): ?>
			<?php foreach($rEvents as $event): ?>
				<a href="<?php echo URL.'register/edit/'.$event->id; ?>">
					<?php
						$color=($event->confirmed==1)?'w3-green':'w3-orange';
						$text=($event->confirmed==1)?'Registration confirmed.':'Registration not confirmed yet.';
						require 'app/sites/'.THEME.'/reg/evt.php';
					?>
				</a>
			<?php endforeach; ?>
		<?php else: ?>
			<p>There's nothing to show here <i class="far fa-frown"></i></p>
		<?php endif; ?>
	</div>
	<!-- CURRENT/UPCOMING EVENTS -->
	<div class="w3-container">
		<h3>Upcoming events</h3>
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
							$text='Registration open until<br>'.$reg_model->convertViewable($event->reg_end, true).' @ '.$reg_model->convertViewable($event->reg_end, false);
						}
						elseif($event->pre_reg_start!=0 && new DateTime($event->pre_reg_start)<=new DateTime() && $account->status>=2){
							$color='w3-light-blue';
							$text='Pre-reg open until<br>'.$reg_model->convertViewable($event->reg_start, true).' @ '.$reg_model->convertViewable($event->reg_start, false);
						}
						else{
							$color='w3-light-gray';
							$text=($account->status>=2)?
								'Registrations closed until<br>'.$reg_model->convertViewable($event->pre_reg_start, true).' @ '.$reg_model->convertViewable($event->pre_reg_start, false):
								$text='Registrations closed until<br>'.$reg_model->convertViewable($event->reg_start, true).' @ '.$reg_model->convertViewable($event->reg_start, false);
						}
					?>
					<?php if($color!='w3-light-gray'): ?>
						<a href="<?php echo URL.'register/new/'.$event->id; ?>">
					<?php endif; ?>
						<?php require 'app/sites/'.THEME.'/reg/evt.php'; ?>
					<?php if($color!='w3-light-gray'){ echo "</a>"; } ?>
				<?php endforeach; ?>
			<?php else: ?>
				<p>There are no upcoming events <i class="far fa-frown"></i></p>
			<?php endif; ?>
		</div>
	</div>
</div>