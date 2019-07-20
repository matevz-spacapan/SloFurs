<!-- Header with full-height image -->
<header class="bgimg-1 w3-display-container w3-grayscale-min">
  <div class="w3-display-left w3-text-white" style="padding:48px">
    <span class="w3-jumbo w3-hide-small">Welcome to SloFurs!</span><br>
    <span class="w3-xxlarge w3-hide-large w3-hide-medium">Welcome to SloFurs!</span><br>
    <span class="w3-large">This is our new home for events.</span>
  </div>
  <div class="w3-display-bottomleft w3-text-white w3-large" style="padding:70px 48px">
    <a href="https://www.facebook.com/slofurs" style="padding-right:10px;" target="_blank"><i class="fab fa-facebook-f w3-hover-opacity"></i></a>
    <a href="https://twitter.com/SloFurs" target="_blank"><i class="fab fa-twitter w3-hover-opacity"></i></a>
  </div>
  <div class="w3-display-bottomright w3-text-white w3-large" style="padding:70px 48px">
    Image by <a href="https://twitter.com/onyxdesignsfx">Onyx</a>
  </div>
</header>

<!-- First Grid -->
<div class="w3-row-padding w3-padding-64 w3-container">
  <div class="w3-content">
    <div class="w3-twothird">
      <h1>Our new event home</h1>
      <h5 class="w3-padding-32">Welcome to SloFurs' new home for events! This is where we'll handle registrations for our events from now on.</h5>

      <p class="w3-text-gray">It is worth mentioning that these pages are still being worked on. There might be things that don't work properly or that break.</p>
      <p class="w3-text-gray">If you find any bugs, please send a message to <a href="http://t.me/pur3bolt" target="_blank">Pur3Bolt <i class="far fa-external-link"></i></a> on Telegram.</p>
      <p class="w3-text-gray">You can check if an issue has already been reported to me on <a href="https://trello.com/b/OKE6arXk/slofurs-registration" target="_blank">this Trello <i class="far fa-external-link"></i></a>. You can also vote for already made suggestins if you have an account on the website (this way I can prioritize things to work on). Thanks!</p>
    </div>
  </div>
</div>

<?php
  $reg_model=$this->loadSQL('RegModel');
  $cEvents=$reg_model->getCEvents(true); //upcoming
?>

<!-- Second Grid -->
  <div class="w3-row-padding w3-light-gray w3-padding-64 w3-container">
    <div class="w3-content">
      <h1>Our upcoming events</h1>
      <?php if(count($cEvents)>0): ?>
        <?php if(!isset($_SESSION['account'])): ?>
          <h5 class="w3-padding-16"><a href="<?php echo URL;?>login">Log in</a> to your account so you can register for these events! We'll be very happy to see you join us <i class="fal fa-laugh-beam"></i></h5>
        <?php else: ?>
          <h5 class="w3-padding-16">If you'd like to register for any of these events, just click on it <i class="fal fa-laugh-beam"></i></h5>
        <?php endif; ?>
        <div class="w3-row">
  				<?php foreach($cEvents as $event): ?>
  					<?php
  						if(new DateTime($event->reg_end)<=new DateTime()){
  							$color='w3-light-gray';
  							$text=L::admin_event_text_closed;;
  						}
  						elseif(new DateTime($event->reg_start)<=new DateTime()){
  							$color='w3-blue';
  							$text=L::admin_event_text_reg.'<br>'.$reg_model->convertViewable($event->reg_end, 2);
  						}
  						elseif($event->pre_reg_start!=$event->reg_start && new DateTime($event->pre_reg_start)<=new DateTime() && isset($account) && $account->status>=PRE_REG){
  							$color='w3-light-blue';
  							$text=L::admin_event_text_pre.'<br>'.$reg_model->convertViewable($event->reg_start, 2);
  						}
  						else{
  							$color='w3-light-gray';
  							$text=L::admin_event_text_until.'<br>';
  							$date=(isset($account) && $account->status>=PRE_REG)?$reg_model->convertViewable($event->pre_reg_start, 2):$reg_model->convertViewable($event->reg_start, 2);
  							$text=$text.$date;
  						}
              echo '<a href="'.URL.'register/new?id='.$event->id.'" style="text-decoration: none;">';
  						  require 'app/sites/'.THEME.'/reg/evt.php';
              echo '</a>';
  					?>
  				<?php endforeach; ?>
    		</div>
      <?php else: ?>
        <p><?php echo L::admin_event_noUpcoming;?></p>
      <?php endif; ?>
    </div>
  </div>
<div class="w3-container w3-black w3-center w3-opacity w3-padding-64">
    <h1 class="w3-margin w3-xlarge"><i class="fas fa-paw-claws"></i> STAY FUZZY <i class="fas fa-paw-claws"></i></h1>
</div>

<!-- Footer -->
<div class="w3-container w3-padding-64 w3-center w3-opacity">
  <div class="w3-xlarge w3-padding-32">
    <a href="https://www.facebook.com/slofurs" style="padding-right:10px;" target="_blank"><i class="fab fa-facebook-f w3-hover-opacity"></i></a>
    <a href="https://twitter.com/SloFurs" target="_blank"><i class="fab fa-twitter w3-hover-opacity"></i></a>
 </div>
</div>
