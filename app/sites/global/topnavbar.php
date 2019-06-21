<div class="w3-bar w3-large">
	<a href="<?php echo URL ?>" class="w3-bar-item w3-button">Home</a>

	<?php if(isset($_SESSION['account'])&&$_SESSION['account']!=null): ?>
		<!-- Logged into account -->
		<div class="w3-dropdown-hover w3-right w3-hide-small">
		<button class="w3-button" title="Account">
			<?php if(file_exists('public/accounts/'.$_SESSION['account'].'.png')): ?>
				<img src="<?php echo URL.'public/accounts/'.$_SESSION['account']; ?>.png" class="w3-circle" style="height:23px;width:23px">
			<?php else: ?>
				<img src="public/img/account.png" class="w3-circle" style="height:23px;width:23px">
			<?php endif; ?>
		</button>
		<div class="w3-dropdown-content w3-bar-block" style="right:0">
			 <a href="<?php echo URL; ?>account" class="w3-bar-item w3-button w3-mobile">My account</a>
			 <a href="<?php echo URL; ?>login/logout" class="w3-bar-item w3-button w3-mobile">Log out</a>
		</div>
		</div>

	<?php else: ?>
		<!-- Not logged in -->
		<a href="./login" class="w3-bar-item w3-button w3-hide-small w3-right w3-padding-large w3-hover-white">Log in</a>
	<?php endif; ?>
	<a href="javascript:void(0)" class="w3-bar-item w3-button w3-right w3-hide-large w3-hide-medium" onclick="openNav()">&#9776;</a>
</div>


<!-- TODO small navbar, example from w3.css -->
<div id="navSmall" class="w3-bar-block w3-theme-d2 w3-hide w3-hide-large w3-hide-medium w3-large">
	<a href="<?php echo URL; ?>account" class="w3-bar-item w3-button w3-padding-large">My account</a>
	<a href="<?php echo URL; ?>login/logout" class="w3-bar-item w3-button w3-padding-large">Log out</a>
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