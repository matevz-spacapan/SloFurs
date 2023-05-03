<!-- Header with full-height image -->
<header class="bgimg container-fluid">
  <div class="display-left text-white p-5">
    <span class="display-3 d-none d-md-block"><?php echo L::home_a;?></span><br>
    <span class="display-4 d-md-none"><?php echo L::home_a;?></span><br>
    <h4 style="max-width: 85%"><?php echo L::home_b;?></h4>
  </div>
  <div class="display-bottomleft text-white lead" style="padding:70px 48px">
    <div class="container p-4 bg-light rounded">
      <a href="https://discord.gg/K3dDpw8" class="px-2" target="_blank"><i class="fab fa-discord"></i></a>
      <a href="https://twitter.com/SloFurs" class="px-2" target="_blank"><i class="fab fa-twitter"></i></a>
      <a href="https://www.facebook.com/slofurs" class="px-2" target="_blank"><i class="fab fa-facebook-f"></i></a>
    </div>
  </div>
  <div class="display-bottomright text-white lead" style="padding:90px 48px">
    <?php echo L::home_c;?> Torchfire
  </div>
</header>

<?php
  $reg_model=$this->loadSQL('RegModel');
  $cEvents=$reg_model->getCEvents(true); //upcoming
?>

<div class="jumbotron jumbotron-fluid px-4">
  <div class="container-fluid">
    <h2><?php echo L::home_second_a;?></h2>
    <?php if(count($cEvents)>0): ?>
      <div class="row mx-1">
        <?php foreach($cEvents as $event): ?>
          <?php
            if(new DateTime($event->reg_end)<=new DateTime()){
              $color='text-dark';
              $text=L::admin_event_text_closed;;
            }
            elseif(new DateTime($event->reg_start)<=new DateTime()){
              $color='text-primary';
              $text=L::admin_event_text_reg.'<br>'.$reg_model->convertViewable($event->reg_end, 2);
            }
            elseif($event->pre_reg_start!=$event->reg_start && new DateTime($event->pre_reg_start)<=new DateTime() && isset($account) && $account->status>=PRE_REG){
              $color='text-info';
              $text=L::admin_event_text_pre.'<br>'.$reg_model->convertViewable($event->reg_start, 2);
            }
            else{
              $color='text-dark';
              $text=L::admin_event_text_until.'<br>';
              $date=(isset($account) && $account->status>=PRE_REG)?$reg_model->convertViewable($event->pre_reg_start, 2):$reg_model->convertViewable($event->reg_start, 2);
              $text=$text.$date;
            }
              require 'app/sites/'.THEME.'/reg/evt.php';
          ?>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <p><?php echo L::admin_event_noUpcoming;?></p>
    <?php endif; ?>
    <hr>
    <p><?php echo L::home_first_c;?></p>
    <p><?php echo L::home_first_d;?></p>
  </div>
</div>

<!-- Social -->
<div class="jumbotron jumbotron-fluid bg-transparent">
  <div class="container-fluid text-center">
    <a href="https://discord.gg/K3dDpw8" class="px-3 display-4" target="_blank"><i class="fab fa-discord"></i></a>
    <a href="https://twitter.com/SloFurs" class="px-3 display-4" target="_blank"><i class="fab fa-twitter"></i></a>
    <a href="https://www.facebook.com/slofurs" class="px-3 display-4" target="_blank"><i class="fab fa-facebook-f"></i></a>
 </div>
</div>
