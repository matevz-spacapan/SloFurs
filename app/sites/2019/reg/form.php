<div class="w3-main">
<div class="w3-blue">
	<div class="w3-container">
		<?php if($new_reg): ?>
			<h1><?php echo L::register_form_h.': '.$event->name;?></h1>
		<?php else: ?>
			<h1><?php echo $event->name; ?></h1>
		<?php endif; ?>
	</div>
</div>
<div class="w3-row w3-center">
	<a href="javascript:void(0)" onclick="openTab(event, 'Event');">
		<div class="w3-half tablink w3-bottombar w3-hover-light-grey w3-padding w3-border-blue"><?php echo L::register_form_details;?></div>
	</a>
	<a href="javascript:void(0)" onclick="openTab(event, 'Stats');">
		<div class="w3-half tablink w3-bottombar w3-hover-light-grey w3-padding"><?php echo L::register_form_statistics;?></div>
	</a>
</div>
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
				elseif($event->pre_reg_start!=0 && new DateTime($event->pre_reg_start)<=$now && $account->status>=PRE_REG){
					$color='w3-green';
					$text=L::register_form_currently.'<br>'.$reg_model->convertViewable($event->reg_end, 2).'.';
				}
				else{
					$color='w3-dark-gray';
					$text=L::register_form_upcoming.'<br>'.($account->status>=PRE_REG)?
						$reg_model->convertViewable($event->pre_reg_start, 2):
						$reg_model->convertViewable($event->reg_start, 2);
					$text=$text.' '.L::register_form_and.'<br>'.$reg_model->convertViewable($event->reg_end, 2).'.';
				}
			?>
			<p class="w3-text-dark-gray"><?php echo $text; ?></p>

			<h5><?php echo L::register_form_location;?></h5>
			<p class="w3-text-dark-gray"> <a href="https://maps.google.com/?q=<?php echo $event->location;?>" target="_blank"><?php echo $event->location;?> <i class="far fa-external-link"></i></a> </p>

			<h5><?php echo L::register_form_age_h;?></h5>
			<?php $age=(int)date_diff(date_create($event->event_start), date_create($account->dob), true)->format('%y'); ?>
			<?php if($event->age==0): ?>
				<p class="w3-text-dark-gray"><?php echo L::register_form_age_none;?></p>

			<?php elseif($age>=$event->age): ?>
				<p class="w3-text-dark-gray"><?php echo L::register_form_age_ok.$event->age.L::register_form_age_okYears;?></p>

			<?php elseif($age<$event->age && $age>=$event->restricted_age): ?>
				<p class="w3-text-orange"><?php echo L::register_form_age_restricted;?><br> <?php echo $event->restricted_text; ?></p>

			<?php else: ?>
				<?php $color='w3-dark-gray'; ?>
				<p class="w3-text-red"><?php echo L::register_form_age_notOk1.$event->restricted_age.L::register_form_age_notOk2.$age.L::register_form_age_notOk3;?></p>
			<?php endif; ?>
			<!-- FORM BUTTON -->
			<?php if($new_reg): ?>
				<button class="w3-button w3-block w3-round <?php echo $color; ?>" <?php if($color!='w3-green'||$age<$event->restricted_age){echo 'disabled';} else{echo 'onclick="$(\'#register\').show()"';} ?>><?php echo L::register_form_buttonRegister;?></button>
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
								<!-- TICKET TYPES / IF FREE state it, ELSE radio buttons -->
								<h5><?php echo L::register_form_modal_prices_h;?></h5>
								<?php if($event->regular_price==0): ?>
									<p class="w3-text-dark-gray"><?php echo L::register_form_modal_prices_free;?></p>
								<?php else: ?>
									<table class="w3-table">
										<tr>
											<th class="w3-center"><?php echo L::register_form_modal_selection;?></th>
											<th><?php echo L::register_form_modal_price;?></th>
											<th><?php echo L::register_form_modal_prices_info;?></th>
										</tr>
										<tr>
											<td class="w3-center" style="vertical-align: middle;"><input class="w3-radio" type="radio" name="ticket" value="regular" <?php if(!$new_reg&&$event->ticket=='regular'){echo 'checked';} ?> required> <?php echo L::admin_form_tickets_regular;?></td>
											<td style="vertical-align: middle;"><?php echo $event->regular_price; ?>€</td>
											<td><?php echo nl2br($event->regular_text); ?></td>
										</tr>
										<?php if($event->sponsor_price!=-1): ?>
										<tr>
											<td class="w3-center" style="vertical-align: middle;"><input class="w3-radio" type="radio" name="ticket" value="sponsor" <?php if(!$new_reg&&$event->ticket=='sponsor'){echo 'checked';} ?>> <?php echo L::admin_form_tickets_sponsor;?></td>
											<td style="vertical-align: middle;"><?php echo $event->sponsor_price; ?>€</td>
											<td><?php echo nl2br($event->sponsor_text); ?></td>
										</tr>
										<?php endif; ?>
										<?php if($event->super_price!=-1): ?>
										<tr>
											<td class="w3-center" style="vertical-align: middle;"><input class="w3-radio" type="radio" name="ticket" value="super" <?php if(!$new_reg&&$event->ticket=='sponsor'){echo 'checked';} ?>> <?php echo L::admin_form_tickets_super;?></td>
											<td style="vertical-align: middle;"><?php echo $event->super_price; ?>€</td>
											<td><?php echo nl2br($event->super_text); ?></td>
										</tr>
										<?php endif; ?>
									</table>
								<?php endif; ?>
								<!-- ACCOMODATION / IF NONE skip, ELSE dropdown -->
								<h5><?php echo L::register_form_modal_accomodation_h;?></h5>
								<?php
									if($new_reg){
										$rooms=$reg_model->getAccomodation($event->id);
									}
									else{
										$rooms=$reg_model->getAccomodation($event->event_id);
									}
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
												<td class="w3-center"><input class="w3-radio" type="radio" name="room" value="<?php echo $room->id; ?>" <?php if(!$new_reg&&$event->room_id==$room->id){echo 'checked';} ?>></td>
												<td><?php echo $room->type; ?></td>
												<td><?php echo $room->price; ?>€</td>
												<td><?php echo $room->persons; ?></td>
												<?php
													$result=$room->quantity-$reg_model->getBooked($event->id, $room->id)->quantity;
													if($result<=0){
														$result=L::register_form_modal_accomodation_noSpace;
													}
												?>
												<td><?php echo $result; ?></td>
											</tr>
										<?php endforeach; ?>
									</table>
								<?php else: ?>
									<p class="w3-text-dark-gray"><?php echo L::register_form_modal_accomodation_noAccomodation;?> <?php if($event_duration>0){ echo L::register_form_modal_accomodation_noAccomodationI; } ?></p>
								<?php endif; ?>
								<!-- OTHER DATA -->
								<h5><?php echo L::register_form_modal_other_h;?></h5>
								<input class="w3-check" type="checkbox" name="fursuit" value="1" <?php if(!$new_reg&&$event->fursuiter==1){echo 'checked';} ?>>
								<label><?php echo L::register_form_modal_other_fursuiter;?></label><br>
								<input class="w3-check" type="checkbox" name="artist" value="1" <?php if(!$new_reg&&$event->artist==1){echo 'checked';} ?>>
								<label><?php echo L::register_form_modal_other_artist;?></label>
								<div class="w3-center">
									<p>
									<?php if($new_reg): ?>
										<button type="submit" name="new_registration" class="w3-button w3-green w3-round"><?php echo L::register_form_modal_register;?></button>
									<?php elseif($color=='w3-green'): ?>
										<button type="submit" name="edit_registration" class="w3-button w3-green w3-round"><?php echo L::register_form_modal_save;?></button>
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

<!-- STATS -->
<div class="w3-container tab" id="Stats">
	<?php if(new DateTime($event->reg_start)<=$now): ?>
		<div class="w3-row">
			<div class="w3-half">
				<div class="w3-center w3-padding-16">
					<?php echo L::register_form_stats_country;?>
				</div>
				<div id="chartCountry" style="width: 100%; height: 300px;"></div>
			</div>
			<div class="w3-half">
				<div class="w3-center w3-padding-16">
					<?php echo L::register_form_stats_ticket;?>
				</div>
				<div id="chartTicket" style="width: 100%; height: 300px;"></div>
			</div>
		</div>
		<div class="w3-row">
			<div class="w3-half">
				<div class="w3-center w3-padding-16">
					<?php echo L::register_form_stats_accomodation;?>
				</div>
				<div id="chartRooms" style="width: 100%; height: 300px;"></div>
			</div>
			<div class="w3-half">
				<div class="w3-center w3-padding-16">
					<?php echo L::register_form_stats_gender;?>
				</div>
				<div id="chartGender" style="width: 100%; height: 300px;"></div>
			</div>
		</div>
		<?php $attendees=$reg_model->getAttendees($event->id); ?>
		<?php if(count($attendees)>0): ?>
			<div class="w3-row">
				<div class="w3-padding-32">
					<h3><?php echo L::register_form_stats_attendees;?></h3>
				</div>
				<?php foreach($attendees as $attendee): ?>
					<?php if($attendee->ticket=='sponsor'): ?>
						<div class="card w3-yellow" style="width:150px; min-height:150px;">
					<?php elseif($attendee->ticket=='sponsor'): ?>
						<div class="card w3-amber" style="width:150px; min-height:150px;">
					<?php else: ?>
						<div class="card" style="width:150px; min-height:150px;">
					<?php endif; ?>
						<?php if(file_exists('public/accounts/'.$attendee->pfp.'.png')): ?>
							<img src="<?php echo URL.'public/accounts/'.$attendee->pfp; ?>.png" class="roundImg">
						<?php else: ?>
							<img src="<?php echo URL.'public/img/account.png' ?>" class="roundImg">
						<?php endif; ?>
						<div class="w3-center">
							<b><?php echo $attendee->username;?></b><br>
							<?php if($attendee->fursuiter==1): ?>
								<i class="fas fa-paw" title="<?php echo L::register_form_stats_fursuiter;?>"></i>
							<?php endif; ?>
							<?php if($attendee->artist==1): ?>
								<i class="fas fa-paint-brush" title="<?php echo L::register_form_stats_artist;?>"></i>
							<?php endif; ?>
							<?php if($attendee->ticket=='sponsor'): ?>
								<i class="fal fa-heart" title="<?php echo L::register_form_stats_sponsor;?>"></i>
							<?php elseif($attendee->ticket=='super'): ?>
								<i class="fas fa-heart" title="<?php echo L::register_form_stats_super;?>"></i>
							<?php endif; ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
		<?php
			$event_model=$this->loadSQL('EventModel');
			$fursuits=$event_model->getFursuits($event->id);
		?>
		<?php if(count($fursuits)>0): ?>
			<div class="w3-row">
				<div class="w3-padding-32">
					<h3><?php echo L::register_form_stats_fursuiters;?></h3>
				</div>
				<?php foreach($fursuits as $fursuit): ?>
					<div class="card" style="width: 220px;">
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
	$countries=$reg_model->getCountries($event->id);
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
	$tickets=$reg_model->getTickets($event->id);
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
	$genders=$reg_model->getGenders($event->id);
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
	$rooms=$reg_model->getRooms($event->id);
	foreach ($rooms as $room){
		$text.='{"room": "'.ucfirst($room->type).'", "quantity": '.$room->counter.'},';
	}
	$room=$reg_model->getNoRoom($event->id);
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
</script>
