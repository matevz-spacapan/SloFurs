<?php if(count($attendees)>0): ?>
<div class="tab-pane container-fluid fade mt-3" id="Stats">
  <div class="text-center p-4">
    <h4><b><?php echo L::register_form_stats_country;?></b></h4>
  </div>
  <div id="chartCountry" style="height: 300px;"></div>
  <div class="text-center p-4">
    <h4><b><?php echo L::register_form_stats_ticket;?></b></h4>
  </div>
  <div id="chartTicket"></div>
  <div class="text-center p-4">
    <h4><b><?php echo L::register_form_stats_accomodation;?></b></h4>
  </div>
  <div id="chartRooms" style="width: 100%; height: 300px;"></div>
  <div class="text-center p-4">
    <h4><b><?php echo L::register_form_stats_gender;?></b></h4>
  </div>
  <div id="chartGender" style="width: 100%; height: 300px;"></div>
</div>

<link rel="stylesheet" type="text/css" href="<?php echo URL; ?>public/c3.min.css">
<script src="<?php echo URL; ?>public/c3.min.js"></script>
<script src="<?php echo URL; ?>public/d3.min.js"></script>
<script>
$(document).ready(function (){
  var chart = c3.generate({
    bindto: '#chartCountry',
  data: {
    columns: [
      <?php
  		$countries=$reg_model->getCountries($evt_id);
      $i=-1;
      $counter=0;
  		foreach ($countries as $country){
        $i+=1;
        if($i<7){
          echo '["'.$country->country.'", '.$country->counter.'],';
        }
        else{
          $counter+=$country->counter;
        }
  		}
      if($counter>0){
        echo '["Other",'.$counter.'],';
      }
    	?>
    ],
    type : 'pie'
  },
  color: {
    pattern: ['#3498db','#2c3e50','#8e44ad','#27ae60','#f1c40f','#d35400','#e74c3c']
  }
  });
});
$(document).ready(function (){
  var chart = c3.generate({
    bindto: '#chartGender',
  data: {
    columns: [
      <?php
  		$genders=$reg_model->getGenders($evt_id);
  		foreach ($genders as $gender){
  			if($gender->gender=='silent'){
          echo '["Do not wish to answer", '.$gender->counter.'],';
  			}
  			else{
          echo '["'.ucfirst($gender->gender).'", '.$gender->counter.'],';
  			}
  		}
    	?>
    ],
    type : 'pie'
  },
  color: {
    pattern: ['#3498db','#2c3e50','#8e44ad']
  }
  });
});
$(document).ready(function (){
  var chart = c3.generate({
    bindto: '#chartRooms',
  data: {
    columns: [
      <?php
  		$rooms=$reg_model->getRooms($evt_id);
  		foreach ($rooms as $room){
        echo '["'.ucfirst($room->type).'", '.$room->counter.'],';
  		}
  		$room=$reg_model->getNoRoom($evt_id);
  		if($room->counter!=0){
        echo '["No accomodation", '.$room->counter.'],';
  		}
    	?>
    ],
    type : 'pie'
  },
  color: {
    pattern: ['#3498db','#2c3e50','#8e44ad','#27ae60','#f1c40f','#d35400','#e74c3c']
  }
  });
});
$(document).ready(function (){
  var chart = c3.generate({
    bindto: '#chartTicket',
  data: {
    columns: [
      <?php
  		$tickets=$reg_model->getTickets($evt_id);
  		if($event->regular_price==0){
  			foreach ($tickets as $ticket){
            echo '["Free", '.$ticket->counter.'],';
  			}
  		}
  		else{
  			foreach ($tickets as $ticket){
          echo '["'.ucfirst($ticket->ticket).'", '.$ticket->counter.'],';
  			}
  			$text=substr($text, 0, -1);
  		}
    	?>
    ],
    type : 'pie'
  },
  color: {
    pattern: ['#3498db','#2c3e50','#8e44ad']
  }
  });
});
</script>
<?php endif; ?>
