<!-- Admin Sidebar -->
<nav class="w3-sidebar w3-collapse w3-white w3-animate-left" style="z-index:3;width:300px;" id="sidebar"><br>
  <div class="w3-container w3-row">
    <div class="w3-col s4">
      <?php $src=(file_exists('public/accounts/'.$account->pfp.'.png')) ? URL.'public/accounts/'.$account->pfp.'.png' : URL.'public/img/account.png';
			echo '<img src="'.$src.'" class="w3-circle w3-margin-right" style="height:80px;">'; ?>
    </div>
    <div class="w3-col s8 w3-bar">
      <?php echo L::admin_sidebar_hello;?>, <strong><?php echo $account->username;?></strong><br>
      <?php echo L::admin_sidebar_privileges;?>: <b>
      <?php
        switch($account->status){
          case OWNER: echo L::admin_sidebar_status_owner; break;
          case SUPER: echo L::admin_sidebar_status_super; break;
          case ADMIN: echo L::admin_sidebar_status_admin; break;
          case STAFF: echo L::admin_sidebar_status_staff; break;
        }
      ?>
      </b>
    </div>
  </div>
  <hr>
  <div class="w3-container">
    <h5><?php echo L::admin_sidebar_adminPanel;?></h5>
  </div>
  <div class="w3-bar-block">
    <a href="#" class="w3-bar-item w3-button w3-padding-16 w3-hide-large w3-dark-grey w3-hover-black" onclick="$('#sidebar').hide()" title="close menu"><i class="far fa-times"></i> <?php echo L::admin_sidebar_close;?></a>
    <a href="<?php echo URL;?>admin/dash" class="w3-bar-item w3-button w3-padding" id="dash"><i class="far fa-eye"></i> <?php echo L::admin_sidebar_overview;?></a>
		<button onclick="dropdown()" class="w3-bar-item w3-button w3-padding" id="events_list"><i class="fal fa-calendar"></i> <?php echo L::admin_sidebar_events;?> <i class="far fa-angle-down"></i></button>
		<script>
			function dropdown(){
				if($('#dropdown').hasClass("w3-show")){
					$('#dropdown').removeClass("w3-show");
				}
				else{
					$('#dropdown').addClass("w3-show");
				}
			}
		</script>
		<div id="dropdown" class="w3-hide">
	    <a href="<?php echo URL; ?>admin/event" class="w3-bar-item w3-button" style="padding-left: 30px;" id="event"><i class="fal fa-calendar-alt"></i> <?php echo L::admin_sidebar_viewEvt;?></a>
			<a href="<?php echo URL; ?>admin/event/new" class="w3-bar-item w3-button" style="padding-left: 30px;" id="new_event"><i class="far fa-calendar-plus"></i> <?php echo L::admin_sidebar_newEvt;?></a>
		</div>
		<?php if($account->status>=SUPER): ?>
	    <a href="<?php echo URL; ?>admin/users" class="w3-bar-item w3-button w3-padding" id="users"><i class="fal fa-users-cog"></i> <?php echo L::admin_sidebar_accounts;?></a>
  		<div id="editing" class="w3-hide">
  	    <a href="#" class="w3-bar-item w3-button" style="padding-left: 30px;" id="event"><i class="fal fa-user-cog"></i> <?php echo L::admin_sidebar_editAcc;?></a>
  		</div>
	    <a href="<?php echo URL; ?>admin/fursuits" class="w3-bar-item w3-button w3-padding" id="fursuits"><i class="fal fa-paw"></i> <?php echo L::admin_sidebar_fursuits;?></a><br><br>
		<?php endif; ?>
  </div>
</nav>
