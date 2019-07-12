<div class="w3-sidebar w3-bar-block w3-light-grey w3-card" style="width:200px">
  <a href="<?php echo URL; ?>admin/event" class="w3-bar-item w3-button" id="event"><i class="far fa-arrow-square-left"></i> Back to events</a>
  <h4 class="w3-bar-item"><b><?php echo $event->name; ?></b></h4>
  <button class="w3-bar-item w3-button tablink w3-orange" onclick="openTab(event, 'Edit')">Edit event details</button>
  <button class="w3-bar-item w3-button tablink" onclick="openTab(event, 'Attendees')">Attendees (<?php echo count($attendees);?>)</button>
  <button class="w3-bar-item w3-button tablink" onclick="openTab(event, 'Fursuits')">Fursuiters (<?php echo count($fursuits);?>)</button>
  <button class="w3-bar-item w3-button tablink w3-hide" onclick="openTab(event, 'Payments')">Payments</button>
</div>

<div style="margin-left:200px">
  <div id="Edit" class="w3-container tab">
    <?php $editEvent=true; require 'app/sites/'.THEME.'/admin/form.php'; ?>
  </div>

  <div id="Attendees" class="w3-container tab" style="display:none; width:50%;">
    <h3>Attendees</h3>
    <?php if(count($attendees)>0): ?>
      <p>You can view all registered attendees on the list below. To confirm changes in the Confirmed column, click Confirm attendees. To export release forms, click the appropriate button.</p>
      <form action="<?php echo URL; ?>admin/event?id=<?php echo $event->id; ?>" method="post">
        <table class="w3-table w3-striped w3-centered">
          <tr>
            <th>Account</th>
            <th>Ticket type</th>
            <th>Requested room</th>
            <th>Fursuiter/Artist</th>
            <th>Confirmed</th>
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
          <button type="submit" class="w3-button w3-green w3-round" name="confirm_attendees">Confirm attendees</button><br><br>
          <button type="submit" class="w3-button w3-blue w3-round" name="export_confirmed" disabled>Export confirmed</button>
          <button type="submit" class="w3-button w3-blue w3-round" name="export_all" disabled>Export all</button>
        </div>
      </form>
    <?php else: ?>
      <p>There's nothing to show here yet as there are no registered users for this event.</p>
    <?php endif; ?>
  </div>

  <div id="Fursuits" class="w3-container tab" style="display:none">
    <h2>Fursuits</h2>
    <div class="w3-row">
      <?php foreach($fursuits as $fursuit): ?>
        <div class="card">
          <?php if(file_exists('public/fursuits/'.$fursuit->img.'.png')): ?>
            <img src="<?php echo URL.'public/fursuits/'.$fursuit->img; ?>.png" class="roundImg">
          <?php else: ?>
            <img src="<?php echo URL.'public/img/account.png' ?>" class="roundImg">
          <?php endif; ?>
          <div class="w3-center"><b><?php echo $fursuit->name;?></b><br>
          (owned by <?php echo $fursuit->username;?>)<br>
          <?php echo $fursuit->animal;?></div>
        </div>
      <?php endforeach; ?>
    </div>
    <?php if(count($fursuits)==0): ?>
      <p>There are no fursuits to show yet.</p>
    <?php endif; ?>
  </div>

  <div id="Payments" class="w3-container tab" style="display:none">
    <h2>Payments</h2>
    <p>When and if the event is collecting online payments, they will be shown here.</p>
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
