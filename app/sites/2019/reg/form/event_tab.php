<div class="tab-pane container-fluid active mt-3" id="Event">
	<div class="row">
		<div class="col-md-9">
			<h5><?php echo L::register_form_description;?></h5>
			<div class="text-dark"><?php echo nl2br($event->description); ?></div>
		</div>
		<div class="col">
			<h5><?php echo L::register_form_date;?></h5>
			<p class="text-dark"><?php echo $reg_model->convertViewable($event->event_start, 2); ?> -<br>
				<?php echo $reg_model->convertViewable($event->event_end, 2); ?>
			</p>

			<h5><?php echo L::register_form_registration;?></h5>
			<?php
			$now=new DateTime();
				if(new DateTime($event->reg_end)<=$now){
					//registrations ended
					$color='btn-light';
					$text=L::register_form_ended.'<br>'.$reg_model->convertViewable($event->reg_start, 2).' '.L::register_form_and.'<br>'.$reg_model->convertViewable($event->reg_end, 2).'.';
				}
				elseif(new DateTime($event->reg_start)<=$now){
					//regular registrations
					$color='btn-success';
					$text=L::register_form_currently.'<br>'.$reg_model->convertViewable($event->reg_end, 2).'.';
				}
				elseif($event->pre_reg_start!=0 && new DateTime($event->pre_reg_start)<=$now && $account!=null && $account->status>=PRE_REG){
					//pre-registrations
					$color='btn-success';
					$text=L::register_form_currently.'<br>'.$reg_model->convertViewable($event->reg_end, 2).'.';
				}
				else{
					//upcoming registrations
					$color='btn-light';
					$text=L::register_form_upcoming.'<br>'.($account!=null&&$account->status>=PRE_REG)?
						$reg_model->convertViewable($event->pre_reg_start, 2):
						$reg_model->convertViewable($event->reg_start, 2);
					$text=$text.' '.L::register_form_and.'<br>'.$reg_model->convertViewable($event->reg_end, 2).'.';
				}
			?>
			<p class="text-dark"><?php echo $text; ?></p>

			<h5><?php echo L::register_form_location;?></h5>
			<p class="text-dark"> <a href="https://maps.google.com/?q=<?php echo $event->location;?>" target="_blank"><?php echo $event->location;?> <i class="far fa-external-link"></i></a></p>

			<h5><?php echo L::register_form_gallery;?></h5>
			<p class="text-dark"><?php echo L::register_form_galleryD;?><br>
			<?php if($event->gallery!=null): ?>
				<a href="<?php echo $event->gallery;?>" target="_blank"><?php echo L::register_form_galleryL;?> <i class="far fa-external-link"></i></a>
			<?php else: ?>
				<?php echo L::register_form_galleryNone;?>
			<?php endif; ?>
			</p>
			<h5><?php echo L::register_form_age_h;?></h5>
			<?php
			$age=null;
				if($account!=null){
					$age=(int)date_diff(date_create($event->event_start), date_create($account->dob), true)->format('%y');
				}?>
			<?php if($event->age==0): ?>
				<p class="text-dark"><?php echo L::register_form_age_none;?></p>

			<?php elseif($account==null): ?>
				<p class="text-dark"><?php echo L::register_form_age_noAcc.$event->restricted_age.L::register_form_age_okYears;?></p>

			<?php elseif($age>=$event->age): ?>
				<p class="text-dark"><?php echo L::register_form_age_ok.$event->age.L::register_form_age_okYears;?></p>

			<?php elseif($age<$event->age && $age>=$event->restricted_age): ?>
				<p class="text-warning"><?php echo L::register_form_age_restricted;?><br> <?php echo $event->restricted_text; ?></p>

			<?php else: ?>
				<?php $color='btn-light'; ?>
				<p class="text-danger"><?php echo L::register_form_age_notOk1.$event->restricted_age.L::register_form_age_notOk2.$age.L::register_form_age_notOk3;?></p>
			<?php endif; ?>
			<?php if($account!=null&&!$reg_model->checkProfile()){
				$color='btn-light';
			} ?>
			<h5><?php echo L::register_form_questions;?></h5>
			<p class="text-dark"><a href="mailto:slofurs@gmail.com" target="_blank"><?php echo L::register_form_email;?></a>, <a href="https://discord.gg/0eaoyLCJ7eiTMBaj" target="_blank">Discord <i class="far fa-external-link"></i></a> </p>
			<!-- FORM BUTTON -->
			<?php if($new_reg): ?>
				<button class="btn-block btn <?php echo $color; ?>" <?php if($color!='btn-success'||$age<$event->restricted_age){echo 'disabled';} else{echo 'data-toggle="modal" data-target="#register"';} ?>><?php echo L::register_form_buttonRegister;?></button>
				<?php if($account==null&&$color=='btn-success'): ?>
					<p><a href="#" data-toggle="modal" data-target="#loginModal"><?php echo L::register_form_login;?></p>
				<?php elseif($account!=null&&!$reg_model->checkProfile()): ?>
					<p><a href="<?php echo URL;?>account/contact"><?php echo L::register_form_completeProfile;?></p>
				<?php endif; ?>
			<?php elseif($color=='btn-light'): ?>
				<button class="btn-block btn btn-light" data-toggle="modal" data-target="#register"><?php echo L::register_form_buttonView;?></button>
			<?php else: ?>
				<button class="btn-block btn btn-primary" data-toggle="modal" data-target="#register"><?php echo L::register_form_buttonEdit;?></button>
			<?php endif; ?>

<!-- REGISTRATION FORM -->
			<?php if($age>=$event->restricted_age && ($color=='btn-success' || new DateTime($event->reg_end)<=$now)): ?>
				<div id="register" class="modal fade">
					<div class="modal-dialog modal-lg">
						<div class="modal-content" style="max-width:600px">
							<?php
								if($new_reg){
									$form_type='new';
									$c='text-success';
								}
								else{
									$form_type='edit';
									$c='text-primary';
								}
							?>
							<div class="modal-header">
								<h4 class="modal-title <?php echo $c; ?>"><?php echo L::register_form_modal_h;?></h4>
				        <button type="button" class="close" data-dismiss="modal">&times;</button>
							</div>
							<form action="<?php echo URL; ?>register/<?php echo $form_type; ?>?id=<?php echo $event->id; ?>" method="post" class="needs-validation" novalidate>
							<div class="modal-body">
								<!-- TICKET TYPES -->
								<h5><?php echo L::register_form_modal_prices_h;?></h5>
								<?php if($event->regular_price==0): ?>
									<p class="text-dark"><?php echo L::register_form_modal_prices_free;?></p>
								<?php else: ?>
									<table class="table table-borderless">
										<tr>
											<th><?php echo L::register_form_modal_selection;?></th>
											<th><?php echo L::register_form_modal_price;?></th>
											<th><?php echo L::register_form_modal_prices_info;?></th>
										</tr>
										<tr>
											<td>
												<div class="custom-control custom-radio custom-control-inline">
													<input class="custom-control-input" type="radio" name="ticket" value="regular" id="regular" <?php if(!$new_reg&&$event->ticket=='regular'){echo 'checked';} ?> required>
													<label for="regular" class="custom-control-label">
														<?php if(strlen($event->regular_title)!=0): ?>
															<?php echo $event->regular_title;?>
														<?php else: ?>
															<?php echo L::admin_form_tickets_regular;?>
														<?php endif; ?>
													</label>
												</div>
											</td>
											<td><?php echo $event->regular_price; ?>€</td>
											<td><?php echo nl2br($event->regular_text); ?></td>
										</tr>
										<?php if($event->sponsor_price!=-1): ?>
										<tr>
											<td>
												<div class="custom-control custom-radio custom-control-inline">
													<input class="custom-control-input" type="radio" name="ticket" value="sponsor" id="sponsor" <?php if(!$new_reg&&$event->ticket=='sponsor'){echo 'checked';} ?>>
													<label for="sponsor" class="custom-control-label">
														<?php if(strlen($event->sponsor_title)!=0): ?>
															<?php echo $event->sponsor_title;?>
														<?php else: ?>
															<?php echo L::admin_form_tickets_sponsor;?>
														<?php endif; ?>
													</label>
												</div>
											</td>
											<td><?php echo $event->sponsor_price; ?>€</td>
											<td><?php echo nl2br($event->sponsor_text); ?></td>
										</tr>
										<?php endif; ?>
										<?php if($event->super_price!=-1): ?>
										<tr>
											<td>
												<div class="custom-control custom-radio custom-control-inline">
													<input class="custom-control-input" type="radio" name="ticket" value="super" id="super" <?php if(!$new_reg&&$event->ticket=='sponsor'){echo 'checked';} ?>>
													<label for="super" class="custom-control-label">
														<?php if(strlen($event->super_title)!=0): ?>
															<?php echo $event->super_title;?>
														<?php else: ?>
															<?php echo L::admin_form_tickets_super;?>
														<?php endif; ?>
													</label>
												</div>
											</td>
											<td><?php echo $event->super_price; ?>€</td>
											<td><?php echo nl2br($event->super_text); ?></td>
										</tr>
										<?php endif; ?>
									</table>
								<?php endif; ?>
								<!-- ACCOMODATION -->
								<h5><?php echo L::register_form_modal_accomodation_h;?></h5>
								<?php
									$rooms=$reg_model->getAccomodation($evt_id);
									$event_duration=(int)date_diff(date_create($event->event_start), date_create($event->event_end), true)->format('%d');
								?>
								<?php if(count($rooms)>0): ?>
									<table class="table table-borderless">
										<tr>
											<th><?php echo L::register_form_modal_selection;?></th>
											<th><?php echo L::register_form_modal_price;?></th>
											<th><?php echo L::register_form_modal_accomodation_persons;?> <i class="far fa-info-circle" title="<?php echo L::register_form_modal_accomodation_personsI;?>"></i></th>
											<th><?php echo L::register_form_modal_accomodation_availability;?> <i class="far fa-info-circle" title="<?php echo L::register_form_modal_accomodation_availabilityI;?>"></i></th>
										</tr>
										<tr>
											<td>
												<div class="custom-control custom-radio custom-control-inline">
													<input class="custom-control-input" type="radio" name="room" value="0" id="room0" required <?php if(!$new_reg&&$event->room_id==null){echo 'checked';} ?>>
													<label for="room0" class="custom-control-label">
														<?php echo L::register_form_modal_accomodation_none;?>
													</label>
												</div>
											</td>
										</tr>
										<?php foreach($rooms as $room): ?>
											<tr>
												<?php $result=$room->quantity-$reg_model->getBooked($evt_id, $room->id)->quantity;?>
												<td>
													<div class="custom-control custom-radio custom-control-inline">
														<input class="custom-control-input" type="radio" name="room" value="<?php echo $room->id; ?>" id="room<?php echo $room->id; ?>"
														<?php
															if(!$new_reg&&$event->room_id==$room->id){
																echo 'checked ';
															}
															if($result<=0){
																echo 'disabled';
																$result=L::register_form_modal_accomodation_noSpace;
															}
															?>>
														<label for="room<?php echo $room->id; ?>" class="custom-control-label">
															<?php echo $room->type; ?>
														</label>
													</div>
												</td>
												<td><?php echo $room->price; ?>€</td>
												<td><?php echo $room->persons; ?></td>
												<td><?php echo $result; ?></td>
											</tr>
										<?php endforeach; ?>
									</table>
								<?php else: ?>
									<p class="text-dark"><?php echo L::register_form_modal_accomodation_noAccomodation;?> <?php if($event_duration>0){ echo L::register_form_modal_accomodation_noAccomodationI; } ?></p>
								<?php endif; ?>
								<!-- OTHER DATA -->
								<h5><?php echo L::register_form_modal_other_h;?></h5>
								<div class="form-group">
									<label for="notes"><?php echo L::register_form_modal_other_notes;?> <small class="form-text text-muted"><?php echo L::register_form_modal_other_notesI;?></small></label>
									<input type="text" name="notes" value="<?php if(!$new_reg){echo $event->notes;} ?>" class="form-control">
								</div>
								<div class="custom-control custom-checkbox">
									<input class="custom-control-input" type="checkbox" name="fursuit" value="1" id="fursuit" <?php if(!$new_reg&&$event->fursuiter==1){echo 'checked';} ?>>
									<label for="fursuit" class="custom-control-label"><?php echo L::register_form_modal_other_fursuiter;?></label>
								</div>
								<div class="custom-control custom-checkbox">
									<input class="custom-control-input" type="checkbox" name="artist" value="1" id="artist" <?php if(!$new_reg&&$event->artist==1){echo 'checked';} ?>>
									<label for="artist" class="custom-control-label"><?php echo L::register_form_modal_other_artist;?></label>
								</div>
								<?php if($new_reg): ?>
									<?php if($account->newsletter==0): ?>
										<div class="custom-control custom-checkbox">
											<input class="custom-control-input" type="checkbox" name="newsletter" value="1" id="newsletter">
											<label for="newsletter" class="custom-control-label"><?php echo L::register_form_modal_other_newsletter;?></label>
										</div>
									<?php endif; ?>
								<?php endif; ?>
							</div>
							<div class="modal-footer">
								<span class="form-control-static"><?php echo L::register_form_modal_rules1;?> <a href="<?php echo URL;?>rules" target="_blank"><?php echo L::register_form_modal_rules2;?> <i class="far fa-external-link"></i></a></span>
								<?php if($new_reg): ?>
									<button type="submit" name="new_registration" class="btn btn-success"><?php echo L::register_form_modal_register;?></button>
								<?php elseif($color=='btn-success'): ?>
									<button type="submit" name="edit_registration" class="btn btn-success"><?php echo L::register_form_modal_save;?></button>
								<?php endif; ?>
			        </div>
							</form>
						</div>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
<?php require 'app/sites/global/validate_form.php'; ?>
