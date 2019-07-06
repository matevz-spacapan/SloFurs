<div class="card w3-center <?php echo $color; ?>" <?php if($color=='w3-light-gray'){ echo 'style="cursor: default;"'; } ?>>
	<h4 style="text-transform: uppercase;"><?php echo $event->name; ?></h4>
	<?php
		if($reg_model->convertViewable($event->event_start, true)==$reg_model->convertViewable($event->event_end, true)){
			echo "<p>".$reg_model->convertViewable($event->event_start, true)."<br>".$reg_model->convertViewable($event->event_start, false)." - ".$reg_model->convertViewable($event->event_end, false)."</p>";
		}
		else{
			echo "<p>".substr($reg_model->convertViewable($event->event_start, true),0, 6).' - '.$reg_model->convertViewable($event->event_end, true)."<br>".$reg_model->convertViewable($event->event_start, false)." - ".$reg_model->convertViewable($event->event_end, false)."</p>";
		}
	?>
	<p><b><?php echo $text; ?></b></p>
</div>