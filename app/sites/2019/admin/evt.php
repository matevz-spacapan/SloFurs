<div class="card mr-5 my-2 <?php echo $color; ?>">
	<div class=" bg-light">
		<div class="img-container">
			<?php
			echo '<a href="'.URL.'admin/event?id='.$event->id.'" class="text-body">';
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
				if($event_model->convertViewable($event->event_start, true)==$event_model->convertViewable($event->event_end, true)){
					echo '<p class="text-body">'.$event_model->convertViewable($event->event_start, true)."<br>".$event_model->convertViewable($event->event_start, false)." - ".$event_model->convertViewable($event->event_end, false)."</p>";
				}
				//multiple day event
				else{
					echo '<p class="text-body">'.substr($event_model->convertViewable($event->event_start, true),0, 6).' - '.$event_model->convertViewable($event->event_end, true)."<br>".$event_model->convertViewable($event->event_start, false)."&nbsp;&nbsp;- ".$event_model->convertViewable($event->event_end, false)."</p>";
				}
			?>
			<?php
			echo '<a href="'.URL.'admin/event?id='.$event->id.'" class="text-body">';
			?>
	    	<h5 class="card-title"><?php echo $event->name; ?></h5>
			</a>
	    <p class="card-text text-secondary"><?php echo $event->location;?></p>
	    <p class="card-text text-dark">
				<?php
					if($event->regular_price==0){
						echo L::register_view_free;
					}
					elseif($event->sponsor_price==-1){
						echo $event->regular_price.'€';
					}
					elseif($event->super_price==-1){
						echo $event->regular_price.'€ - '.$event->sponsor_price.'€';
					}
					else{
						echo $event->regular_price.'€ - '.$event->super_price.'€';
					}
				?>
			</p>
	  </div>
	</div>
	<b class="px-2 py-3"><?php echo $text; ?></b>
</div>
