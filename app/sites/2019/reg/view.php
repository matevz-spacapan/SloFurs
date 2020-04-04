<!-- COMPLETE PROFILE WARNING -->
<?php if(!$complete_profile): ?>
	<div class="container-fluid">
		<h3 class="text-danger"><?php echo L::register_view_profile_h;?></h3>
		<?php echo L::register_view_profile_desc1;?><br>
		<a href="#" data-toggle="modal" data-target="#personal"><?php echo L::register_view_profile_button;?></a><?php echo L::register_view_profile_desc2;?> <a href="<?php echo URL; ?>account/contact" class="text-secondary"><?php echo L::register_view_profile_link;?></a>.<br><br>
		<div class="modal fade" id="personal">
			<div class="modal-dialog modal-xl">
				<div class="modal-content p-4">
					<div class="modal-header">
						<h4 class="modal-title"><?php echo L::account_contact_h;?></h4>
						<button type="button" class="close" data-dismiss="modal">&times;</button>
					</div>
					<?php $register=true; require 'app/sites/'.THEME.'/account/personal_info.php'; ?>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>
<!-- REGISTERED EVENTS -->
<?php if($account!=null&&count($rEvents)>0): ?>
	<div class="container-fluid">
		<h3><?php echo L::register_view_registered_h; $edit=true;?></h3>
		<div class="row ml-1">
		<?php foreach($rEvents as $event): ?>
			<?php
				$id=$event->id;
				$event=$reg_model->existingReg($id);
				$data=$reg_model->getColorText($id);
				$color=$data['color'];
				$text=$data['text'];
				require 'app/sites/'.THEME.'/reg/evt.php';
			?>
		<?php endforeach; ?>
		</div>
	</div>
<?php endif; ?>
<!-- CURRENT/UPCOMING EVENTS -->
<div class="container-fluid">
	<h3><?php echo L::register_view_upcoming_h; $edit=false;?></h3>
	<?php if(count($cEvents)>0): ?>
		<div class="row ml-1">
		<?php foreach($cEvents as $event): ?>
			<?php
				//reg closed
				if(new DateTime($event->reg_end)<=new DateTime()){
					$color='bg-secondary text-white';
					$text=L::admin_event_text_closed;
				}
				//regular reg
				elseif(new DateTime($event->reg_start)<=new DateTime()){
					$color='bg-primary text-white';
					$text=L::admin_event_text_reg.'<br>'.$reg_model->convertViewable($event->reg_end, 2);
				}
				//pre-reg
				elseif($event->pre_reg_start!=$event->reg_start && new DateTime($event->pre_reg_start)<=new DateTime() && $account!=null && $account->status>=PRE_REG){
					$color='bg-info text-white';
					$text=L::admin_event_text_pre.'<br>'.$reg_model->convertViewable($event->reg_start, 2);
				}
				//not available for reg yet
				else{
					$color='bg-secondary text-white';
					$text=L::admin_event_text_until.'<br>';
					$date=($account!=null && $account->status>=PRE_REG)?$reg_model->convertViewable($event->pre_reg_start, 2):$reg_model->convertViewable($event->reg_start, 2);
					$text=$text.$date;
				}
				if(!$complete_profile){
					$color='bg-secondary text-white';
					$text=L::register_view_upcoming_complete;
				}
			?>
			<?php require 'app/sites/'.THEME.'/reg/evt.php'; ?>
		<?php endforeach; ?>
		</div>
	<?php else: ?>
		<p><?php echo L::register_view_upcoming_none;?> <i class="far fa-frown"></i></p>
	<?php endif; ?>
</div>

<!-- PAST EVENTS -->
<?php if(count($pEvents)>0): ?>
	<div class="container-fluid">
		<h3><?php echo L::register_view_past_h;?></h3>
		<div class="row ml-1">
			<?php foreach($pEvents as $event): ?>
				<?php
					$color='bg-secondary text-white';
					$text=L::register_view_past_ended;
					require 'app/sites/'.THEME.'/reg/evt.php';
				?>
			<?php endforeach; ?>
		</div>
	</div>
<?php endif; ?>
