<div class="w3-sidebar w3-bar-block w3-collapse w3-card w3-animate-left" style="width:200px" id="sidebar">
  <button class="w3-bar-item w3-button w3-large w3-hide-large" onclick="$('#sidebar').hide()"><?php echo L::admin_sidebar_close;?> &times;</button>
  <a href="<?php echo URL; ?>admin/event" class="w3-bar-item w3-button" id="event"><i class="far fa-arrow-square-left"></i> <?php echo L::admin_overview_back;?></a>
  <button class="w3-bar-item w3-button tablink bg-warning" onclick="openTab(event, 'Edit')"><?php echo L::admin_overview_edit;?></button>
  <button class="w3-bar-item w3-button tablink" onclick="openTab(event, 'Attendees')"><?php echo L::admin_overview_attendees_h;?> (<?php echo count($attendees);?>)</button>
  <button class="w3-bar-item w3-button tablink" onclick="openTab(event, 'Fursuits')"><?php echo L::admin_overview_fursuiters_h;?> (<?php echo count($fursuits);?>)</button>
  <button class="w3-bar-item w3-button tablink" onclick="openTab(event, 'Payments')"><?php echo L::admin_overview_payments_h;?></button>
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
        $sum4=0; //num of people with rooms
        $sum5=0; //num of fursuiters
        $sum6=0; //num of artists
        $sum7=0; //sum of si
        $sum8=0; //sum of en
        $sum9=0; //sum of confirmed
        $sum10=0; //sum of received payments
      ?>
      <p><?php echo L::admin_overview_attendees_info;?></p>
      <form action="<?php echo URL; ?>admin/event?id=<?php echo $event->id; ?>" method="post">
        <div class="table-responsive">
          <table class="table table-striped table-hover thead-light">
            <tr>
              <th><?php echo L::admin_overview_attendees_account;?></th>
              <th><?php echo L::admin_overview_attendees_created;?></th>
              <th><?php echo L::admin_overview_attendees_type;?></th>
              <th><?php echo L::admin_overview_attendees_payments;?></th>
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
                <td>
                    <a href="<?php echo URL.'admin/users?id='.$attendee->accID; ?>"><?php echo $attendee->username; $sum1++; ?></a>
                    <?php echo ($attendee->waiver == 1) ? '<i class="fas fa-check" title="Permanent waiver"></i>':'<i class="fas fa-times" title="Must sign a waiver"></i>'; ?>
                </td>
                <td><?php echo $event_model->convertViewable($attendee->created, 2);?></td>
                <td class="text-center"><?php
                  if($attendee->ticket=='regular'&&$event->regular_price==0){
                    echo L::admin_form_tickets_free;
                  }
                  elseif($attendee->ticket=='regular'){
                    echo L::admin_form_tickets_regular.' ('.$event->regular_price.'€)';
                    $sum2+=$event->regular_price;
                    $toBePaid=$event->regular_price;
                  }
                  elseif($attendee->ticket=='sponsor'){
                    echo L::admin_form_tickets_sponsor.' ('.$event->sponsor_price.'€)';
                    $sum2+=$event->sponsor_price;
                    $toBePaid=$event->sponsor_price;
                  }
                  else{
                    echo L::admin_form_tickets_super.' ('.$event->super_price.'€)';
                    $sum2+=$event->super_price;
                    $toBePaid=$event->super_price;
                  }
                ?></td>
                <?php
                  $amount=$event_model->getSumPayments($attendee->id)->paid;
                  if($amount==null){
                    $amount=0;
                  }
                  $sum10+=$amount;
                  if($amount!=0 && $amount>=$toBePaid){
                    $colorPayment='bg-success text-white';
                  }
                  elseif($amount!=0){
                    $colorPayment='bg-warning';
                  }
                  else{
                    $colorPayment='bg-danger text-white';
                  }
                ?>
                <td class='<?php echo $colorPayment; ?> text-center'>
                  <?php echo $amount.'€'; ?>
                </td>
                <td class="text-center"><?php
                  if($attendee->type==null){
                    echo '<i class="far fa-times"></i>';
                  }
                  else{
                    echo $attendee->type.' ('.$attendee->price.'€) ';
                    $sum3+=$attendee->price;
                    $sum4++;
                    echo ($attendee->room_confirmed==1)?'<i class="fas fa-check" title="'.L::admin_overview_attendees_roomGet.'"></i>':'<i class="fas fa-times" title="'.L::admin_overview_attendees_roomNotGet.'"></i>';
                  }
                ?></td>
                <td class="text-center"><?php
                if($attendee->fursuiter==1){
                  echo '<i class="fas fa-paw"></i> ';
                  $sum5++;
                }
                if($attendee->artist==1){
                  echo '<i class="fas fa-paint-brush"></i>';
                  $sum6++;
                }
                if($attendee->fursuiter==0&&$attendee->artist==0){
                  echo '<i class="far fa-times"></i>';
                }
                ?></td>
                <td><?php
                  echo '<img src="'.URL.'public/img/'.$attendee->language.'.jpg" width="32" class="rounded-circle">';
                  if($attendee->language=='si'){
                    $sum7++;
                  }
                  else{
                    $sum8++;
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
                    <input class="custom-control-input" id="confirmed<?php echo $attendee->id; ?>" type="checkbox" name="<?php echo $attendee->id; ?>" value="true" <?php if($attendee->confirmed==1){echo 'checked'; $sum9++;} ?>>
                    <label for="confirmed<?php echo $attendee->id; ?>" class="custom-control-label"></label>
                  </div>
                    <p>(#<?php echo $attendee->id; ?>)</p>
                </td>
                <td class="text-center">
                  <button type="submit" name="edit_reg" value="<?php echo $attendee->id; ?>" class="btn btn-primary mb-1" disabled><?php echo L::admin_overview_attendees_edit; ?></button><br>
                  <button type="button" value="<?php echo $attendee->id; ?>" class="btn btn-success mb-1" data-toggle="modal" data-target="#paymentModal" onClick="payment(<?php echo $attendee->id.', \''.$attendee->username.'\''; ?>)"><?php echo L::admin_overview_attendees_payment; ?></button><br>
                  <button type="button" class="btn btn-outline-danger" id="del<?php echo $attendee->id; ?>" onclick="delData('<?php echo $attendee->id; ?>')"><?php echo L::admin_overview_attendees_remove; ?></button>
            			<button type="submit" name="delete_reg" value="<?php echo $attendee->id; ?>" id="delconf<?php echo $attendee->id; ?>" class="btn btn-danger" style="display: none;"><?php echo L::personalInfo_delete2;?></button>
                </td>
              </tr>
            <?php endforeach; ?>
            <tr class="table-success">
              <td><i class="fas fa-users"></i> <?php echo $sum1;?></td>
              <td></td>
              <td><i class="far fa-sigma"></i> <?php echo $sum2;?>€</td>
              <td><i class="far fa-sigma"></i> <?php echo $sum10.'€'; ?></td>
              <td><i class="far fa-sigma"></i> <?php echo $sum3;?>€ (<i class="fas fa-users"></i> <?php echo $sum4;?>)</td>
              <td><?php echo "$sum5 / $sum6";?></td>
              <td></i> <?php echo "SI: $sum7 / EN: $sum8";?></td>
              <td></td>
              <td></td>
              <td><i class="far fa-sigma"></i> <?php echo $sum9;?></td>
              <td></td>
            </tr>
          </table><br>
        </div>
        <?php if($account->status>=ADMIN): ?>
        <div class="text-center">
          <button type="submit" class="btn btn-success" name="confirm_attendees"><?php echo L::admin_overview_attendees_confirm;?></button><br><br>
          <button type="submit" class="btn btn-primary" name="export_confirmed"><?php echo L::admin_overview_attendees_exportC;?></button>
          <button type="submit" class="btn btn-primary" name="export_invoices"><?php echo L::admin_overview_attendees_exportInv;?></button>
          <button type="submit" class="btn btn-primary" name="export_drustvo"><?php echo L::admin_overview_attendees_exportDrustvo;?></button>
        </div>
        <?php endif;?>
      </form>
    <?php else: ?>
      <p><?php echo L::admin_overview_attendees_none;?></p>
    <?php endif; ?>
    <!-- Payment modal -->
    <div class="modal fade" id="paymentModal">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" id="payment-title"></h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <form action="<?php echo URL; ?>admin/event?id=<?php echo $event->id; ?>" method="post">
            <div class="modal-body">
              <label for="amount"><?php echo L::admin_overview_attendees_modal_amount; ?></label>
      				<input class="form-control" type="number" name="amount" min="1" placeholder="<?php echo L::admin_overview_attendees_modal_amountP; ?>" required>
              <input type="hidden" name="reg_id" id="reg_id">
            </div>
            <div class="modal-footer">
              <button type="submit" name="pay_reg" class="btn btn-success"><?php echo L::admin_overview_attendees_modal_btn; ?></button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div id="Fursuits" class="container-fluid tab" style="display:none">
    <h3><?php echo L::admin_overview_fursuiters_h;?></h3>
    <div class="container-fluid row">
      <?php foreach($fursuits as $fursuit): ?>
        <div class="card card-custom fursuit card-round mr-3 bg-light" >
					<img src="<?php echo URL.'public/fursuits/'.$fursuit->img; ?>.jpg" class="roundImg">
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
    <div class="table-responsive">
      <form action="<?php echo URL; ?>admin/event?id=<?php echo $event->id; ?>" method="post">
        <table class="table table-striped table-hover thead-light">
          <tr>
            <th><?php echo L::admin_overview_attendees_account;?></th>
            <th><?php echo L::admin_overview_payments_amount;?></th>
            <th><?php echo L::admin_overview_payments_type;?></th>
            <th><?php echo L::admin_overview_payments_verified;?></th>
            <th><?php echo L::admin_overview_payments_time;?></th>
            <th></th>
          </tr>
          <?php foreach($payments as $payment): ?>
            <tr <?php if($payment->verified==0){ echo 'class="table-warning"'; } ?>>
              <td><?php echo $payment->username; ?></td>
              <td><?php echo $payment->amount; ?></td>
              <td>
                <?php
                  if($payment->session!=null){
                    echo 'Stripe ('.$payment->session.')';
                  }
                  else{
                    echo L::admin_overview_payments_manual.' ('.$event_model->getUsername($payment->manual)->username.')';
                  }
                ?>
              </td>
              <td><?php echo ($payment->verified==1)?'<i class="far fa-check"></i>':'<i class="far fa-times"></i>'; ?></td>
              <td><?php echo $event_model->convertViewable($payment->paytime, 2); ?></td>
              <td class="text-center">
                <?php if($payment->verified==1): ?>
                  <button type="submit" name="unverify_payment" value="<?php echo $payment->id; ?>" class="btn btn-warning mb-1"><?php echo L::admin_overview_payments_unverify;?></button>
                <?php else: ?>
                  <button type="submit" name="verify_payment" value="<?php echo $payment->id; ?>" class="btn btn-primary mb-1"><?php echo L::admin_overview_payments_verify;?></button>
                <?php endif; ?>
                <br><button type="button" class="btn btn-outline-danger" id="del<?php echo $payment->id; ?>" onclick="delData('<?php echo $payment->id; ?>')"><?php echo L::admin_overview_attendees_remove; ?></button>
                <button type="submit" name="delete_payment" value="<?php echo $payment->id; ?>" id="delconf<?php echo $payment->id; ?>" class="btn btn-danger" style="display: none;"><?php echo L::personalInfo_delete2;?></button>
              </td>
            </tr>
        <?php endforeach; ?>
        </table>
      </form>
    </div>
  </div>

</div>

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
function payment(id, name){
  $("#reg_id").val(id);
  $("#payment-title").text("<?php echo L::admin_overview_attendees_modal_h; ?> "+name)
}
</script>
