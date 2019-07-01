<div class="w3-main" style="margin-left:200px">
<div class="w3-orange">
	<button class="w3-button w3-orange w3-xlarge w3-hide-large" onclick="side_open()">&#9776;</button>
	<div class="w3-container">
		<h1>Events manager</h1>
	</div>
</div>
<div class="w3-container">
	<!-- NEW EVENT COLUMN -->
	<div class="w3-container">
		<h3>New event</h3>
		<div class="w3-cell-row w3-light-gray w3-round-large" style="width: auto!important;">
			<form action="<?php echo URL; ?>admin/update/1" method="post">
				<div class="w3-container w3-cell">
					<label>Event type</label><br/>
					<input type="hidden" id="sync_type" value="">
					<input class="w3-radio" type="radio" name="type" value="meet" id="meet" required>
					<label>Meet</label>
					<input class="w3-radio" type="radio" name="type" value="con" id="con">
					<label>Convention</label><p>
					<label>Name</label>
					<input type="text" class="w3-input" name="name" required>
					<label>Start</label>
					<input type="datetime-local" class="w3-input" name="start" id="start0" onblur="checkDate('start0')" required>
					<label>End</label>
					<input type="datetime-local" class="w3-input" name="end" required>
					<label>Location</label>
					<input type="text" class="w3-input" name="location" required>
				</div>
				<div class="w3-container w3-cell">
					<label>Reg. start</label>
					<input type="datetime-local" class="w3-input" name="reg_start" required>
					<label>Pre-reg start</label> <i class="w3-opacity w3-small">(optional)</i>
					<input type="datetime-local" class="w3-input" name="pre_reg">
					<label>Reg. end</label> <i class="w3-opacity w3-small">when users can't reg. anymore</i>
					<input type="datetime-local" class="w3-input" name="reg_end" required>
					<label>Description</label>
					<textarea class="w3-input" name="desc" required></textarea>
				</div>
				<div class="w3-center">
					<button type="submit" name="edit_fursuit" class="w3-button w3-green w3-round">Save</button><p>
				</div>
			</form>
		</div>
	</div>
		
	<!-- CURRENT/UPCOMING EVENTS COLUMN, LOOPED -->
	<div class="w3-container">
		<h3>Current and upcoming events</h3>
		<!-- COLOUR GUIDE -->
		<div>
			To differentiate between the phase of an event, the following colour codes are being used:
			<div class="w3-bar">
				<button class="w3-button w3-round-large w3-light-green w3-hover-light-green">Upcoming</button>
				<button class="w3-button w3-round-large w3-yellow w3-hover-yellow">Pre-reg</button>
				<button class="w3-button w3-round-large w3-orange w3-hover-orange">Reg</button>
				<button class="w3-button w3-round-large w3-red w3-hover-red">Reg closed</button>
			</div>
		</div>
		<br>
		<div class="w3-row">
			<?php if(count($cEvents) > 0): ?>
				<?php foreach($cEvents as $event): ?>
					<?php
						if(new DateTime($event->reg_end)<=new DateTime()){
							$color='w3-red';
						}
						elseif(new DateTime($event->reg_start)<=new DateTime()){
							$color='w3-orange';
						}
						elseif($event->pre_reg_start!=0 &&new DateTime($event->pre_reg_start)<=new DateTime()){
							$color='w3-yellow';
						}
						else{
							$color='w3-light-green';
						}
					?>
					<!-- On the list -->
					<div class="card w3-center <?php echo $color; ?>" onclick="editEvent('<?php echo $event->id; ?>')">
						<p><?php echo $event->name.' ('.$event->type.')'; ?></p>
						<p><?php echo $event_model->convert($event->event_start, false).' - '.$event_model->convert($event->event_end, false); ?></p>
					</div>
					<!-- Pop-up modal editor -->
					<div id="event<?php echo $event->id; ?>" class="w3-modal">
						<div class="w3-modal-content w3-card-4 w3-round-large" style="max-width: 580px;">
							<header class="w3-container <?php echo $color; ?> w3-center roundHeaderTop"> 
								<span onclick="document.getElementById('event<?php echo $event->id; ?>').style.display='none'" 
								class="w3-button w3-display-topright roundXTop">&times;</span>
								<h2><?php echo $event->name; ?></h2>
							</header>
							<form action="<?php echo URL; ?>admin/update/2/<?php echo $event->id ?>" method="post">
								<div class="w3-container w3-cell">
									<label>Event type</label><br/>
									<input class="w3-radio" type="radio" name="type" value="meet" id="meet" <?php if($event->type=="meet"){echo "checked";} ?> required>
									<label>Meet</label>
									<input class="w3-radio" type="radio" name="type" value="con" id="con" <?php if($event->type=="con"){echo "checked";} ?>>
									<label>Convention</label><p>
									<label>Name</label>
									<input type="text" class="w3-input" name="name" value="<?php echo $event->name; ?>" required>
									<label>Start</label>
									<input type="datetime-local" class="w3-input" name="start" value="<?php echo $event_model->convert($event->event_start); ?>" id="start____" onblur="checkDate('start_____')" required>
									<label>End</label>
									<input type="datetime-local" class="w3-input" name="end" value="<?php echo $event_model->convert($event->event_end); ?>" required>
									<label>Location</label>
									<input type="text" class="w3-input" name="location" value="<?php echo $event->location; ?>" required>
								</div>
								<div class="w3-container w3-cell">
									<label>Reg. start</label>
									<input type="datetime-local" class="w3-input" name="reg_start" value="<?php echo $event_model->convert($event->reg_start); ?>" required>
									<label>Pre-reg start</label> <i class="w3-opacity w3-small">(optional)</i>
									<input type="datetime-local" class="w3-input" name="pre_reg" value="<?php echo $event_model->convert($event->pre_reg_start); ?>">
									<label>Reg. end</label> <i class="w3-opacity w3-small">when users can't reg. anymore</i>
									<input type="datetime-local" class="w3-input" name="reg_end" value="<?php echo $event_model->convert($event->reg_end); ?>" required>
									<label>Description</label>
									<textarea class="w3-input" name="desc" required><?php echo $event->description; ?></textarea>
								</div>
								<div class="w3-center">
									<button type="submit" name="edit_fursuit" class="w3-button w3-green w3-round">Save</button><br><br>
								</div>
							</form>
						</div>
					</div>
				<?php endforeach; ?>
			<?php else: ?>
				<p>There are no past events to show.</p>
			<?php endif; ?>
		</div>
	</div>

	<!-- PAST EVENTS COLUMN, LOOPED -->
	<div class="w3-container">
		<h3>Past events</h3>
		<div class="w3-row">
			<?php if(count($pEvents) > 0): ?>
				<?php foreach($pEvents as $event): ?>
					<!-- On the list -->
					<div class="card w3-center" onclick="editEvent('<?php echo $event->id; ?>')">
						<p><?php echo $event->name.' ('.$event->type.')'; ?></p>
						<p><?php echo $event_model->convert($event->event_start, false).' - '.$event_model->convert($event->event_end, false); ?></p>
					</div>
					<!-- Pop-up modal editor -->
					<div id="event<?php echo $event->id; ?>" class="w3-modal">
						<div class="w3-modal-content w3-card-4 w3-round-large" style="max-width: 580px;">
							<header class="w3-container w3-light-gray w3-center roundHeaderTop"> 
								<span onclick="document.getElementById('event<?php echo $event->id; ?>').style.display='none'" 
								class="w3-button w3-display-topright roundXTop">&times;</span>
								<h2><?php echo $event->name; ?></h2>
							</header>
							<form action="<?php echo URL; ?>admin/update/2/<?php echo $event->id ?>" method="post">
								<div class="w3-container w3-cell">
									<label>Event type</label><br/>
									<input class="w3-radio" type="radio" name="type" value="meet" id="meet" <?php if($event->type=="meet"){echo "checked";} ?> required>
									<label>Meet</label>
									<input class="w3-radio" type="radio" name="type" value="con" id="con" <?php if($event->type=="con"){echo "checked";} ?>>
									<label>Convention</label><p>
									<label>Name</label>
									<input type="text" class="w3-input" name="name" value="<?php echo $event->name; ?>" required>
									<label>Start</label>
									<input type="datetime-local" class="w3-input" name="start" value="<?php echo $event_model->convert($event->event_start); ?>" id="start____" onblur="checkDate('start_____')" required>
									<label>End</label>
									<input type="datetime-local" class="w3-input" name="end" value="<?php echo $event_model->convert($event->event_end); ?>" required>
									<label>Location</label>
									<input type="text" class="w3-input" name="location" value="<?php echo $event->location; ?>" required>
								</div>
								<div class="w3-container w3-cell">
									<label>Reg. start</label>
									<input type="datetime-local" class="w3-input" name="reg_start" value="<?php echo $event_model->convert($event->reg_start); ?>" required>
									<label>Pre-reg start</label> <i class="w3-opacity w3-small">(optional)</i>
									<input type="datetime-local" class="w3-input" name="pre_reg" value="<?php echo $event_model->convert($event->pre_reg_start); ?>">
									<label>Reg. end</label> <i class="w3-opacity w3-small">when users can't reg. anymore</i>
									<input type="datetime-local" class="w3-input" name="reg_end" value="<?php echo $event_model->convert($event->reg_end); ?>" required>
									<label>Description</label>
									<textarea class="w3-input" name="desc" required><?php echo $event->description; ?></textarea>
								</div>
								<div class="w3-center">
									<button type="submit" name="edit_fursuit" class="w3-button w3-green w3-round">Save</button><br><br>
								</div>
							</form>
						</div>
					</div>
				<?php endforeach; ?>
			<?php else: ?>
				<p>There are no past events to show.</p>
			<?php endif; ?>
		</div>
	</div>
</div>

<script>
function side_open() {
	document.getElementById("accSidebar").style.display="block";
}

function side_close() {
	document.getElementById("accSidebar").style.display="none";
}
function editEvent(id){
	document.getElementById('event'.concat(id)).style.display='block';
}
function checkDate(id){
	val=document.getElementById(id).value;
	console.log(val);
}
function onLoad(){
	document.getElementById("event").classList.add("w3-orange");
	document.getElementById("event").classList.remove("w3-sand");
}
onLoad();
</script>