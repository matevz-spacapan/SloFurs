<?php if(count($attendees)>0): ?>
<div class="tab-pane container-fluid fade mt-3" id="Attendees">
  <h3><?php echo L::register_form_stats_attendees;?></h3>
  <div class="row">
    <?php foreach($attendees as $attendee): ?>
        <div class="card m-2 card-round" style="width:150px; min-height:150px;">
        <?php if(file_exists('public/accounts/'.$attendee->pfp.'.png')): ?>
          <img src="<?php echo URL.'public/accounts/'.$attendee->pfp; ?>.png" class="roundImg">
        <?php else: ?>
          <img src="<?php echo URL.'public/img/account.png' ?>" class="roundImg">
        <?php endif; ?>
        <div class="text-center">
          <b><?php echo $attendee->username;?></b><br>
          <?php if($attendee->ticket=='sponsor'): ?>
            <i class="fal fa-heart" title="<?php echo L::register_form_stats_sponsor;?>"></i>
          <?php elseif($attendee->ticket=='super'): ?>
            <i class="fas fa-heart" title="<?php echo L::register_form_stats_super;?>"></i>
          <?php endif; ?>
          <?php if($attendee->fursuiter==1): ?>
            <i class="fas fa-paw" title="<?php echo L::register_form_stats_fursuiter;?>"></i>
          <?php endif; ?>
          <?php if($attendee->artist==1): ?>
            <i class="fas fa-paint-brush" title="<?php echo L::register_form_stats_artist;?>"></i>
          <?php endif; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
  <?php $fursuits=$reg_model->getFursuits($evt_id); ?>
  <?php if(count($fursuits)>0): ?>
    <h3><?php echo L::register_form_stats_fursuiters;?></h3>
    <div class="row">
      <?php foreach($fursuits as $fursuit): ?>
        <div class="card m-2 card-round" style="width: 220px;">
          <?php if(file_exists('public/fursuits/'.$fursuit->img.'.png')): ?>
            <img src="<?php echo URL.'public/fursuits/'.$fursuit->img; ?>.png" class="roundImg">
          <?php else: ?>
            <img src="<?php echo URL.'public/img/account.png' ?>" class="roundImg">
          <?php endif; ?>
          <div class="text-center"><b><?php echo $fursuit->name;?></b><br>
          (<?php echo L::admin_overview_fursuiters_owned;?> <?php echo $fursuit->username;?>)<br>
          <?php echo $fursuit->animal;?></div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>
<?php endif; ?>
