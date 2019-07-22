<div class="fixer">
	<?php echo '<a href="'.URL.'register/new?id='.$event->id.'" style="text-decoration: none;">';?>
	<?php if($event->img!=null): ?>
		<img src="<?php echo URL.'public/events/'.$event->img.'.png'?>" class="w3-round-xlarge eventImage">
	<?php else: ?>
		<img src="<?php echo URL.'public/events/default.png'?>" class="w3-round-xlarge eventImage">
	<?php endif;?>
	<div class="card w3-center <?php echo $color; ?>" style="margin-right: 100px; z-index:1;">
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
	<?php echo '</a>'; ?>
</div>
