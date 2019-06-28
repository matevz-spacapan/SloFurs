<div class="w3-bar w3-large">
	<a href="<?php echo URL ?>" class="w3-bar-item w3-button">Home</a>
	<a href="https://slofurs.org/" class="w3-bar-item w3-button">Blog</a>

	<?php if($account!=null): ?>
		<!-- Logged into account -->
		<div class="w3-dropdown-hover w3-right w3-hide-small">
		<button class="w3-button" title="Account">
			<?php
				$src=(file_exists('public/accounts/'.$account->pfp.'.png')) ? URL.'public/accounts/'.$account->pfp.'.png' : URL.'public/img/account.png';
				echo '<img src="'.$src.'" class="w3-circle" style="height:23px;width:23px">';
			?>
		</button>
		<div class="w3-dropdown-content w3-bar-block" style="right:0">
			<a href="<?php echo URL; ?>account" class="w3-bar-item w3-button w3-mobile">My account</a>
			<?php if($account->status>=2): ?>
				<a href="<?php echo URL; ?>admin" class="w3-bar-item w3-button w3-mobile">Admin</a>
			<?php endif; ?>
			<a href="<?php echo URL; ?>login/logout" class="w3-bar-item w3-button w3-mobile">Log out</a>
		</div>
		</div>

	<?php else: ?>
		<!-- Not logged in -->
		<a href="<?php echo URL; ?>login" class="w3-bar-item w3-button w3-hide-small w3-right w3-padding-large w3-hover-white">Log in</a>
	<?php endif; ?>
	<a href="javascript:void(0)" class="w3-bar-item w3-button w3-right w3-hide-large w3-hide-medium" onclick="openNav()">&#9776;</a>
</div>


<!-- Small screen navbar -->
<div id="navSmall" class="w3-bar-block w3-theme-d2 w3-hide w3-hide-large w3-hide-medium w3-large">
	<?php if($account!=null): ?>
		<a href="<?php echo URL; ?>account" class="w3-bar-item w3-button w3-padding-large">My account</a>
		<a href="<?php echo URL; ?>login/logout" class="w3-bar-item w3-button w3-padding-large">Log out</a>
	<?php else: ?>
		<!-- Not logged in -->
		<a href="<?php echo URL; ?>login" class="w3-bar-item w3-button w3-padding-large">Log in</a>
	<?php endif; ?>
</div>
<script>
	function openNav(){
		var x = document.getElementById("navSmall");
		if (x.className.indexOf("w3-show")==-1){
			x.className+=" w3-show";
		}
		else{ 
			x.className = x.className.replace(" w3-show", "");
		}
	}
</script>