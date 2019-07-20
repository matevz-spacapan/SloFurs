<body class="w3-light-gray">

<?php require 'app/sites/'.THEME.'/admin/sidebar.php';?>

<div class="w3-main" style="margin-left:300px;">

  <!-- Header -->
  <header class="w3-container" style="padding-top:22px">
    <h5><b><i class="fal fa-cogs"></i> Dashboard</b></h5>
  </header>

  <div class="w3-row-padding w3-margin-bottom">
    <div class="w3-quarter">
      <?php
        if($account->status>=SUPER){
          echo '<a href="'.URL.'admin/users">';
          $color='w3-pale-green';
        }
        else{
          $color='w3-gray';
        }
      ?>
      <div class="w3-container <?php echo $color;?> w3-padding-16">
        <div class="w3-left">
          <i class="fal fa-users w3-xxxlarge"></i>
          <h4>Accounts</h4>
        </div>
        <div class="w3-right">
          <div class="w3-cell w3-center w3-padding-small">
            <h5>Total</h5>
            <p><?php echo $dash_model->accountsB1()->tot;?></p>
          </div>
          <div class="w3-cell w3-center w3-padding-small">
            <h5>Incomplete</h5>
            <p><?php echo $dash_model->accountsB2()->tot;?></p>
          </div>
        </div>
      </div>
      <?php if($account->status>=SUPER){echo '</a>';}?>
    </div>

    <div class="w3-quarter">
      <?php
        if($account->status>=ADMIN){
          echo '<a href="'.URL.'admin/event">';
          $color='w3-pale-green';
        }
        else{
          $color='w3-gray';
        }
      ?>
      <div class="w3-container <?php echo $color;?> w3-padding-16">
        <div class="w3-left">
          <i class="fal fa-calendar-alt w3-xxxlarge"></i>
          <h4>Events</h4>
        </div>
        <div class="w3-right">
          <div class="w3-cell w3-center w3-padding-small">
            <h5>Total</h5>
            <p><?php echo $dash_model->eventsB1()->tot;?></p>
          </div>
          <div class="w3-cell w3-center w3-padding-small">
            <h5>Upcoming</h5>
            <p><?php echo $dash_model->eventsB2()->tot;?></p>
          </div>
        </div>
      </div>
      <?php if($account->status>=ADMIN){echo '</a>';}?>
    </div>


    <div class="w3-quarter">
      <?php
        if($account->status>=SUPER){
          echo '<a href="'.URL.'admin/fursuits">';
          $color='w3-pale-green';
        }
        else{
          $color='w3-gray';
        }
      ?>
      <div class="w3-container <?php echo $color;?> w3-padding-16">
        <div class="w3-left">
          <i class="fal fa-paw w3-xxxlarge"></i>
          <h4>Fursuits</h4>
        </div>
        <div class="w3-right">
          <div class="w3-cell w3-center w3-padding-small">
            <h5>Total</h5>
            <p><?php echo $dash_model->fursuitsB()->tot;?></p>
          </div>
        </div>
      </div>
      <?php if($account->status>=SUPER){echo '</a>';}?>
    </div>
    <div class="w3-quarter">
      <div class="w3-container w3-gray w3-padding-16">
        <div class="w3-left">
          <i class="fal fa-inbox-out w3-xxxlarge"></i>
          <h4>Emails</h4>
        </div>
        <?php $result=json_decode($dash_model->emailsB(), true);?>
        <div class="w3-right">
          <div class="w3-cell w3-center w3-padding-small">
            <h5>Sent</h5>
            <p><?php echo $result[0]['stats'][0]['metrics']['processed'];?></p>
          </div>
          <div class="w3-cell w3-center w3-padding-small">
            <h5>Opened</h5>
            <p><?php echo $result[0]['stats'][0]['metrics']['unique_opens'];?></p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="w3-panel">
    <div class="w3-row-padding" style="margin:0 -16px">
      <h5>Recent changes</h5>
      <table class="w3-table w3-striped w3-hoverable w3-white">
        <tr>
          <td><i class="fa fa-user w3-text-blue w3-large"></i></td>
          <td>New record, over 90 views.</td>
          <td><i>10 mins</i></td>
        </tr>
        <tr>
          <td><i class="fa fa-bell w3-text-red w3-large"></i></td>
          <td>Database error.</td>
          <td><i>15 mins</i></td>
        </tr>
        <tr>
          <td><i class="fa fa-users w3-text-yellow w3-large"></i></td>
          <td>New record, over 40 users.</td>
          <td><i>17 mins</i></td>
        </tr>
        <tr>
          <td><i class="fa fa-comment w3-text-red w3-large"></i></td>
          <td>New comments.</td>
          <td><i>25 mins</i></td>
        </tr>
        <tr>
          <td><i class="fa fa-bookmark w3-text-blue w3-large"></i></td>
          <td>Check transactions.</td>
          <td><i>28 mins</i></td>
        </tr>
        <tr>
          <td><i class="fa fa-laptop w3-text-red w3-large"></i></td>
          <td>CPU overload.</td>
          <td><i>35 mins</i></td>
        </tr>
        <tr>
          <td><i class="fa fa-share-alt w3-text-green w3-large"></i></td>
          <td>New shares.</td>
          <td><i>39 mins</i></td>
        </tr>
      </table>
    </div>
  </div>
  <hr>

  <div class="w3-container">
    <h3>Accounts</h3>
    <h5>Newest</h5>
    <table class="w3-table w3-striped w3-hoverable w3-white">
      <tr>
        <th>Username</th>
        <th>Email</th>
        <th>Name</th>
        <th>Created <i class="far fa-angle-down"></i></th>
      </tr>
      <?php $res=$dash_model->newest(); ?>
      <?php foreach($res as $acc): ?>
        <tr>
          <td><img src="<?php echo URL;?>public/accounts/<?php echo $acc->pfp;?>.png" class="w3-circle w3-margin-right" style="height:35px"> <?php echo $acc->username;?></td>
          <td><?php echo $acc->email;?></td>
          <td>
            <?php
              if($acc->fname!=null){
                echo $acc->fname.' '.$acc->lname;
              }
              else{
                echo '<i>Profile not complete</i>';
              }
            ?>
          </td>
          <td><?php echo $acc->created;?></td>
        </tr>
      <?php endforeach; ?>
    </table>
  </div>

  <div class="w3-container">
    <h5>Top countries</h5>
    <?php $res=$dash_model->countries(); ?>
    <table class="w3-table w3-striped w3-hoverable w3-white">
      <?php foreach($res as $row): ?>
        <tr>
          <td><?php echo $row->country;?></td>
          <td><?php echo $row->tot;?></td>
        </tr>
      <?php endforeach; ?>
    </table>
  </div>
</div>
<script>
$("#dash").addClass("w3-orange");
</script>
