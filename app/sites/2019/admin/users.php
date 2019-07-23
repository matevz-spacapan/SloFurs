<body class="w3-light-gray">

<?php require 'app/sites/'.THEME.'/admin/sidebar.php';?>

<div class="w3-main" style="margin-left:300px;">

  <!-- Header -->
  <header class="w3-container" style="padding-top:22px">
    <h5><b><i class="fal fa-users-cog"></i> <?php echo L::admin_dash_h.': '.L::admin_sidebar_accounts;?></b></h5>
  </header>

  <div class="w3-row-padding w3-margin-bottom">
    <div class="w3-quarter">
      <div class="w3-container w3-gray w3-padding-16">
        <div class="w3-left">
          <i class="fal fa-users w3-xxxlarge"></i>
          <h4><?php echo L::admin_sidebar_accounts;?></h4>
        </div>
        <div class="w3-right">
          <div class="w3-cell w3-center w3-padding-small">
            <h5><?php echo L::admin_dash_total;?></h5>
            <p><?php echo $dash_model->accountsB1()->tot;?></p>
          </div>
          <div class="w3-cell w3-center w3-padding-small">
            <h5><?php echo L::admin_dash_incomplete;?></h5>
            <p><?php echo $dash_model->accountsB2()->tot;?></p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="w3-container">
    <h3><?php echo L::admin_sidebar_accounts;?></h3>
    <div class="w3-responsive">
      <table class="w3-table w3-striped w3-hoverable w3-white">
        <tr>
          <th></th>
          <th><?php echo L::admin_dash_username;?> <i class="far fa-angle-down"></i></th>
          <th>Privileges</th>
          <th><?php echo L::admin_dash_email;?></th>
          <th><?php echo L::admin_dash_name;?></th>
          <th><?php echo L::admin_dash_created;?></th>
        </tr>
        <?php $res=$dash_model->list(); ?>
        <?php foreach($res as $acc): ?>
          <tr>
            <td width="40">
              <?php if(file_exists('public/accounts/'.$acc->pfp.'.png')): ?>
      					<img src="<?php echo URL.'public/accounts/'.$acc->pfp; ?>.png" class="w3-circle w3-margin-right" style="height:35px">
      				<?php else: ?>
      					<img src="<?php echo URL.'public/img/account.png' ?>" class="w3-circle w3-margin-right" style="height:35px">
      				<?php endif; ?>
            </td>
            <td>
              <?php echo $acc->username;?><br>
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
$("#users").addClass("w3-orange");
</script>
