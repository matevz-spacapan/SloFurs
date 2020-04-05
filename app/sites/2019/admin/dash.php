<?php require 'app/sites/'.THEME.'/admin/sidebar.php';?>
<div class="w3-main" style="margin-left:300px;">

<!-- Header -->
<header class="container-fluid mt-4">
  <h5><b><i class="fal fa-cogs"></i> <?php echo L::admin_dash_h.': '.L::admin_sidebar_overview;?></b></h5>
</header>
<div class="container-fluid row">
    <div class="col">
      <?php
        if($account->status>=SUPER){
          echo '<a href="'.URL.'admin/users">';
        }
      ?>
      <div class="d-flex p-4 mt-3">
        <div class="mr-auto">
          <i class="fal fa-users fa-3x"></i>
          <h4><?php echo L::admin_sidebar_accounts;?></h4>
        </div>
        <div class="row">
          <div class="cell text-center p-2">
            <h5><?php echo L::admin_dash_total;?></h5>
            <p><?php echo $dash_model->accountsB1()->tot;?></p>
          </div>
          <div class="cell text-center p-2">
            <h5><?php echo L::admin_dash_incomplete;?></h5>
            <p><?php echo $dash_model->accountsB2()->tot;?></p>
          </div>
        </div>
      </div>
      <?php if($account->status>=SUPER){echo '</a>';}?>
    </div>

    <div class="col">
      <?php
        if($account->status>=STAFF){
          echo '<a href="'.URL.'admin/event">';
        }
      ?>
      <div class="d-flex p-4 mt-3">
        <div class="mr-auto">
          <i class="fal fa-calendar-alt fa-3x"></i>
          <h4><?php echo L::admin_sidebar_events;?></h4>
        </div>
        <div class="row">
          <div class="cell text-center p-2">
            <h5><?php echo L::admin_dash_total;?></h5>
            <p><?php echo $dash_model->eventsB1()->tot;?></p>
          </div>
          <div class="cell text-center p-2">
            <h5><?php echo L::admin_dash_upcoming;?></h5>
            <p><?php echo $dash_model->eventsB2()->tot;?></p>
          </div>
        </div>
      </div>
      <?php if($account->status>=STAFF){echo '</a>';}?>
    </div>

    <div class="col">
      <?php
        if($account->status>=SUPER){
          echo '<a href="'.URL.'admin/fursuits">';
        }
      ?>
      <div class="d-flex p-4 mt-3">
        <div class="mr-auto">
          <i class="fal fa-paw fa-3x"></i>
          <h4><?php echo L::admin_sidebar_fursuits;?></h4>
        </div>
        <div class="row">
          <div class="cell text-center p-2">
            <h5><?php echo L::admin_dash_total;?></h5>
            <p><?php echo $dash_model->fursuitsB()->tot;?></p>
          </div>
        </div>
      </div>
      <?php if($account->status>=SUPER){echo '</a>';}?>
    </div>

    <div class="col">
      <div class="d-flex p-4 mt-3">
        <div class="mr-auto">
          <i class="fal fa-inbox-out fa-3x"></i>
          <h4><?php echo L::admin_dash_emails;?><br>(<?php echo date('Y-m');?>)</h4>
        </div>
        <?php $result=json_decode($dash_model->emailsB(), true);?>
        <div class="row">
          <div class="cell text-center p-2">
            <h5><?php echo L::admin_dash_sent;?></h5>
            <p><?php echo $result[0]['stats'][0]['metrics']['processed'];?></p>
          </div>
          <div class="cell text-center p-2">
            <h5><?php echo L::admin_dash_opened;?></h5>
            <p><?php echo $result[0]['stats'][0]['metrics']['unique_opens'];?></p>
          </div>
        </div>
      </div>
    </div>
</div>

<!-- Recent users -->
<div class="container-fluid">
  <h3><?php echo L::admin_sidebar_accounts;?></h3>
  <h5><?php echo L::admin_dash_newest;?> 5</h5>
  <div class="table-responsive">
    <table class="table table-striped table-hover thead-light">
      <tr>
        <th><?php echo L::admin_dash_username;?></th>

        <?php if($account->status>=ADMIN): ?>
        <th><?php echo L::admin_dash_email;?></th>
        <th><?php echo L::admin_dash_name;?></th>
        <?php endif;?>

        <th><?php echo L::admin_dash_created;?> <i class="far fa-angle-down"></i></th>
      </tr>
      <?php $res=$dash_model->newest(); ?>
      <?php foreach($res as $acc): ?>
        <tr <?php if($acc->activate!=null&&$acc->newemail==null){echo 'class="table-danger"';}?>>
          <td>
            <?php if(file_exists('public/accounts/'.$acc->pfp.'.png')): ?>
    					<img src="<?php echo URL.'public/accounts/'.$acc->pfp; ?>.png" class="rounded-circle mr-2" style="height:35px">
    				<?php else: ?>
    					<img src="<?php echo URL.'public/img/account.png' ?>" class="rounded-circle mr-2" style="height:35px">
    				<?php endif; ?>
            <?php
              echo $acc->username;
              if($acc->activate!=null&&$acc->newemail==null){
                echo ' (not confirmed)';
              }
            ?></td>

          <?php if($account->status>=ADMIN): ?>
          <td><a href="mailto:<?php echo $acc->email;?>"><?php echo $acc->email;?></a></td>
          <td>
            <?php
              if($acc->fname!=null){
                echo $acc->fname.' '.$acc->lname;
              }
              else{
                echo '<i>'.L::admin_dash_noProfile.'</i>';
              }
            ?>
          </td>
        <?php endif;?>

          <td><?php echo $acc->created;?></td>
        </tr>
      <?php endforeach; ?>
    </table>
  </div>
</div>

<!-- Account countries -->
<div class="container-fluid">
  <h5><?php echo L::admin_dash_countries;?></h5>
  <?php $res=$dash_model->countries(); ?>
  <table class="table table-striped table-hover table-sm">
    <?php foreach($res as $row): ?>
      <tr>
        <td><?php echo $row->country;?></td>
        <td><?php echo $row->tot;?></td>
      </tr>
    <?php endforeach; ?>
  </table>
</div>
<hr>

<!-- Recent changes -->
<?php if($account->status>=SUPER): ?>
  <div class="container-fluid">
    <h5><?php echo L::admin_dash_changes;?></h5>
    <div class="table-responsive">
      <table class="table table-striped table-hover table-sm thead-light">
        <tr>
          <th><?php echo L::admin_dash_who;?></th>
          <th><?php echo L::admin_dash_type;?></th>
          <th><?php echo L::admin_dash_for;?></th>
          <th><?php echo L::admin_dash_time;?></th>
        </tr>
        <?php $res=$dash_model->changes(); ?>
        <?php foreach($res as $row): ?>
          <tr>
            <td>ID <?php echo $row->who;?> (<b><?php echo $row->whoU;?></b>)</td>
            <td><?php echo $row->what;?></td>
            <td>ID <?php echo $row->for_who;?> (<b><?php echo $row->forU;?></b>)</td>
            <td><?php echo $row->changed_at;?></td>
          </tr>
        <?php endforeach; ?>
      </table>
    </div>
  </div>
<?php endif;?>
<script>
$("#dash").addClass("bg-warning");
</script>
