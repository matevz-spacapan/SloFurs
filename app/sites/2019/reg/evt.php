<div class="card mr-5 my-2 bg-light">
	<div class="img-container">
		<?php if(isset($edit)&&$edit): ?>
			<div class="confirmation btn btn-light py-3 px-2 <?php echo $color; ?>">
				<?php echo $text; ?>
			</div>
		<?php endif;?>
		<?php
		if(isset($edit)&&$edit){
			echo '<a href="'.URL.'register/edit?id='.$event->id.'" class="text-body">';
		}
		else{
			echo '<a href="'.URL.'register/new?id='.$event->id.'" class="text-body">';
		}
		?>
  	<img src="<?php echo URL.'public/events/head.png'?>" class="card-img-top">
		</a>
	</div>
  <div class="card-body">
		<?php
			//one day event
			if($reg_model->convertViewable($event->event_start, true)==$reg_model->convertViewable($event->event_end, true)){
				echo '<p class="text-warning">'.$reg_model->convertCard($event->event_start, true).'</p>';
			}
			//multiple day event
			else{
				echo '<p class="text-warning">'.$reg_model->convertCard($event->event_start, false).'</p>';
			}
		?>
		<?php
		if(isset($edit)&&$edit){
			echo '<a href="'.URL.'register/edit?id='.$event->id.'" class="text-body">';
		}
		else{
			echo '<a href="'.URL.'register/new?id='.$event->id.'" class="text-body">';
		}
		?>
    	<h5 class="card-title"><?php echo $event->name; ?></h5>
		</a>
    <p class="card-text text-secondary"><?php echo $event->location;?></p>
    <p class="card-text text-dark">
			<?php
				if($event->regular_price==0){
					echo L::register_view_free;
				}
				else{
					echo L::register_view_from.$event->regular_price.'€';
				}
			?>
		</p>
  </div>
</div>
