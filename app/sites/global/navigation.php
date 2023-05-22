<nav class="navbar navbar-expand-md navbar-light bg-light">
	<?php if(strpos($this->getBaseUrl(), 'admin/')!==false): ?>
		<button class="w3-bar-item w3-button w3-hide-large w3-hover-none w3-hover-text-light-grey" onclick="$('#sidebar').show()"><i class="far fa-bars"></i>  Menu</button>
	<?php endif; ?>
  <a class="navbar-brand" href="<?php echo URL ?>">
      <img src="/public/img/logo.svg" alt="SloFurs Logo"
           style="max-width: 100%; max-height: 100%; height: 50px;">
  </a>

	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar" aria-controls="collapsibleNavbar" aria-expanded="false">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="collapsibleNavbar">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" href="<?php echo URL ?>register"><?php echo L::topnavbar_event;?></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="<?php echo URL ?>rules"><?php echo L::topnavbar_rules;?></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="<?php echo URL ?>ofurry"><?php echo L::topnavbar_furries;?></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="<?php echo URL ?>drustvo"><?php echo L::topnavbar_drustvo;?></a>
      </li>
    </ul>
		<ul class="navbar-nav ml-auto">
			<!-- LANGUAGE SELECTION -->
			<?php
				if(isset($_GET['lang'])&&(strip_tags($_GET['lang'])=='si'||strip_tags($_GET['lang'])=='en')){
					$_SESSION['lang']=strip_tags($_GET['lang']);
				}
			?>
			<a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
				<?php
					if(isset($_SESSION['lang'])&&$_SESSION['lang']=='si'){
						$src1=URL.'public/img/si.jpg';
						$src2=URL.'public/img/en.jpg';
						$lang='si';
					}
					else{
						$src1=URL.'public/img/en.jpg';
						$src2=URL.'public/img/si.jpg';
						$lang='en';
					}
					echo '<img src="'.$src1.'" style="height:23px;" class="rounded-circle">';
					$base='?lang=';
					if(strpos($this->getBaseUrl(), '?')!==false){
						$base=BASEURL.$this->getBaseUrl().'&lang=';
					}
				?>
			</a>
			<div class="dropdown-menu dropdown-menu-right">
				<?php if($lang=='si'): ?>
					<a class="dropdown-item" href="<?php echo $base;?>en"><?php echo '<img src="'.$src2.'" style="height:23px;" class="rounded-circle">';?></a>
				<?php else: ?>
					<a class="dropdown-item" href="<?php echo $base;?>si"><?php echo '<img src="'.$src2.'" style="height:23px;" class="rounded-circle">';?></a>
				<?php endif; ?>
			</div>
			<!-- ACCOUNT ITEMS -->
			<?php if($account!=null): ?>
				<!-- Logged into account -->
				<li class="nav-item dropdown">
		      <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
						<?php
							$src=(file_exists('public/accounts/'.$account->pfp.'.jpg')) ? URL.'public/accounts/'.$account->pfp.'.jpg' : URL.'public/img/account.jpg';
							echo '<img src="'.$src.'" class="rounded-circle" style="height:23px;width:23px">';
						?>
		      </a>
		      <div class="dropdown-menu dropdown-menu-right">
		        <a class="dropdown-item" href="<?php echo URL; ?>account"><?php echo L::topnavbar_myAccount;?></a>
						<?php if($account->status>=STAFF): ?>
		        	<a class="dropdown-item" href="<?php echo URL; ?>admin"><?php echo L::topnavbar_admin;?></a>
						<?php endif; ?>
		        <a class="dropdown-item" href="<?php echo URL; ?>login/logout"><?php echo L::topnavbar_logOut;?></a>
		      </div>
		    </li>
			<?php else: ?>
				<!-- Not logged in -->
				<button type="button" data-toggle="modal" data-target="#loginModal" class="btn btn-link text-body"><?php echo L::topnavbar_logIn;?></button>
			<?php endif; ?>
		</ul>
  </div>
</nav>
