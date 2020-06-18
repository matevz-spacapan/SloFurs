<div class="w3-sidebar w3-bar-block w3-collapse w3-card w3-animate-left" style="width:200px" id="sidebar">
  <button class="w3-bar-item w3-button w3-large w3-hide-large" onclick="$('#sidebar').hide()"><?php echo L::admin_sidebar_close;?> &times;</button>
  <a href="<?php echo URL; ?>admin/event" class="w3-bar-item w3-button" id="event"><i class="far fa-arrow-square-left"></i> <?php echo L::admin_overview_back;?></a>
  <button class="w3-bar-item w3-button tablink bg-warning" onclick="openTab(event, 'Edit')"><?php echo L::admin_overview_edit;?></button>
  <button class="w3-bar-item w3-button tablink" onclick="openTab(event, 'Attendees')"><?php echo L::admin_overview_attendees_h;?> (<?php echo count($attendees);?>)</button>
  <button class="w3-bar-item w3-button tablink" onclick="openTab(event, 'Fursuits')"><?php echo L::admin_overview_fursuiters_h;?> (<?php echo count($fursuits);?>)</button>
  <button class="w3-bar-item w3-button tablink w3-hide" onclick="openTab(event, 'Payments')"><?php echo L::admin_overview_payments_h;?></button>
</div>

<div class="w3-main" style="margin-left:200px">
  <div class="container-fluid bg-warning">
  	<h1 class="py-2"><?php echo $event->name; ?></h1>
  </div>
  <div id="Edit" class="container-fluid tab">
    <?php $editEvent=true; require 'app/sites/'.THEME.'/admin/form.php'; ?>
  </div>

  <div id="Attendees" class="container-fluid tab" style="display:none;">
    <h3><?php echo L::admin_overview_attendees_h;?></h3>
    <?php if(count($attendees)>0): ?>
      <?php
        $sum1=0; //num of attendees
        $sum2=0; //sum of tickets
        $sum3=0; //sum of rooms
        $sum9=0; //num of people with rooms
        $sum4=0; //num of fursuiters
        $sum5=0; //num of artists
        $sum6=0; //sum of si
        $sum7=0; //sum of en
        $sum8=0; //sum of confirmed
      ?>
      <p><?php echo L::admin_overview_attendees_info;?></p>
      <form action="<?php echo URL; ?>admin/event?id=<?php echo $event->id; ?>" method="post" class="allforms">
        <div class="table-responsive">
          <table class="table table-striped table-hover thead-light">
            <tr>
              <th><?php echo L::admin_overview_attendees_account;?></th>
              <th><?php echo L::admin_overview_attendees_created;?></th>
              <th><?php echo L::admin_overview_attendees_type;?></th>
              <th><?php echo L::admin_overview_attendees_room;?></th>
              <th><?php echo L::admin_overview_attendees_fursuiterArtist;?></th>
              <th><?php echo L::admin_overview_attendees_language;?></th>
              <th width="170"><?php echo L::admin_overview_attendees_age;?></th>
              <th><?php echo L::admin_overview_attendees_notes;?></th>
              <th><?php echo L::admin_overview_attendees_confirmed;?></th>
              <th></th>
            </tr>
            <?php foreach($attendees as $attendee): ?>
              <tr>
                <td><?php echo $attendee->username; $sum1++; ?></td>
                <td><?php echo $event_model->convertViewable($attendee->created, 2);?></td>
                <td><?php
                  if($attendee->ticket=='regular'&&$event->regular_price==0){
                    echo L::admin_form_tickets_free;
                  }
                  elseif($attendee->ticket=='regular'){
                    echo L::admin_form_tickets_regular.' ('.$event->regular_price.'€)';
                    $sum2+=$event->regular_price;
                  }
                  elseif($attendee->ticket=='sponsor'){
                    echo L::admin_form_tickets_sponsor.' ('.$event->sponsor_price.'€)';
                    $sum2+=$event->sponsor_price;
                  }
                  else{
                    echo L::admin_form_tickets_super.' ('.$event->super_price.'€)';
                    $sum2+=$event->super_price;
                  }
                ?></td>
                <td><?php
                  if($attendee->type==null){
                    echo '<i class="far fa-times"></i>';
                  }
                  else{
                    echo $attendee->type.' ('.$attendee->price.'€) ';
                    $sum3+=$attendee->price;
                    $sum9++;
                    echo ($attendee->room_confirmed==1)?'<i class="fas fa-check-circle" title="'.L::admin_overview_attendees_roomGet.'"></i>':'<i class="fas fa-times-circle" title="'.L::admin_overview_attendees_roomNotGet.'"></i>';
                  }
                ?></td>
                <td><?php
                if($attendee->fursuiter==1){
                  echo '<i class="fas fa-paw"></i> ';
                  $sum4++;
                }
                if($attendee->artist==1){
                  echo '<i class="fas fa-paint-brush"></i>';
                  $sum5++;
                }
                if($attendee->fursuiter==0&&$attendee->artist==0){
                  echo '<i class="far fa-times"></i>';
                }
                ?></td>
                <td><?php
                  echo '<img src="'.URL.'public/img/'.$attendee->language.'.png" width="32" class="rounded-circle">';
                  if($attendee->language=='si'){
                    $sum6++;
                  }
                  else{
                    $sum7++;
                  }
                ?></td>
                <td>
                  <?php
                    $age=(int)date_diff(date_create($event->event_start), date_create($attendee->dob), true)->format('%y');
                    echo "{$event_model->convertViewable($attendee->dob, 1)} ($age ".L::admin_overview_attendees_years.")";
                  ?>
                </td>
                <td>
                  <?php echo $attendee->notes;?>
                </td>
                <td class="text-center">
                  <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" id="confirmed" type="checkbox" name="<?php echo $attendee->id; ?>" value="true" <?php if($attendee->confirmed==1){echo 'checked'; $sum8++;} ?>>
                    <label for="confirmed" class="custom-control-label"></label>
                  </div>
                </td>
                <td class="text-center">
                  <button type="submit" name="edit_reg" value="<?php echo $attendee->id; ?>" class="btn btn-primary mb-1" disabled>TRANSLATE Uredi</button><br>
                  <button type="button" class="btn btn-outline-danger" id="del<?php echo $attendee->id; ?>" onclick="delData('<?php echo $attendee->id; ?>')"><?php echo L::admin_overview_attendees_remove; ?></button>
            			<button type="submit" name="delete_reg" value="<?php echo $attendee->id; ?>" id="delconf<?php echo $attendee->id; ?>" class="btn btn-danger" style="display: none;"><?php echo L::personalInfo_delete2;?></button>
                  <script>
                    function delData(id){
                      $("#del"+id).addClass("scale-out-center");
                      setTimeout(function(){
                        contDel(id);
                      }, 500);
                    }
                    function contDel(id){
                      $("#del"+id).hide();
                      $("#delconf"+id).css("display", "inline-block");
                      $("#delconf"+id).addClass("scale-in-center");
                      setTimeout(function(){
                        $("#delconf"+id).removeClass("scale-in-center");
                      }, 500);
                    }
                  </script>
                </td>
              </tr>
            <?php endforeach; ?>
            <tr class="table-success">
              <td><i class="fas fa-users"></i> <?php echo $sum1;?></td>
              <td></td>
              <td><i class="far fa-sigma"></i> <?php echo $sum2;?>€</td>
              <td><i class="far fa-sigma"></i> <?php echo $sum3;?>€ (<i class="fas fa-users"></i> <?php echo $sum9;?>)</td>
              <td><?php echo "$sum4 / $sum5";?></td>
              <td></i> <?php echo "SI: $sum6 / EN: $sum7";?></td>
              <td></td>
              <td></td>
              <td><i class="far fa-sigma"></i> <?php echo $sum8;?></td>
              <td></td>
            </tr>
          </table><br>
        </div>
        <?php if($account->status>=ADMIN): ?>
        <div class="text-center">
          <button type="submit" class="btn btn-success" name="confirm_attendees"><?php echo L::admin_overview_attendees_confirm;?></button><br><br>
          <button type="submit" class="btn btn-secondary" name="export_confirmed"><?php echo L::admin_overview_attendees_exportC;?></button>
          <button type="submit" class="btn btn-secondary" name="export_all"><?php echo L::admin_overview_attendees_exportA;?></button><br><br>
          <button type="submit" class="btn btn-primary" name="export_invoices">Izvozi račune</button>
        </div>
        <?php endif;?>
      </form>
    <?php else: ?>
      <p><?php echo L::admin_overview_attendees_none;?></p>
    <?php endif; ?>
  </div>

  <div id="Fursuits" class="container-fluid tab" style="display:none">
    <h3><?php echo L::admin_overview_fursuiters_h;?></h3>
    <div class="container-fluid row">
      <?php foreach($fursuits as $fursuit): ?>
        <div class="card fursuit card-round mr-3 bg-light" >
					<img src="<?php echo URL.'public/fursuits/'.$fursuit->img; ?>.png" class="roundImg">
					<p class="text-center pt-2"><b><?php echo $fursuit->name; ?></b></p>
				</div>
      <?php endforeach; ?>
    </div>
    <?php if(count($fursuits)==0): ?>
      <p><?php echo L::admin_overview_fursuiters_none;?></p>
    <?php endif; ?>
  </div>

  <div id="Payments" class="container-fluid tab" style="display:none">
    <h3><?php echo L::admin_overview_payments_h;?></h3>
  </div>

</div>

<script>
function openTab(evt, tabName){
  var i, x, tablinks;
  x=document.getElementsByClassName("tab");
  for(i=0;i<x.length;i++){
    x[i].style.display="none";
  }
  tablinks=document.getElementsByClassName("tablink");
  for(i=0;i<x.length;i++){
    tablinks[i].className=tablinks[i].className.replace(" bg-warning", "");
  }
  document.getElementById(tabName).style.display="block";
  evt.currentTarget.className+=" bg-warning";
}
</script>
