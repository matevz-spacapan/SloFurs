<!-- On the list -->
<div class="card w3-center <?php echo $color; ?>" onclick="editEvent('<?php echo $event->id; ?>')">
	<h4><?php echo $event->name; ?></h4>
	<?php
		if($event_model->convertViewable($event->event_start, true)==$event_model->convertViewable($event->event_end, true)){
			echo "<p>".$event_model->convertViewable($event->event_start, true)." ".$event_model->convertViewable($event->event_start, false)." - ".$event_model->convertViewable($event->event_end, false)."</p>";
		}
		else{
			echo "<p>".$event_model->convertViewable($event->event_start, true).' - '.$event_model->convertViewable($event->event_end, true)."</p>";
		}
	?>
</div>
<!-- Pop-up modal editor -->
<div id="event<?php echo $event->id; ?>" class="w3-modal">
	<div class="w3-modal-content w3-card-4 w3-round-large" style="max-width: 580px;">
		<header class="w3-container <?php echo $color; ?> w3-center roundHeaderTop"> 
			<span onclick="$('#event<?php echo $event->id; ?>').hide()" 
			class="w3-button w3-display-topright roundXTop">&times;</span>
			<h2><?php echo $event->name; ?></h2>
		</header>
		<form action="<?php echo URL; ?>admin/update/2/<?php echo $event->id ?>" method="post">
			<div class="w3-container w3-cell">
				<label>Name</label>
				<input type="text" class="w3-input" name="name" value="<?php echo $event->name; ?>" required>
				<label>Start</label>
				<input type="datetime-local" class="w3-input" name="start" value="<?php echo $event_model->convert($event->event_start); ?>" required>
				<label>End</label>
				<input type="datetime-local" class="w3-input" name="end" value="<?php echo $event_model->convert($event->event_end); ?>" required>
				<label>Location</label>
				<input type="text" class="w3-input" name="location" value="<?php echo $event->location; ?>" required>
			</div>
			<div class="w3-container w3-cell">
				<label>Reg. start</label>
				<input type="datetime-local" class="w3-input" name="reg_start" value="<?php echo $event_model->convert($event->reg_start); ?>" required>
				<label>Pre-reg start</label> <i class="w3-opacity w3-small">optional</i>
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