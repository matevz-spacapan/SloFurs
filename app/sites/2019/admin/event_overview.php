<div class="w3-sidebar w3-bar-block w3-collapse w3-card w3-animate-left" style="width:200px" id="accSidebar">
  <button class="w3-bar-item w3-button w3-large w3-hide-large" onclick="$('#accSidebar').hide()"><?php echo L::admin_close;?> &times;</button>
  <a href="<?php echo URL; ?>admin/event" class="w3-bar-item w3-button" id="event"><i class="far fa-arrow-square-left"></i> <?php echo L::admin_overview_back;?></a>
  <button class="w3-bar-item w3-button tablink w3-orange" onclick="openTab(event, 'Edit')"><?php echo L::admin_overview_edit;?></button>
  <button class="w3-bar-item w3-button tablink" onclick="openTab(event, 'Attendees')"><?php echo L::admin_overview_attendees_h;?> (<?php echo count($attendees);?>)</button>
  <button class="w3-bar-item w3-button tablink" onclick="openTab(event, 'Fursuits')"><?php echo L::admin_overview_fursuiters_h;?> (<?php echo count($fursuits);?>)</button>
  <button class="w3-bar-item w3-button tablink w3-hide" onclick="openTab(event, 'Payments')"><?php echo L::admin_overview_payments_h;?></button>
</div>

<div class="w3-main" style="margin-left:200px">
  <div class="w3-orange">
  	<button class="w3-button w3-orange w3-xlarge w3-hide-large" onclick="$('#accSidebar').show()">&#9776;</button>
  	<div class="w3-container">
  		<h1><?php echo $event->name; ?></h1>
  	</div>
  </div>
  <div id="Edit" class="w3-container tab">
    <?php $editEvent=true; require 'app/sites/'.THEME.'/admin/form.php'; ?>
  </div>

  <div id="Attendees" class="w3-container tab" style="display:none; width:50%;">
    <h3><?php echo L::admin_overview_attendees_h;?></h3>
    <?php if(count($attendees)>0): ?>
      <p><?php echo L::admin_overview_attendees_info;?></p>
      <form action="<?php echo URL; ?>admin/event?id=<?php echo $event->id; ?>" method="post">
        <table class="w3-table w3-striped w3-centered">
          <tr>
            <th><?php echo L::admin_overview_attendees_account;?></th>
            <th><?php echo L::admin_overview_attendees_type;?></th>
            <th><?php echo L::admin_overview_attendees_room;?></th>
            <th><?php echo L::admin_overview_attendees_fursuiterArtist;?></th>
            <th><?php echo L::admin_overview_attendees_confirmed;?></th>
          </tr>
          <?php foreach($attendees as $attendee): ?>
            <tr>
              <td><?php echo $attendee->username; ?></td>
              <td><?php
                if($attendee->ticket=='regular'&&$event->regular_price==0){
                  echo 'Free';
                }
                elseif($attendee->ticket=='regular'){
                  echo 'Regular ('.$event->regular_price.'€)';
                }
                elseif($attendee->ticket=='sponsor'){
                  echo 'Sponsor ('.$event->sponsor_price.'€)';
                }
                else{
                  echo 'Super sponsor ('.$event->super_price.'€)';
                }
              ?></td>
              <td><?php echo ($attendee->type==null)?'<i class="far fa-times"></i>':$attendee->type; ?></td>
              <td><?php
              if($attendee->fursuiter==1){
                echo '<i class="fas fa-paw"></i> ';
              }
              if($attendee->artist==1){
                echo '<i class="fas fa-paint-brush"></i>';
              }
              if($attendee->fursuiter==0&&$attendee->artist==0){
                echo '<i class="far fa-times"></i>';
              }
              ?></td>
              <td>
                <input class="w3-check" type="checkbox" name="<?php echo $attendee->id; ?>" value="true" <?php if($attendee->confirmed==1){echo 'checked';} ?>>
              </td>
            </tr>
          <?php endforeach; ?>
        </table><br>
        <div class="w3-center">
          <button type="submit" class="w3-button w3-green w3-round" name="confirm_attendees"><?php echo L::admin_overview_attendees_confirm;?></button><br><br>
          <button type="submit" class="w3-button w3-blue w3-round" name="export_confirmed" disabled><?php echo L::admin_overview_attendees_exportC;?></button>
          <button type="submit" class="w3-button w3-blue w3-round" name="export_all" disabled><?php echo L::admin_overview_attendees_exportA;?></button>
        </div>
      </form>
    <?php else: ?>
      <p><?php echo L::admin_overview_attendees_none;?></p>
    <?php endif; ?>
  </div>

  <div id="Fursuits" class="w3-container tab" style="display:none">
    <h3><?php echo L::admin_overview_fursuiters_h;?></h3>
    <div class="w3-row">
      <?php foreach($fursuits as $fursuit): ?>
        <div class="card">
          <?php if(file_exists('public/fursuits/'.$fursuit->img.'.png')): ?>
            <img src="<?php echo URL.'public/fursuits/'.$fursuit->img; ?>.png" class="roundImg">
          <?php else: ?>
            <img src="<?php echo URL.'public/img/account.png' ?>" class="roundImg">
          <?php endif; ?>
          <div class="w3-center"><b><?php echo $fursuit->name;?></b><br>
          (<?php echo L::admin_overview_fursuiters_owned;?> <?php echo $fursuit->username;?>)<br>
          <?php echo $fursuit->animal;?></div>
        </div>
      <?php endforeach; ?>
    </div>
    <?php if(count($fursuits)==0): ?>
      <p><?php echo L::admin_overview_fursuiters_none;?></p>
    <?php endif; ?>
  </div>

  <div id="Payments" class="w3-container tab" style="display:none">
    <h3><?php echo L::admin_overview_payments_h;?></h3>
  </div>

</div>

<script>
function openTab(evt, tabName) {
  var i, x, tablinks;
  x = document.getElementsByClassName("tab");
  for (i = 0; i < x.length; i++) {
    x[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablink");
  for (i = 0; i < x.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" w3-orange", "");
  }
  document.getElementById(tabName).style.display = "block";
  evt.currentTarget.className += " w3-orange";
}
</script>
