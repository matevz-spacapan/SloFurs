<div class="w3-main">
<div class="w3-blue">
	<div class="w3-container">
		<h1>New registration: <?php echo $event->name; ?></h1>
	</div>
</div>
<div class="w3-container">
	<div class="w3-container" style="margin: 0 auto; width: 50%">
			<div class="w3-row">
				<div class="w3-col l8 m6">
					<h5>Description</h5>
					<p class="w3-text-dark-gray"><?php echo nl2br($event->description); ?></p>
				</div>
				<div class="w3-container w3-rest">
					<h5>Date & Time</h5>
					<p class="w3-text-dark-gray"><?php echo $reg_model->convertViewable($event->event_start, true).' '.$reg_model->convertViewable($event->event_start, false); ?> -<br>
						<?php echo $reg_model->convertViewable($event->event_end, true).' '.$reg_model->convertViewable($event->event_end, false); ?>
					</p>

					<h5>Location</h5>
					<p class="w3-text-dark-gray"><?php echo $event->location; ?></p>

					<h5>Age restrictions</h5>
					<?php $age=(int)date_diff(date_create($event->event_start), date_create($account->dob), true)->format('%y'); ?>
					<?php if($event->age==0): ?>
						<p class="w3-text-dark-gray">There are no age restrictions for this event.</p>

					<?php elseif($age>=$event->age): ?>
						<p class="w3-text-dark-gray">You are old enough to attend this event. The minimum age is <?php echo $event->age; ?>.<br>There can also be attendees aged <?php echo $event->restricted_age; ?> to <?php echo $event->age; ?> with some restrictions applying to them.</p>

					<?php elseif($age<$event->age && $age>=$event->restricted_age): ?>
						<p class="w3-text-orange">You are old enough to attend this event, but with restrictions.<br>The restrictions are:<br> <?php echo $event->restricted_text; ?></p>

					<?php else: ?>
						<p class="w3-text-red">You are not old enough to attend this event. The minimum age for it is <?php echo $event->restricted_age; ?> years and you will be <?php echo $age; ?> years old at the start of the event.</p>
					<?php endif; ?>

					<button class="w3-button w3-block w3-round w3-green" <?php if($age<$event->restricted_age){ echo 'disabled';} else{ echo 'onclick="$(\'#register\').show()"'; } ?>>Register for event!</button>
					<?php if($age>=$event->restricted_age): ?>
						<div id="register" class="w3-modal">
							<div class="w3-modal-content w3-card-4 w3-round-large" style="max-width:600px">
								<header class="w3-container w3-green w3-center roundHeaderTop"> 
									<span onclick="$('#register').hide()" 
									class="w3-button w3-display-topright roundXTop">&times;</span>
									<h2>Registration form</h2>
								</header>
								<div class="w3-container">
									<form action="<?php echo URL; ?>register/new?id=<?php echo $event->id; ?>" method="post">
										<!-- TICKET TYPES / IF FREE state it, ELSE radio buttons -->
										<h5>Attendance prices</h5>
										<?php if($event->regular_price==0): ?>
											<p>This event is free of charge.</p>
										<?php else: ?>
											<table class="w3-table">
												<tr>
													<th class="w3-center">Selection</th>
													<th>Price</th>
													<th>Additional info</th>
												</tr>
												<tr>
													<td class="w3-center"><input class="w3-radio" type="radio" name="ticket" value="regular" checked></td>
													<td><?php echo $event->regular_price; ?>€</td>
													<td><?php echo nl2br($event->regular_text); ?></td>
												</tr>
												<?php if($event->sponsor_price!=-1): ?>
												<tr>
													<td class="w3-center"><input class="w3-radio" type="radio" name="ticket" value="sponsor"></td>
													<td><?php echo $event->sponsor_price; ?>€</td>
													<td><?php echo nl2br($event->sponsor_text); ?></td>
												</tr>
												<?php endif; ?>
												<?php if($event->super_price!=-1): ?>
												<tr>
													<td class="w3-center"><input class="w3-radio" type="radio" name="ticket" value="super"></td>
													<td><?php echo $event->super_price; ?>€</td>
													<td><?php echo nl2br($event->super_text); ?></td>
												</tr>
												<?php endif; ?>
											</table>
										<?php endif; ?>
										<!-- ACCOMODATION / IF NONE skip, ELSE dropdown -->
										<h5>Accomodation</h5>
										<?php
											$rooms=$reg_model->getAccomodation($event->id);
											$event_duration=(int)date_diff(date_create($event->event_start), date_create($event->event_end), true)->format('%d');
										?>
										<?php if(count($rooms)>0): ?>
											<table class="w3-table">
												<tr>
													<th class="w3-center">Selection</th>
													<th>Room type</th>
													<th>Price</th>
													<th>Persons/room <i class="far fa-info-circle" title="You are making a reservation for only 1 spot in a room. Room sharing is done at a later step."></i></th>
													<th>Availability <i class="far fa-info-circle" title="This is the number of rooms of this type still available when you loaded the page."></i></th>
												</tr>
												<tr>
													<td class="w3-center"><input class="w3-radio" type="radio" name="room" value="0"></td>
													<td>None</td>
													<td></td>
													<td></td>
													<td></td>
												</tr>
												<?php foreach($rooms as $room): ?>
													<tr>
														<td class="w3-center"><input class="w3-radio" type="radio" name="room" value="<?php echo $room->id; ?>"></td>
														<td><?php echo $room->type; ?></td>
														<td><?php echo $room->price; ?></td>
														<td><?php echo $room->persons; ?></td>
														<?php
															$result=$room->quantity-$reg_model->getAvailable($event->id, $room->id)->quantity;
															if($result<=0){
																$result='No availability - waitlist.';
															}
														?>
														<td><?php echo $result; ?></td>
													</tr>
												<?php endforeach; ?>
											</table>
										<?php else: ?>
											<p class="w3-text-dark-gray">This event has no accomodation options. <?php if($event_duration>0){ echo 'Since this is a multi-day event, we recommend you check the description for accomodation recommendations, if there are any.'; } ?></p>
										<?php endif; ?>
										<div class="w3-center">
											<p>
											<button type="submit" name="new_registration" class="w3-button w3-green w3-round">Register</button>
										</div>
									</form>
								</div>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div>
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
</script>