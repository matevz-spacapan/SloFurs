<a href="<?php echo URL.'admin/event?id='.$event->id; ?>" style="text-decoration: none;">
	<div class="card w3-center <?php echo $color; ?>">
		<h4 style="text-transform: uppercase;"><?php echo $event->name; ?></h4>
		<?php
			if($event_model->convertViewable($event->event_start, true)==$event_model->convertViewable($event->event_end, true)){
				echo "<p>".$event_model->convertViewable($event->event_start, true)."<br>".$event_model->convertViewable($event->event_start, false)." - ".$event_model->convertViewable($event->event_end, false)."</p>";
			}
			else{
				echo "<p>".substr($event_model->convertViewable($event->event_start, true),0, 6).' - '.$event_model->convertViewable($event->event_end, true)."<br>".$event_model->convertViewable($event->event_start, false)." - ".$event_model->convertViewable($event->event_end, false)."</p>";
			}
		?>
		<p><b><?php echo $text; ?></b></p>
	</div>
</a>
