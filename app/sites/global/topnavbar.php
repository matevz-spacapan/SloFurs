<div class="w3-bar w3-large">
	<a href="<?php echo URL ?>" class="w3-bar-item w3-button"><?php echo L::topnavbar_home;?></a>
	<?php if($account!=null): ?>
		<a href="<?php echo URL ?>register" class="w3-bar-item w3-button w3-hide-small"><?php echo L::topnavbar_event;?></a>
	<?php endif; ?>
	<a href="https://slofurs.org/" class="w3-bar-item w3-button w3-hide-small" target="_blank"><?php echo L::topnavbar_blog;?> <i class="far fa-external-link"></i></a>

	<?php if($account!=null): ?>
		<!-- Logged into account -->
		<div class="w3-dropdown-hover w3-right w3-hide-small">
		<button class="w3-button" title="<?php echo L::topnavbar_account;?>">
			<?php
				$src=(file_exists('public/accounts/'.$account->pfp.'.png')) ? URL.'public/accounts/'.$account->pfp.'.png' : URL.'public/img/account.png';
				echo '<img src="'.$src.'" class="w3-circle" style="height:23px;width:23px">';
			?>
		</button>
		<div class="w3-dropdown-content w3-bar-block" style="right:0">
			<a href="<?php echo URL; ?>account" class="w3-bar-item w3-button w3-mobile"><?php echo L::topnavbar_myAccount;?></a>
			<?php if($account->status>=STAFF): ?>
				<a href="<?php echo URL; ?>admin" class="w3-bar-item w3-button w3-mobile"><?php echo L::topnavbar_admin;?></a>
			<?php endif; ?>
			<a href="<?php echo URL; ?>login/logout" class="w3-bar-item w3-button w3-mobile"><?php echo L::topnavbar_logOut;?></a>
		</div>
		</div>

	<?php else: ?>
		<!-- Not logged in -->
		<a href="<?php echo URL; ?>login" class="w3-bar-item w3-button w3-hide-small w3-right w3-padding-large w3-hover-white"><?php echo L::topnavbar_logIn;?></a>
	<?php endif; ?>
	<!-- LANGUAGE SELECTION -->
	<?php
		if(isset($_GET['lang'])&&($_GET['lang']=='si'||$_GET['lang']=='en')){
			$_SESSION['lang']=$_GET['lang'];
		}
	?>
	<div class="w3-dropdown-hover w3-right w3-hide-small">
		<button class="w3-button" title="<?php echo L::topnavbar_language;?>">
			<?php
				if(isset($_SESSION['lang'])&&$_SESSION['lang']=='si'){
					$src1=URL.'public/img/si.png';
					$src2=URL.'public/img/en.png';
					$lang='si';
				}
				else{
					$src1=URL.'public/img/en.png';
					$src2=URL.'public/img/si.png';
					$lang='en';
				}
				echo '<img src="'.$src1.'" style="height:23px;">';
				$base='?lang=';
				if(STRPOS($_SERVER['REQUEST_URI'], '?')!==false){
					$base=BASEURL.$_SERVER['REQUEST_URI'].'&lang=';
				}
			?>
		</button>
		<div class="w3-dropdown-content w3-bar-block" style="right:0">
			<?php if($lang=='si'): ?>
				<a href="<?php echo $base;?>en" class="w3-bar-item w3-button w3-mobile w3-center"><?php echo '<img src="'.$src2.'" style="height:23px;">';?></a>
			<?php else: ?>
				<a href="<?php echo $base;?>si" class="w3-bar-item w3-button w3-mobile w3-center"><?php echo '<img src="'.$src2.'" style="height:23px;">';?></a>
			<?php endif; ?>
		</div>
	</div>
	<a href="javascript:void(0)" class="w3-bar-item w3-button w3-right w3-hide-large w3-hide-medium" onclick="openNav()">&#9776;</a>
</div>


<!-- Small screen navbar -->
<div id="navSmall" class="w3-bar-block w3-theme-d2 w3-hide w3-hide-large w3-hide-medium w3-large">
	<a href="https://slofurs.org/" class="w3-bar-item w3-button w3-padding-large" target="_blank"><?php echo L::topnavbar_blog;?> <i class="far fa-external-link"></i></a>
	<?php if($account!=null): ?>
		<a href="<?php echo URL ?>register" class="w3-bar-item w3-button w3-padding-large"><?php echo L::topnavbar_event;?></a>
		<a href="<?php echo URL; ?>account" class="w3-bar-item w3-button w3-padding-large"><?php echo L::topnavbar_myAccount;?></a>
		<?php if($account->status>=STAFF): ?>
			<a href="<?php echo URL; ?>admin" class="w3-bar-item w3-button w3-padding-large"><?php echo L::topnavbar_admin;?></a>
		<?php endif; ?>
		<a href="<?php echo URL; ?>login/logout" class="w3-bar-item w3-button w3-padding-large"><?php echo L::topnavbar_logOut;?></a>
	<?php else: ?>
		<!-- Not logged in -->
		<a href="<?php echo URL; ?>login" class="w3-bar-item w3-button w3-padding-large"><?php echo L::topnavbar_logIn;?></a>
	<?php endif; ?>
</div>
<script>
	function openNav(){
		if($("#navSmall").hasClass("w3-show")){
			$("#navSmall").addClass("w3-hide");
			$("#navSmall").removeClass("w3-show");
		}
		else{
			$("#navSmall").addClass("w3-show");
			$("#navSmall").removeClass("w3-hide");
		}
	}
</script>
