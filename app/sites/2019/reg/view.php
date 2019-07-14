<div class="w3-blue">
	<div class="w3-container">
		<h1><?php echo L::register_view_h;?></h1>
	</div>
</div>
<div class="w3-container w3-col l6 m8">
	<!-- COMPLETE PROFILE WARNING -->
	<?php if(!$complete_profile): ?>
		<h3 class="w3-text-red"><i><?php echo L::register_view_profile_h;?></i></h3>
		<div>
			<?php echo L::register_view_profile_desc1;?><br> <button class="w3-button w3-border w3-round w3-border-red" onclick="$('#personal').show()"><?php echo L::register_view_profile_button;?></button><br> <?php echo L::register_view_profile_desc2;?> <a href="<?php echo URL; ?>account/contact"><?php echo L::register_view_profile_link;?></a>.
			<div id="personal" class="w3-modal">
				<div class="w3-modal-content w3-card-4 w3-round-large" style="max-width:600px">
					<header class="w3-container w3-blue w3-center roundHeaderTop">
						<span onclick="$('#personal').hide()"
						class="w3-button w3-display-topright roundXTop">&times;</span>
						<h2><?php echo L::account_contact_h;?></h2> <i class="w3-opacity w3-small"><?php echo L::account_contact_hInfo;?></i><br/><br/>
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
			<h3><?php echo L::register_view_registered_h;?></h3>
			<?php foreach($rEvents as $event): ?>
				<a href="<?php echo URL.'register/edit?id='.$event->id; ?>" style="text-decoration: none;">
					<?php
						$color=($event->confirmed==1)?'w3-green':'w3-orange';
						$text=($event->confirmed==1)?L::register_view_registered_confirmed:L::register_view_registered_notConfirmed;
						require 'app/sites/'.THEME.'/reg/evt.php';
					?>
				</a>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
	<!-- CURRENT/UPCOMING EVENTS -->
	<div class="w3-container">
		<h3><?php echo L::register_view_upcoming_h;?></h3>
		<div class="w3-row">
			<?php if(count($cEvents)>0): ?>
				<?php foreach($cEvents as $event): ?>
					<?php
						if(new DateTime($event->reg_end)<=new DateTime()){
							$color='w3-light-gray';
							$text=L::admin_event_text_closed;
						}
						elseif(new DateTime($event->reg_start)<=new DateTime()){
							$color='w3-blue';
							$text=L::admin_event_text_reg.'<br>'.$reg_model->convertViewable($event->reg_end, 2);
						}
						elseif($event->pre_reg_start!=$event->reg_start && new DateTime($event->pre_reg_start)<=new DateTime() && $account->status>=PRE_REG){
							$color='w3-light-blue';
							$text=L::admin_event_text_pre.'<br>'.$reg_model->convertViewable($event->reg_start, 2);
						}
						else{
							$color='w3-light-gray';
							$text=L::admin_event_text_until.'<br>'.($account->status>=PRE_REG)?
								$reg_model->convertViewable($event->pre_reg_start, 2):
								$reg_model->convertViewable($event->reg_start, 2);
						}
						if(!$complete_profile){
							$color='w3-light-gray';
							$text=L::register_view_upcoming_;complete;
						}
					?>
					<a href="<?php echo URL.'register/new?id='.$event->id; ?>" style="text-decoration: none;">
					<?php require 'app/sites/'.THEME.'/reg/evt.php'; ?>
					</a>
				<?php endforeach; ?>
			<?php else: ?>
				<p><?php echo L::register_view_upcoming_none;?> <i class="far fa-frown"></i></p>
			<?php endif; ?>
		</div>
	</div>

	<!-- PAST EVENTS -->
	<?php if(count($pEvents)>0): ?>
		<div class="w3-container">
			<h3><?php echo L::register_view_past_h;?></h3>
			<?php foreach($pEvents as $event): ?>
				<a href="<?php echo URL.'register/edit?id='.$event->id; ?>" style="text-decoration: none;">
					<?php
						$color='w3-gray';
						$text=L::register_view_past_ended;
						require 'app/sites/'.THEME.'/reg/evt.php';
					?>
				</a>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</div>
