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
<button class="btn-block btn btn-success mt-2" data-toggle="modal" data-target="#payment" id="payButton">Plačaj zdaj<?php //echo L::register_form_buttonView;?></button>
<div id="payment" class="modal fade">
  <div class="modal-dialog modal-lg">
    <div class="modal-content" style="max-width:650px">
      <div class="modal-header">
        <h4 class="modal-title text-primary">Plačilo vstopnine<?php //echo L::register_form_modal_h;?></h4>
        <button type="button" class="close" data-dismiss="modal" style="font-weight: 1;">Plačaj kasneje &times;</button>
      </div>
      <div class="modal-body">
        <?php
          $pending_list=$reg_model->pendingPayments($id);
        ?>
        <div class="text-center">
          <h5>Skupaj za plačilo: <?php //echo L::register_form_modal_prices_h;?><?php echo $price;?>€</h5>
          <small class="text-muted"><i>Cena vsebuje 8,5% DDV. Vsa plačila so obvezujoča (po plačilu ni možno vračilo celotne kupnine). Pri plačilu veljajo <a href="<?php echo URL;?>rules" target="_blank">SloFurs pogoji poslovanja<i class="far fa-external-link"></i></a>.</i></small><br><br>
          <button class="btn btn-primary" onclick="goStripe()" id="StripeStart" <?php if(count($pending_list)>0){echo 'disabled';} ?>><i class="fab fa-stripe fa-3x"></i><br>spletno plačilo</button>
        </div>
        <?php if(count($pending_list)>0): ?>
          <div><br>
            <p class="text-danger">Imate plačila v obdelavi. <b>Bodite pozorni pri nadaljnih plačilih, saj lahko pride do dvokratnega plačila!</b> V primeru dvoma, <a href="mailto:slofurs@gmail.com" target="_blank">nas kontaktirajte</a>.</p>
            <?php foreach($pending_list as $pending_item): ?>
              <p><?php echo $pending_item->amount; ?>€ <?php echo $pending_item->start_time; ?></p>
          </div>
          <?php endforeach; ?>
        <?php endif; ?>
        <hr>
        <div>V primeru, da ne želite opraviti plačila preko spleta lahko znesek nakažete na naš TRR.<br>
        Naziv prejemnika: <strong>Društvo SloFurs</strong><br>
        IBAN: <strong>SI56 0000 0000 0000 000</strong><br>
        BIC: <strong>it gon be here, nigga</strong><br>
        Naslov: <strong>Gregorčičeva ulica 33, 5000 Nova Gorica</strong><br>
        Koda namena: <strong>GDSV</strong><br>
        Referenca: <strong>SI00 <?php echo $id; ?></strong>
        </div>
      </div>
    </div>
  </div>
</div>
