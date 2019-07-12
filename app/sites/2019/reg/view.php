<div class="w3-blue">
	<div class="w3-container">
		<h1>Event registration</h1>
	</div>
</div>
<div class="w3-container w3-col l6 m8">
	<!-- COMPLETE PROFILE WARNING -->
	<?php if(!$complete_profile): ?>
		<h3 class="w3-text-red"><i>Complete your profile!</i></h3>
		<div>
			Before you can register for any event you'll have to complete your profile with personal information. We need this information for administrative and security reasons.<br> <button class="w3-button w3-border w3-round w3-border-red" onclick="$('#personal').show()">Add personal information</button><br> Alternatively you can view all your account details <a href="<?php echo URL; ?>account/contact">on the account pages</a>.
			<div id="personal" class="w3-modal">
				<div class="w3-modal-content w3-card-4 w3-round-large" style="max-width:600px">
					<header class="w3-container w3-blue w3-center roundHeaderTop">
						<span onclick="$('#personal').hide()"
						class="w3-button w3-display-topright roundXTop">&times;</span>
						<h2>Contact information</h2> <i class="w3-opacity w3-small">This information is kept private and validated at the event by staff. It will also show up on your invoice.</i><br/><br/>
					</header>
					<div class="w3-container">
						<?php $register=true; require 'app/sites/'.THEME.'/account/personal_info.php'; ?>
					</div>
				</div>
			</div>
		</div>
	<?php endif; ?>
	<!-- REGISTERED EVENTS -->
	<?php if(count($rEvents)>0): ?>
		<div class="w3-container">
			<h3>Upcoming events you've registered for</h3>
			<?php foreach($rEvents as $event): ?>
				<a href="<?php echo URL.'register/edit?id='.$event->id; ?>" style="text-decoration: none;">
					<?php
						$color=($event->confirmed==1)?'w3-green':'w3-orange';
						$text=($event->confirmed==1)?'Registration confirmed.':'Registration not confirmed yet.';
						require 'app/sites/'.THEME.'/reg/evt.php';
					?>
				</a>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
	<!-- CURRENT/UPCOMING EVENTS -->
	<div class="w3-container">
		<h3>Upcoming events</h3>
		<div class="w3-row">
			<?php if(count($cEvents)>0): ?>
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
								'Registrations closed until<br>'.$reg_model->convertViewable($event->pre_reg_start, 2):
								'Registrations closed until<br>'.$reg_model->convertViewable($event->reg_start, 2);
						}
						if(!$complete_profile){
							$color='w3-light-gray';
							$text='Complete profile to register.';
						}
					?>
					<a href="<?php echo URL.'register/new?id='.$event->id; ?>" style="text-decoration: none;">
					<?php require 'app/sites/'.THEME.'/reg/evt.php'; ?>
					</a>
				<?php endforeach; ?>
			<?php else: ?>
				<p>There are no upcoming events <i class="far fa-frown"></i></p>
			<?php endif; ?>
		</div>
	</div>

	<!-- PAST EVENTS -->
	<?php if(count($pEvents)>0): ?>
		<div class="w3-container">
			<h3>Past events you've registered for</h3>
			<?php foreach($pEvents as $event): ?>
				<a href="<?php echo URL.'register/edit?id='.$event->id; ?>" style="text-decoration: none;">
					<?php
						$color='w3-gray';
						$text='Event ended.';
						require 'app/sites/'.THEME.'/reg/evt.php';
					?>
				</a>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</div>
