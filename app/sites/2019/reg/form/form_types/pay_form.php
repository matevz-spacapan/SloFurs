<script src="https://js.stripe.com/v3/"></script>
<script>
  var stripe = Stripe('<?php echo STRIPE_PUBLIC; ?>');
  function goStripe(){
    $("#StripeStart").html('<i class="fab fa-stripe fa-3x"></i><br><i class="fas fa-spinner-third fa-spin"></i>');
    stripe.redirectToCheckout({
    sessionId: '<?php echo $session['id']; ?>'
  }).then(function(result){});
  }
</script>
<button class="btn-block btn btn-success mt-2" data-toggle="modal" data-target="#payment" id="payButton"><?php echo L::register_form_buttonPay;?></button>
<div id="payment" class="modal fade">
  <div class="modal-dialog modal-lg">
    <div class="modal-content" style="max-width:650px">
      <div class="modal-header">
        <h4 class="modal-title text-primary"><?php echo L::register_form_payment_h;?></h4>
        <button type="button" class="close" data-dismiss="modal" style="font-weight: 1;"><?php echo L::register_form_payment_later;?> &times;</button>
      </div>
      <div class="modal-body">
        <?php
          $pending_list=$reg_model->pendingPayments($id);
        ?>
        <div class="text-center">
          <h5><?php echo L::register_form_payment_total;?>: <?php echo $price;?>€</h5>
          <small class="text-muted"><i><?php echo L::register_form_payment_finePrint;?> <a href="<?php echo URL;?>rules" target="_blank"><?php echo L::register_form_payment_tos;?> <i class="far fa-external-link"></i></a>.</i></small><br><br>
          <button class="btn btn-primary" onclick="goStripe()" id="StripeStart" <?php if(count($pending_list)>0){echo 'disabled';} ?>><i class="fab fa-stripe fa-3x"></i><br><?php echo L::register_form_payment_stripeButton;?></button><br>
          <small class="text-muted"><i><?php echo L::register_form_payment_redirect;?></i></small>
        </div>
        <?php if(count($pending_list)>0): ?>
          <div><br>
            <p class="text-danger"><?php echo L::register_form_payment_pendingWarning;?></p>
            <?php foreach($pending_list as $pending_item): ?>
              <p><?php echo $pending_item->amount; ?>€ <?php echo $pending_item->start_time; ?></p>
          </div>
          <?php endforeach; ?>
        <?php endif; ?>
        <hr>
        <div><?php echo L::register_form_payment_bank;?><br>
        <?php echo L::register_form_payment_payee;?>: <strong>Društvo SloFurs</strong><br>
        IBAN: <strong>SI56 6100 0002 4500 122</strong><br>
        BIC: <strong>HDELSI22</strong><br>
        <?php echo L::register_form_payment_address;?>: <strong>Gregorčičeva ulica 33, 5000 Nova Gorica</strong><br>
        <?php echo L::register_form_payment_paymentCode;?>: <strong>GDSV</strong><br>
        <?php echo L::register_form_payment_reference;?>: <strong>SI00 <?php echo $id; ?></strong>
        </div>
      </div>
    </div>
  </div>
</div>
