<div class="w3-main">
<?php if($new_reg): ?>
<div class="w3-blue">
<?php elseif($event->confirmed==1): ?>
<div class="w3-green">
<?php else: ?>
<div class="w3-orange">
<?php endif;?>
	<div class="w3-container">
		<?php if($new_reg): ?>
			<h1><?php echo L::register_form_h.": {$event->name}";?></h1>
		<?php else: ?>
			<h1><?php
				$text=($event->confirmed==1)?L::register_view_registered_confirmed:L::register_view_registered_notConfirmed;
				echo "{$event->name} ($text)";?></h1>
		<?php endif; ?>
	</div>
</div>

<!-- NAVIGATION / TABS -->
<div class="w3-row w3-center">
	<a href="javascript:void(0)" onclick="openTab(event, 'Event');">
		<div class="w3-third tablink w3-bottombar w3-hover-light-grey w3-padding w3-border-blue"><?php echo L::register_form_details;?></div>
	</a>
	<a href="javascript:void(0)" onclick="openTab(event, 'Stats');">
		<div class="w3-third tablink w3-bottombar w3-hover-light-grey w3-padding"><?php echo L::register_form_statistics;?></div>
	</a>
	<a href="javascript:void(0)" onclick="openTab(event, 'Rides');">
		<div class="w3-third tablink w3-bottombar w3-hover-light-grey w3-padding"><?php echo L::register_form_car_h;?></div>
	</a>
</div>

<!-- EVENT DETAILS / REGISTRATION -->
<div class="w3-container tab" style="width:85%; margin: 0 auto;" id="Event">
	<div class="w3-row">
		<div class="w3-col l9">
			<h5><?php echo L::register_form_description;?></h5>
			<div class="w3-text-dark-gray"><?php echo nl2br($event->description); ?></div>
		</div>
		<div class="w3-container w3-rest">
			<h5><?php echo L::register_form_date;?></h5>
			<p class="w3-text-dark-gray"><?php echo $reg_model->convertViewable($event->event_start, 2); ?> -<br>
				<?php echo $reg_model->convertViewable($event->event_end, 2); ?>
			</p>

			<h5><?php echo L::register_form_registration;?></h5>
			<?php
			$now=new DateTime();
				if(new DateTime($event->reg_end)<=$now){
					$color='w3-dark-gray';
					$text=L::register_form_ended.'<br>'.$reg_model->convertViewable($event->reg_start, 2).' '.L::register_form_and.'<br>'.$reg_model->convertViewable($event->reg_end, 2).'.';
				}
				elseif(new DateTime($event->reg_start)<=$now){
					$color='w3-green';
					$text=L::register_form_currently.'<br>'.$reg_model->convertViewable($event->reg_end, 2).'.';
				}
				elseif($event->pre_reg_start!=0 && new DateTime($event->pre_reg_start)<=$now && $account!=null && $account->status>=PRE_REG){
					$color='w3-green';
					$text=L::register_form_currently.'<br>'.$reg_model->convertViewable($event->reg_end, 2).'.';
				}
				else{
					$color='w3-dark-gray';
					$text=L::register_form_upcoming.'<br>'.($account!=null&&$account->status>=PRE_REG)?
						$reg_model->convertViewable($event->pre_reg_start, 2):
						$reg_model->convertViewable($event->reg_start, 2);
					$text=$text.' '.L::register_form_and.'<br>'.$reg_model->convertViewable($event->reg_end, 2).'.';
				}
			?>
			<p class="w3-text-dark-gray"><?php echo $text; ?></p>

			<h5><?php echo L::register_form_location;?></h5>
			<p class="w3-text-dark-gray"> <a href="https://maps.google.com/?q=<?php echo $event->location;?>" target="_blank"><?php echo $event->location;?> <i class="far fa-external-link"></i></a> </p>

			<h5><?php echo L::register_form_gallery;?></h5>
			<p class="w3-text-dark-gray"><?php echo L::register_form_galleryD;?><br><a href="<?php echo $event->gallery;?>" target="_blank"><?php echo L::register_form_galleryL;?> <i class="far fa-external-link"></i></a></p>

			<h5><?php echo L::register_form_age_h;?></h5>
			<?php
			$age=null;
				if($account!=null){
					$age=(int)date_diff(date_create($event->event_start), date_create($account->dob), true)->format('%y');
				}?>
			<?php if($event->age==0): ?>
				<p class="w3-text-dark-gray"><?php echo L::register_form_age_none;?></p>

			<?php elseif($account==null): ?>
				<p class="w3-text-dark-gray"><?php echo L::register_form_age_noAcc.$event->restricted_age.L::register_form_age_okYears;?></p>

			<?php elseif($age>=$event->age): ?>
				<p class="w3-text-dark-gray"><?php echo L::register_form_age_ok.$event->age.L::register_form_age_okYears;?></p>

			<?php elseif($age<$event->age && $age>=$event->restricted_age): ?>
				<p class="w3-text-orange"><?php echo L::register_form_age_restricted;?><br> <?php echo $event->restricted_text; ?></p>

			<?php else: ?>
				<?php $color='w3-dark-gray'; ?>
				<p class="w3-text-red"><?php echo L::register_form_age_notOk1.$event->restricted_age.L::register_form_age_notOk2.$age.L::register_form_age_notOk3;?></p>
			<?php endif; ?>
			<?php if($account!=null&&!$reg_model->checkProfile()){
				$color='w3-dark-gray';
			} ?>
			<h5><?php echo L::register_form_questions;?></h5>
			<p class="w3-text-dark-gray"><a href="mailto:slofurs@gmail.com" target="_blank"><?php echo L::register_form_email;?></a>, <a href="https://discord.gg/0eaoyLCJ7eiTMBaj" target="_blank">Discord <i class="far fa-external-link"></i></a> </p>
			<!-- FORM BUTTON -->
			<?php if($new_reg): ?>
				<button class="w3-button w3-block w3-round <?php echo $color; ?>" <?php if($color!='w3-green'||$age<$event->restricted_age){echo 'disabled';} else{echo 'onclick="$(\'#register\').show()"';} ?>><?php echo L::register_form_buttonRegister;?></button>
				<?php if($account==null): ?>
					<p><a href="<?php echo URL;?>login"><?php echo L::register_form_login;?></p>
				<?php elseif($account!=null&&!$reg_model->checkProfile()): ?>
					<p><a href="<?php echo URL;?>account/contact"><?php echo L::register_form_completeProfile;?></p>
				<?php endif; ?>
			<?php elseif($color=='w3-dark-gray'): ?>
				<button class="w3-button w3-block w3-round w3-border w3-border-blue" onclick="$('#register').show()";><?php echo L::register_form_buttonView;?></button>
			<?php else: ?>
				<button class="w3-button w3-block w3-round w3-blue" onclick="$('#register').show()";><?php echo L::register_form_buttonEdit;?></button>
			<?php endif; ?>

			<?php if($age>=$event->restricted_age && ($color=='w3-green' || new DateTime($event->reg_end)<=$now)): ?>
				<div id="register" class="w3-modal">
					<div class="w3-modal-content w3-card-4 w3-round-large" style="max-width:600px">
						<?php
							if($new_reg){
								$form_type='new';
								$c='w3-green';
							}
							else{
								$form_type='edit';
								$c='w3-blue';
							}
						?>
						<header class="w3-container <?php echo $c; ?> w3-center roundHeaderTop">
							<span onclick="$('#register').hide()"
							class="w3-button w3-display-topright roundXTop">&times;</span>
							<h2><?php echo L::register_form_modal_h;?></h2>
						</header>
						<div class="w3-container">
							<form action="<?php echo URL; ?>register/<?php echo $form_type; ?>?id=<?php echo $event->id; ?>" method="post">
								<!-- TICKET TYPES / IF FREE show <p>, ELSE radio buttons -->
								<h5><?php echo L::register_form_modal_prices_h;?></h5>
								<?php if($event->regular_price==0): ?>
									<p class="w3-text-dark-gray"><?php echo L::register_form_modal_prices_free;?></p>
								<?php else: ?>
									<table class="w3-table">
										<tr>
											<th class="w3-center"><?php echo L::register_form_modal_selection;?></th>
											<th><?php echo L::register_form_modal_type;?></th>
											<th><?php echo L::register_form_modal_price;?></th>
											<th><?php echo L::register_form_modal_prices_info;?></th>
										</tr>
										<tr>
											<td class="w3-center" style="vertical-align: middle;"><input class="w3-radio" type="radio" name="ticket" value="regular" <?php if(!$new_reg&&$event->ticket=='regular'){echo 'checked';} ?> required></td>
											<td><?php echo L::admin_form_tickets_regular;?></td>
											<td style="vertical-align: middle;"><?php echo $event->regular_price; ?>€</td>
											<td><?php echo nl2br($event->regular_text); ?></td>
										</tr>
										<?php if($event->sponsor_price!=-1): ?>
										<tr>
											<td class="w3-center" style="vertical-align: middle;"><input class="w3-radio" type="radio" name="ticket" value="sponsor" <?php if(!$new_reg&&$event->ticket=='sponsor'){echo 'checked';} ?>></td>
											<td><?php echo L::admin_form_tickets_sponsor;?></td>
											<td style="vertical-align: middle;"><?php echo $event->sponsor_price; ?>€</td>
											<td><?php echo nl2br($event->sponsor_text); ?></td>
										</tr>
										<?php endif; ?>
										<?php if($event->super_price!=-1): ?>
										<tr>
											<td class="w3-center" style="vertical-align: middle;"><input class="w3-radio" type="radio" name="ticket" value="super" <?php if(!$new_reg&&$event->ticket=='sponsor'){echo 'checked';} ?>></td>
											<td><?php echo L::admin_form_tickets_super;?></td>
											<td style="vertical-align: middle;"><?php echo $event->super_price; ?>€</td>
											<td><?php echo nl2br($event->super_text); ?></td>
										</tr>
										<?php endif; ?>
									</table>
								<?php endif; ?>
								<!-- ACCOMODATION / IF NONE show <p>, ELSE radio buttons -->
								<h5><?php echo L::register_form_modal_accomodation_h;?></h5>
								<?php
									$rooms=$reg_model->getAccomodation($evt_id);
									$event_duration=(int)date_diff(date_create($event->event_start), date_create($event->event_end), true)->format('%d');
								?>
								<?php if(count($rooms)>0): ?>
									<table class="w3-table">
										<tr>
											<th class="w3-center"><?php echo L::register_form_modal_selection;?></th>
											<th><?php echo L::register_form_modal_accomodation_type;?></th>
											<th><?php echo L::register_form_modal_price;?></th>
											<th><?php echo L::register_form_modal_accomodation_persons;?> <i class="far fa-info-circle" title="<?php echo L::register_form_modal_accomodation_personsI;?>"></i></th>
											<th><?php echo L::register_form_modal_accomodation_availability;?> <i class="far fa-info-circle" title="<?php echo L::register_form_modal_accomodation_availabilityI;?>"></i></th>
										</tr>
										<tr>
											<td class="w3-center"><input class="w3-radio" type="radio" name="room" value="0" required <?php if(!$new_reg&&$event->room_id==null){echo 'checked';} ?>></td>
											<td><?php echo L::register_form_modal_accomodation_none;?></td>
											<td></td>
											<td></td>
											<td></td>
										</tr>
										<?php foreach($rooms as $room): ?>
											<tr>
												<?php $result=$room->quantity-$reg_model->getBooked($evt_id, $room->id)->quantity;?>
												<td class="w3-center"><input class="w3-radio" type="radio" name="room" value="<?php echo $room->id; ?>"
													<?php
														if(!$new_reg&&$event->room_id==$room->id){
															echo 'checked ';
														}
														if($result<=0){
															echo 'disabled';
															$result=L::register_form_modal_accomodation_noSpace;
														}
														?>>
												</td>
												<td><?php echo $room->type; ?></td>
												<td><?php echo $room->price; ?>€</td>
												<td><?php echo $room->persons; ?></td>
												<td><?php echo $result; ?></td>
											</tr>
										<?php endforeach; ?>
									</table>
								<?php else: ?>
									<p class="w3-text-dark-gray"><?php echo L::register_form_modal_accomodation_noAccomodation;?> <?php if($event_duration>0){ echo L::register_form_modal_accomodation_noAccomodationI; } ?></p>
								<?php endif; ?>
								<!-- OTHER DATA -->
								<h5><?php echo L::register_form_modal_other_h;?></h5>
								<label><?php echo L::register_form_modal_other_notes;?></label> <i class="far fa-info-circle" title="<?php echo L::register_form_modal_other_notesI;?>"></i><br>
								<input type="text" name="notes" value="<?php if(!$new_reg){echo $event->notes;} ?>" class="w3-input"><br>
								<input class="w3-check" type="checkbox" name="fursuit" value="1" <?php if(!$new_reg&&$event->fursuiter==1){echo 'checked';} ?>>
								<label><?php echo L::register_form_modal_other_fursuiter;?></label><br>
								<input class="w3-check" type="checkbox" name="artist" value="1" <?php if(!$new_reg&&$event->artist==1){echo 'checked';} ?>>
								<label><?php echo L::register_form_modal_other_artist;?></label><p></p>
								<?php if($new_reg): ?>
									<p><?php echo L::register_form_modal_rules1;?> <a href="<?php echo URL;?>rules" target="_blank"><?php echo L::register_form_modal_rules2;?> <i class="far fa-external-link"></i></a>.</p>
								<?php endif; ?>
								<div class="w3-center">
									<?php if($new_reg): ?>
										<button type="submit" name="new_registration" class="w3-button w3-green w3-round"><?php echo L::register_form_modal_register;?></button>
									<?php elseif($color=='w3-green'): ?>
										<button type="submit" name="edit_registration" class="w3-button w3-green w3-round"><?php echo L::register_form_modal_save;?></button>
									<?php endif; ?>
									<p>
								</div>
							</form>
						</div>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>

<!-- STATS -->
<div class="w3-container tab" id="Stats" style="display:none">
	<?php if(true): //new DateTime($event->reg_start)<=$now ?>
		<div class="w3-row">
			<div class="w3-half">
				<div class="w3-center w3-padding-16">
					<h4><b><?php echo L::register_form_stats_country;?></b></h4>
				</div>
				<div id="chartCountry" style="width: 100%; height: 300px;"></div>
			</div>
			<div class="w3-half">
				<div class="w3-center w3-padding-16">
					<h4><b><?php echo L::register_form_stats_ticket;?></b></h4>
				</div>
				<div id="chartTicket" style="width: 100%; height: 300px;"></div>
			</div>
		</div>
		<div class="w3-row">
			<div class="w3-half">
				<div class="w3-center w3-padding-16">
					<h4><b><?php echo L::register_form_stats_accomodation;?></b></h4>
				</div>
				<div id="chartRooms" style="width: 100%; height: 300px;"></div>
			</div>
			<div class="w3-half">
				<div class="w3-center w3-padding-16">
					<h4><b><?php echo L::register_form_stats_gender;?></b></h4>
				</div>
				<div id="chartGender" style="width: 100%; height: 300px;"></div>
			</div>
		</div>
		<?php $attendees=$reg_model->getAttendees($evt_id); ?>
		<?php if(count($attendees)>0): ?>
			<div class="w3-row">
				<div class="w3-padding-32">
					<h3><?php echo L::register_form_stats_attendees;?></h3>
				</div>
				<?php foreach($attendees as $attendee): ?>
					<?php if($attendee->ticket=='sponsor'): ?>
						<div class="card w3-yellow" style="width:150px; min-height:150px; cursor: default;">
					<?php elseif($attendee->ticket=='sponsor'): ?>
						<div class="card w3-amber" style="width:150px; min-height:150px; cursor: default;">
					<?php else: ?>
						<div class="card" style="width:150px; min-height:150px; cursor: default;">
					<?php endif; ?>
						<?php if(file_exists('public/accounts/'.$attendee->pfp.'.png')): ?>
							<img src="<?php echo URL.'public/accounts/'.$attendee->pfp; ?>.png" class="roundImg">
						<?php else: ?>
							<img src="<?php echo URL.'public/img/account.png' ?>" class="roundImg">
						<?php endif; ?>
						<div class="w3-center">
							<b><?php echo $attendee->username;?></b><br>
							<?php if($attendee->ticket=='sponsor'): ?>
								<i class="fal fa-heart" title="<?php echo L::register_form_stats_sponsor;?>"></i>
							<?php elseif($attendee->ticket=='super'): ?>
								<i class="fas fa-heart" title="<?php echo L::register_form_stats_super;?>"></i>
							<?php endif; ?>
							<?php if($attendee->fursuiter==1): ?>
								<i class="fas fa-paw" title="<?php echo L::register_form_stats_fursuiter;?>"></i>
							<?php endif; ?>
							<?php if($attendee->artist==1): ?>
								<i class="fas fa-paint-brush" title="<?php echo L::register_form_stats_artist;?>"></i>
							<?php endif; ?>
							<?php if($attendee->artist==0&&$attendee->fursuiter==0): ?>
								<br>
							<?php endif;?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
		<?php
			$fursuits=$reg_model->getFursuits($evt_id);
		?>
		<?php if(count($fursuits)>0): ?>
			<div class="w3-row">
				<div class="w3-padding-32">
					<h3><?php echo L::register_form_stats_fursuiters;?></h3>
				</div>
				<?php foreach($fursuits as $fursuit): ?>
					<div class="card" style="width: 220px; cursor: default;">
						<?php if(file_exists('public/fursuits/'.$fursuit->img.'.png')): ?>
							<img src="<?php echo URL.'public/fursuits/'.$fursuit->img; ?>.png" class="roundImg">
						<?php else: ?>
							<img src="<?php echo URL.'public/img/account.png' ?>" class="roundImg">
						<?php endif; ?>
						<div class="w3-center"><b><?php echo $fursuit->name;?></b><br>
						(<?php echo L::admin_overview_fursuiters_owned;?> <?php echo $fursuit->username;?>)<br>
						<?php echo $fursuit->animal;?></div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	<?php else: ?>
		<div class="w3-container w3-center w3-padding-64">
			<?php echo L::register_form_stats_noStats;?>
		</div>
	<?php endif; ?>
</div>

<!-- CAR SHARING -->
<div class="w3-container tab" id="Rides" style="display:none">
	<h3><?php echo L::register_form_car_h;?></h3>
	<!-- Add new -->
	<?php $limiter=date_create(); $limiter->add(new DateInterval('P1D')); ?>
	<?php if(strpos($this->getBaseUrl(), 'edit')!==false&&date_create($event->event_end)>=$limiter): ?>
		<button onclick="$('#addNew').removeClass('w3-hide')" class="w3-button w3-round w3-border w3-border-blue"><?php echo L::register_form_car_new;?></button><br>
		<div class="w3-hide w3-col l6 m10" id="addNew">
			<br>
			<p><?php echo L::register_form_car_p;?></p>
			<form action="<?php echo URL;?>register/edit?id=<?php echo $event->id;?>" method="post" autocomplete="off">
				<label><?php echo L::register_form_car_direction;?></label> <sup class="w3-text-red">*</sup><br/>
				<input class="w3-radio" type="radio" name="direction" value="0" required>
				<label><?php echo L::register_form_car_to;?></label>
				<input class="w3-radio" type="radio" name="direction" value="1">
				<label><?php echo L::register_form_car_from;?></label><br>
				<label><?php echo L::register_form_car_number;?></label> <sup class="w3-text-red">*</sup> <i class="w3-opacity w3-small"><?php echo L::register_form_car_numberI;?></i>
				<select class="w3-select" name="passengers">
					<option value="1">1</option>
					<option value="2">2</option>
					<option value="3">3</option>
					<option value="4">4</option>
					<option value="5">5</option>
					<option value="6">6</option>
					<option value="7">7</option>
				</select>
				<label><?php echo L::register_form_car_date;?></label> <sup class="w3-text-red">*</sup> <i class="w3-opacity w3-small"><?php echo L::register_form_car_dateI;?></i>
		    <input type="datetime-local" class="w3-input" name="outbound" required>
	      <label><?php echo L::register_form_car_price;?></label> <sup class="w3-text-red">*</sup> <i class="w3-opacity w3-small"><?php echo L::register_form_car_priceI;?></i>
	      <input type="number" class="w3-input" name="price" min="1" required>
				<label><?php echo L::register_form_car_desc;?></label> <sup class="w3-text-red">*</sup> <i class="w3-opacity w3-small"><?php echo L::register_form_car_descI;?></i>
		    <textarea class="w3-input" name="description" required></textarea><p>
					<div class="w3-center">
						<button type="submit" name="new_car_share" class="w3-button w3-round w3-green"><?php echo L::register_form_car_add;?></button>
					</div>
			</form>
		</div><br>
	<?php endif; ?>

	<!-- To event -->
	<?php $carShares=$reg_model->getAllTo($evt_id); ?>
	<div class="w3-row">
		<h1><?php echo L::register_form_car_to;?></h1>
		<?php if(count($carShares)>0): ?>
			<div class="w3-responsive">
				<table class="w3-table w3-striped w3-hoverable w3-centered">
					<tr>
						<th><?php echo L::register_form_car_driver;?></th>
						<th><?php echo L::register_form_car_at;?></th>
						<th><?php echo L::register_form_car_spots;?></th>
						<th><?php echo L::register_form_car_info;?></th>
						<th></th>
					</tr>
					<?php foreach($carShares as $carShare): ?>
						<?php if($account!=null&&$carShare->accId==$_SESSION['account']): ?>
							<div id="<?php echo $carShare->id; ?>" class="w3-modal">
								<div class="w3-modal-content w3-card-4 w3-round-large" style="max-width:600px">
									<header class="w3-container w3-blue w3-center roundHeaderTop">
										<span onclick="$('#<?php echo $carShare->id; ?>').hide()"
										class="w3-button w3-display-topright roundXTop">&times;</span>
										<h2><?php echo L::register_form_car_edit;?></h2>
									</header>
									<div class="w3-container">
										<form action="<?php echo URL;?>register/edit?id=<?php echo $event->id;?>&carshare=<?php echo $carShare->id;?>" method="post">
											<label><?php echo L::register_form_car_direction;?></label> <sup class="w3-text-red">*</sup><br/>
											<input class="w3-radio" type="radio" name="direction" value="0" required <?php if($carShare->direction==0){ echo 'checked';} ?>>
											<label><?php echo L::register_form_car_to;?></label>
											<input class="w3-radio" type="radio" name="direction" value="1" <?php if($carShare->direction==1){ echo 'checked';} ?>>
											<label><?php echo L::register_form_car_from;?></label><br>
											<label><?php echo L::register_form_car_number;?></label> <sup class="w3-text-red">*</sup> <i class="w3-opacity w3-small"><?php echo L::register_form_car_numberI;?></i>
											<select class="w3-select" name="passengers">
												<option value="1" <?php if($carShare->passengers==1){ echo 'selected';} ?>>1</option>
												<option value="2" <?php if($carShare->passengers==2){ echo 'selected';} ?>>2</option>
												<option value="3" <?php if($carShare->passengers==3){ echo 'selected';} ?>>3</option>
												<option value="4" <?php if($carShare->passengers==4){ echo 'selected';} ?>>4</option>
												<option value="5" <?php if($carShare->passengers==5){ echo 'selected';} ?>>5</option>
												<option value="6" <?php if($carShare->passengers==6){ echo 'selected';} ?>>6</option>
												<option value="7" <?php if($carShare->passengers==7){ echo 'selected';} ?>>7</option>
											</select>
											<label><?php echo L::register_form_car_date;?></label> <sup class="w3-text-red">*</sup> <i class="w3-opacity w3-small"><?php echo L::register_form_car_dateI;?></i>
									    <input type="datetime-local" class="w3-input" name="outbound" required value="<?php echo $reg_model->convert($carShare->outbound); ?>">
								      <label><?php echo L::register_form_car_price;?></label> <sup class="w3-text-red">*</sup> <i class="w3-opacity w3-small"><?php echo L::register_form_car_priceI;?></i>
								      <input type="number" class="w3-input" name="price" min="1" required value="<?php echo $carShare->price; ?>">
											<label><?php echo L::register_form_car_desc;?></label> <sup class="w3-text-red">*</sup> <i class="w3-opacity w3-small"><?php echo L::register_form_car_descI;?></i>
									    <textarea class="w3-input" name="description" required><?php echo $carShare->description; ?></textarea><p>
												<div class="w3-center">
													<button type="submit" name="edit_car_share" class="w3-button w3-round w3-green"><?php echo L::register_form_car_save;?></button>
													<button type="submit" name="delete_car_share" class="w3-button w3-round w3-border w3-border-red"><?php echo L::register_form_car_delete;?></button>
												</div><br>
										</form>
									</div>
								</div>
							</div>
						<?php endif; ?>
						<tr>
							<td><?php echo $carShare->username; ?></td>
							<td><?php echo $reg_model->convertViewable($carShare->outbound, 2); ?></td>
							<td><?php echo $carShare->passengers; ?></td>
							<td><?php echo nl2br($carShare->description); ?></td>
							<td>
								<?php if($account!=null&&$carShare->accId==$_SESSION['account']): ?>
									<button type="button" class="w3-button w3-border w3-border-blue w3-round" onclick="$('#<?php echo $carShare->id; ?>').show()"><?php echo L::admin_dash_edit;?></button>
								<?php endif; ?>
							</td>
						</tr>
					<?php endforeach;?>
				</table>
			</div>
		<?php else: ?>
			<div class="w3-container">
				<?php echo L::register_form_car_none;?></i>
			</div>
		<?php endif; ?>
	</div>

	<!-- From event -->
	<?php $carShares=$reg_model->getAllFrom($evt_id); ?>
	<div class="w3-row">
		<h1><?php echo L::register_form_car_from;?></h1>
		<?php if(count($carShares)>0): ?>
			<div class="w3-responsive">
				<table class="w3-table w3-striped w3-hoverable w3-centered">
					<tr>
						<th><?php echo L::register_form_car_driver;?></th>
						<th><?php echo L::register_form_car_at;?></th>
						<th><?php echo L::register_form_car_spots;?></th>
						<th><?php echo L::register_form_car_info;?></th>
						<th></th>
					</tr>
					<?php foreach($carShares as $carShare): ?>
						<?php if($carShare->accId==$_SESSION['account']): ?>
							<div id="<?php echo $carShare->id; ?>" class="w3-modal">
								<div class="w3-modal-content w3-card-4 w3-round-large" style="max-width:600px">
									<header class="w3-container w3-blue w3-center roundHeaderTop">
										<span onclick="$('#<?php echo $carShare->id; ?>').hide()"
										class="w3-button w3-display-topright roundXTop">&times;</span>
										<h2><?php echo L::register_form_car_edit;?></h2>
									</header>
									<div class="w3-container">
										<form action="<?php echo URL;?>register/edit?id=<?php echo $event->id;?>&carshare=<?php echo $carShare->id;?>" method="post">
											<label><?php echo L::register_form_car_direction;?></label> <sup class="w3-text-red">*</sup><br/>
											<input class="w3-radio" type="radio" name="direction" value="0" required <?php if($carShare->direction==0){ echo 'checked';} ?>>
											<label><?php echo L::register_form_car_to;?></label>
											<input class="w3-radio" type="radio" name="direction" value="1" <?php if($carShare->direction==1){ echo 'checked';} ?>>
											<label><?php echo L::register_form_car_from;?></label><br>
											<label><?php echo L::register_form_car_number;?></label> <sup class="w3-text-red">*</sup> <i class="w3-opacity w3-small"><?php echo L::register_form_car_numberI;?></i>
											<select class="w3-select" name="passengers">
												<option value="1" <?php if($carShare->passengers==1){ echo 'selected';} ?>>1</option>
												<option value="2" <?php if($carShare->passengers==2){ echo 'selected';} ?>>2</option>
												<option value="3" <?php if($carShare->passengers==3){ echo 'selected';} ?>>3</option>
												<option value="4" <?php if($carShare->passengers==4){ echo 'selected';} ?>>4</option>
												<option value="5" <?php if($carShare->passengers==5){ echo 'selected';} ?>>5</option>
												<option value="6" <?php if($carShare->passengers==6){ echo 'selected';} ?>>6</option>
												<option value="7" <?php if($carShare->passengers==7){ echo 'selected';} ?>>7</option>
											</select>
											<label><?php echo L::register_form_car_date;?></label> <sup class="w3-text-red">*</sup> <i class="w3-opacity w3-small"><?php echo L::register_form_car_dateI;?></i>
									    <input type="datetime-local" class="w3-input" name="outbound" required value="<?php echo $reg_model->convert($carShare->outbound); ?>">
								      <label><?php echo L::register_form_car_price;?></label> <sup class="w3-text-red">*</sup> <i class="w3-opacity w3-small"><?php echo L::register_form_car_priceI;?></i>
								      <input type="number" class="w3-input" name="price" min="1" required value="<?php echo $carShare->price; ?>">
											<label><?php echo L::register_form_car_desc;?></label> <sup class="w3-text-red">*</sup> <i class="w3-opacity w3-small"><?php echo L::register_form_car_descI;?></i>
									    <textarea class="w3-input" name="description" required><?php echo $carShare->description; ?></textarea><p>
												<div class="w3-center">
													<button type="submit" name="edit_car_share" class="w3-button w3-round w3-green"><?php echo L::register_form_car_save;?></button>
													<button type="submit" name="delete_car_share" class="w3-button w3-round w3-border w3-border-red"><?php echo L::register_form_car_delete;?></button>
												</div><br>
										</form>
									</div>
								</div>
							</div>
						<?php endif; ?>
						<tr>
							<td><?php echo $carShare->username; ?></td>
							<td><?php echo $reg_model->convertViewable($carShare->outbound, 2); ?></td>
							<td><?php echo $carShare->passengers; ?></td>
							<td><?php echo nl2br($carShare->description); ?></td>
							<td>
								<?php if($carShare->accId==$_SESSION['account']): ?>
									<button type="button" class="w3-button w3-border w3-border-blue w3-round" onclick="$('#<?php echo $carShare->id; ?>').show()"><?php echo L::admin_dash_edit;?></button>
								<?php endif; ?>
							</td>
						</tr>
					<?php endforeach;?>
				</table>
			</div>
		<?php else: ?>
			<div class="w3-container">
				<?php echo L::register_form_car_none;?></i>
			</div>
		<?php endif; ?>
	</div>
</div>

<script>
$(document).ready(function() {
  validate();
  $(document).on("keyup", "input", validate);
  $("input[type=checkbox][name='ticket']").on("change", validate);
  $("input[type=datetime-local]").on("change", validate);
});
function validate(){
	var dateOK=true;
	var now=new Date();
	//NOW<=PRE-REG<REG. START
	if(now>new Date($("input[name='pre_reg']").val())||new Date($("input[name='pre_reg']").val())>new Date($("input[name='reg_start']").val())){
		$("input[name='pre_reg']").addClass("w3-border w3-border-red w3-round");
		dateOK=false;
	}
	else{
		$("input[name='pre_reg']").removeClass("w3-border w3-border-red w3-round");
	}
	//NOW<=REG. START<START
	if(now>new Date($("input[name='reg_start']").val())||new Date($("input[name='reg_start']").val())>=new Date($("input[name='start']").val())){
		$("input[name='reg_start']").addClass("w3-border w3-border-red w3-round");
		dateOK=false;
	}
	else{
		$("input[name='reg_start']").removeClass("w3-border w3-border-red w3-round");
	}
	//REG. START<REG. END<=START
	if($("input[name='reg_end']").val()!=""&&(new Date($("input[name='reg_start']").val())>=new Date($("input[name='reg_end']").val())||new Date($("input[name='reg_end']").val())>new Date($("input[name='start']").val()))){
		$("input[name='reg_end']").addClass("w3-border w3-border-red w3-round");
		dateOK=false;
	}
	else{
		$("input[name='reg_end']").removeClass("w3-border w3-border-red w3-round");
	}
	//NOW<START
	if(now>new Date($("input[name='start']").val())){
		$("input[name='start']").addClass("w3-border w3-border-red w3-round");
		dateOK=false;
	}
	else{
		$("input[name='start']").removeClass("w3-border w3-border-red w3-round");
	}
	//END>START
	if(new Date($("input[name='start']").val())>=new Date($("input[name='end']").val())){
		$("input[name='end']").addClass("w3-border w3-border-red w3-round");
		dateOK=false;
	}
	else{
		$("input[name='end']").removeClass("w3-border w3-border-red w3-round");
	}
	//count required input fields and if they have data
	var inputsWVal=0;
	var requiredInputs=0;
	var myInputs=$("input:not([type='submit'])");
	myInputs.each(function(e){
		if($(this).prop("required")){
			requiredInputs++;
			if($(this).val()){
				inputsWVal++;
			}
		}
	});
	if($("#checksuper").is(":checked")){
		$("#checksponsor").prop("checked", true);
		price("sponsor");
	}
	if($("#checksponsor").is(":checked")){
		$("#checkregular").prop("checked", true);
		price("regular");
	}
	if($("#checkregular").is(":checked")){
		$("#checkfree").prop("checked", false);
		console.log("uncheck");
	}

	//check if required and inputed equals (all required filled) and if at least one price category is selected
	if(inputsWVal==requiredInputs&&dateOK&&$("input[type=checkbox][name='ticket']:checked").length>0){
		$("#submitBtn").prop("disabled", false);
	}
	else{
		$("#submitBtn").prop("disabled", true);
	}
}

function openTab(evt, tabName){
  var i, x, tablinks;
  x=document.getElementsByClassName("tab");
  for(i=0;i<x.length;i++){
    x[i].style.display="none";
  }
  tablinks=document.getElementsByClassName("tablink");
  for(i=0;i<x.length;i++){
    tablinks[i].className=tablinks[i].className.replace(" w3-border-blue", "");
  }
  document.getElementById(tabName).style.display="block";
  evt.currentTarget.firstElementChild.className+=" w3-border-blue";
}
</script>

<?php if(true): //new DateTime($event->reg_start)<=$now ?>
	<!-- Resources -->
	<script src="https://www.amcharts.com/lib/4/core.js"></script>
	<script src="https://www.amcharts.com/lib/4/charts.js"></script>
	<script src="https://www.amcharts.com/lib/4/themes/dataviz.js"></script>
	<script src="https://www.amcharts.com/lib/4/themes/animated.js"></script>

	<!-- Chart code -->
	<script>
	am4core.ready(function() {

	// Themes begin
	am4core.useTheme(am4themes_dataviz);
	am4core.useTheme(am4themes_animated);
	// Themes end

	// Create chart instance
	var chart = am4core.create("chartCountry", am4charts.PieChart);

	<?php
		$text='';
		$countries=$reg_model->getCountries($evt_id);
		foreach ($countries as $country){
			$text.='{"country": "'.$country->country.'", "quantity": '.$country->counter.'},';
		}
		$text=substr($text, 0, -1);
	?>
	chart.data=[<?php echo $text; ?>];

	// Set inner radius
	chart.innerRadius = am4core.percent(50);

	// Add and configure Series
	var pieSeries = chart.series.push(new am4charts.PieSeries());
	pieSeries.dataFields.value = "quantity";
	pieSeries.dataFields.category = "country";
	pieSeries.slices.template.stroke = am4core.color("#fff");
	pieSeries.slices.template.strokeWidth = 2;
	pieSeries.slices.template.strokeOpacity = 1;

	// This creates initial animation
	pieSeries.hiddenState.properties.opacity = 1;
	pieSeries.hiddenState.properties.endAngle = -90;
	pieSeries.hiddenState.properties.startAngle = -90;

	var chart = am4core.create("chartTicket", am4charts.PieChart);

	<?php
		$text='';
		$tickets=$reg_model->getTickets($evt_id);
		if($event->regular_price==0){
			foreach ($tickets as $ticket){
				$text.='{"ticket": "Free", "quantity": '.$ticket->counter.'}';
			}
		}
		else{
			foreach ($tickets as $ticket){
				$text.='{"ticket": "'.ucfirst($ticket->ticket).'", "quantity": '.$ticket->counter.'},';
			}
			$text=substr($text, 0, -1);
		}

	?>
	chart.data=[<?php echo $text; ?>];

	// Set inner radius
	chart.innerRadius = am4core.percent(50);

	// Add and configure Series
	var pieSeries = chart.series.push(new am4charts.PieSeries());
	pieSeries.dataFields.value = "quantity";
	pieSeries.dataFields.category = "ticket";
	pieSeries.slices.template.stroke = am4core.color("#fff");
	pieSeries.slices.template.strokeWidth = 2;
	pieSeries.slices.template.strokeOpacity = 1;

	// This creates initial animation
	pieSeries.hiddenState.properties.opacity = 1;
	pieSeries.hiddenState.properties.endAngle = -90;
	pieSeries.hiddenState.properties.startAngle = -90;

	var chart = am4core.create("chartGender", am4charts.PieChart);

	<?php
		$text='';
		$genders=$reg_model->getGenders($evt_id);
		foreach ($genders as $gender){
			if($gender->gender=='silent'){
				$text.='{"gender": "Do not wish to answer", "quantity": '.$gender->counter.'},';
			}
			else{
				$text.='{"gender": "'.ucfirst($gender->gender).'", "quantity": '.$gender->counter.'},';
			}
		}
		$text=substr($text, 0, -1);
	?>
	chart.data=[<?php echo $text; ?>];

	// Set inner radius
	chart.innerRadius = am4core.percent(50);

	// Add and configure Series
	var pieSeries = chart.series.push(new am4charts.PieSeries());
	pieSeries.dataFields.value = "quantity";
	pieSeries.dataFields.category = "gender";
	pieSeries.slices.template.stroke = am4core.color("#fff");
	pieSeries.slices.template.strokeWidth = 2;
	pieSeries.slices.template.strokeOpacity = 1;

	// This creates initial animation
	pieSeries.hiddenState.properties.opacity = 1;
	pieSeries.hiddenState.properties.endAngle = -90;
	pieSeries.hiddenState.properties.startAngle = -90;

	var chart = am4core.create("chartRooms", am4charts.PieChart);

	<?php
		$text='';
		$rooms=$reg_model->getRooms($evt_id);
		foreach ($rooms as $room){
			$text.='{"room": "'.ucfirst($room->type).'", "quantity": '.$room->counter.'},';
		}
		$room=$reg_model->getNoRoom($evt_id);
		if($room->counter!=0){
			$text.='{"room": "No accomodation", "quantity": '.$room->counter.'},';
		}
		$text=substr($text, 0, -1);
	?>
	chart.data=[<?php echo $text; ?>];

	// Set inner radius
	chart.innerRadius = am4core.percent(50);

	// Add and configure Series
	var pieSeries = chart.series.push(new am4charts.PieSeries());
	pieSeries.dataFields.value = "quantity";
	pieSeries.dataFields.category = "room";
	pieSeries.slices.template.stroke = am4core.color("#fff");
	pieSeries.slices.template.strokeWidth = 2;
	pieSeries.slices.template.strokeOpacity = 1;

	// This creates initial animation
	pieSeries.hiddenState.properties.opacity = 1;
	pieSeries.hiddenState.properties.endAngle = -90;
	pieSeries.hiddenState.properties.startAngle = -90;
});
<?php endif; ?>
</script>
