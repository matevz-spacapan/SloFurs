<?php require 'app/sites/'.THEME.'/admin/sidebar.php';?>

<div class="w3-main" style="margin-left:300px;">

  <!-- Header -->
  <header class="container-fluid my-4">
    <h5><b><i class="fal fa-users-cog"></i> <?php echo L::admin_dash_h.': '.L::admin_sidebar_accounts;?></b></h5>
  </header>

  <div class="container-fluid row mb-2">
    <div class="col-3">
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
    </div>
  </div>

  <div class="container-fluid">
    <div class="table-responsive">
      <table class="table table-striped table-hover thead-light">
        <tr>
          <th></th>
          <th><?php echo L::admin_dash_username;?> <i class="far fa-angle-down"></i></th>
          <th><?php echo L::admin_dash_privileges;?></th>
          <th><?php echo L::admin_dash_email;?></th>
          <th><?php echo L::admin_dash_name;?></th>
          <th><?php echo L::admin_dash_created;?></th>
        </tr>
        <?php $res=$dash_model->list(); ?>
        <?php foreach($res as $acc): ?>
          <tr <?php if($acc->activate!=null&&$acc->newemail==null){echo 'class="table-danger"';}?>>
            <td width="40">
              <?php if(file_exists('public/accounts/'.$acc->pfp.'.jpg')): ?>
      					<img src="<?php echo URL.'public/accounts/'.$acc->pfp; ?>.jpg" class="rounded-circle mr-1" style="height:35px">
      				<?php else: ?>
      					<img src="<?php echo URL.'public/img/account.jpg' ?>" class="rounded-circle mr-1" style="height:35px">
      				<?php endif; ?>
            </td>
            <td>
              <?php
                echo $acc->username;
                if($acc->activate!=null&&$acc->newemail==null){
                  echo L::admin_account_notConfirmed;
                }
              ?><br>
              <a href="<?php echo URL;?>admin/users?id=<?php echo $acc->id;?>"><?php echo L::admin_dash_edit;?></a>
            </td>
            <td>
              <?php
                switch($acc->status){
                  case ATTENDEE: echo L::admin_account_status_attendee; break;
                  case PRE_REG: echo L::admin_account_status_preReg; break;
                  case STAFF: echo L::admin_account_status_staff; break;
                  case ADMIN: echo L::admin_account_status_admin; break;
                  case SUPER: echo L::admin_account_status_super; break;
                  case OWNER: echo L::admin_account_status_owner; break;
                }
              ?>
            </td>
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
            <td><?php echo $acc->created;?></td>
          </tr>
        <?php endforeach; ?>
      </table>
    </div>
  </div>
</div>
<script>
$("#users").addClass("bg-warning");
</script>
