<script src="https://js.stripe.com/v3/"></script>
<script>
  var stripe = Stripe('<?php echo STRIPE_PUBLIC; ?>');
  function goStripe(){
    stripe.redirectToCheckout({
    sessionId: '<?php echo $session['id']; ?>'
    }).then(function (result) {
      // If `redirectToCheckout` fails due to a browser or network
      // error, display the localized error message to your customer
      // using `result.error.message`.
    });
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
          if($event->ticket=='regular'){
            $price=$event->regular_price;
          }
          elseif($event->ticket=='sponsor'){
            $price=$event->sponsor_price;
          }
          else{
            $price=$event->super_price;
          }
        ?>
        <div class="text-center">
          <h5>Skupaj za plačilo: <?php //echo L::register_form_modal_prices_h;?><?php echo $price;?>€</h5>
          <small class="text-muted"><i>Cena vsebuje 8,5% DDV. Vsa plačila so obvezujoča (po plačilu ni možno vračilo celotne kupnine). Pri plačilu veljajo <a href="<?php echo URL;?>rules" target="_blank">pogoji SloFurs srečanj <i class="far fa-external-link"></i></a>.</i></small><br><br>
          <button class="btn btn-primary" onclick="goStripe()"><i class="fab fa-stripe fa-3x"></i><br>spletno plačilo</button>
        </div>
        <hr>
        <div>V primeru, da ne želite opraviti plačila preko spleta lahko znesek nakažete na naš TRR.<br>
        Naziv prejemnika: <strong>Društvo SloFurs</strong><br>
        IBAN: <strong>SI56 0000 0000 0000 000</strong><br>
        BIC: <strong>it gon be here, nigga</strong><br>
        Naslov: <strong>Gregorčičeva ulica 33, 5000 Nova Gorica</strong><br>
        Koda namena: <strong>GDSV</strong><br>
        Referenca: <strong>SI00 <?php echo $event->id; ?></strong>
        </div>
      </div>
    </div>
  </div>
</div>
