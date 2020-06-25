<div class="card mr-5 my-2 <?php echo $color; ?>">
	<div class=" bg-light">
		<div class="img-container">
			<?php
			if(isset($edit)&&$edit){
				echo '<a href="'.URL.'register/edit?id='.$id.'" class="text-body">';
			}
			else{
				echo '<a href="'.URL.'register/new?id='.$event->id.'" class="text-body">';
			}
			if(isset($event->img)){
				echo '<img src="'.URL.'public/events/'.$event->img.'.jpg" class="card-img-top">';
			}
			else {
				echo '<img src="'.URL.'public/events/head.jpg" class="card-img-top">';
			}
			?>
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
				echo '<a href="'.URL.'register/edit?id='.$id.'" class="text-body">';
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
	<b class="px-2 py-3"><?php echo $text; ?></b>
</div>
