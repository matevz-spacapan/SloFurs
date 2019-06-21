<div class="w3-top">
	<div class="w3-bar w3-theme-d2 w3-left-align w3-large">
		<a class="w3-bar-item w3-button w3-hide-medium w3-hide-large w3-right w3-padding-large w3-hover-white w3-large w3-theme-d2" href="javascript:void(0);" onclick="openNav()"><i class="fa fa-bars"></i></a>
		<a href="./" class="w3-bar-item w3-button w3-padding-large w3-theme-d4"><i class="fa fa-home w3-margin-right"></i>Home</a>
		<a href="#" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white" title="News"><i class="fa fa-globe"></i></a>
		<div class="w3-dropdown-hover w3-hide-small">
		<button class="w3-button w3-padding-large" title="Notifications"><i class="fa fa-bell"></i></button>     
		<div class="w3-dropdown-content w3-card-4 w3-bar-block" style="width:300px">
			 <a href="#" class="w3-bar-item w3-button">Dropdown 1</a>
			 <a href="#" class="w3-bar-item w3-button">Dropdown 2</a>
			 <a href="#" class="w3-bar-item w3-button">Dropdown 3</a>
		</div>
		</div>
		<?php if(isset($_SESSION['account'])&&$_SESSION['account']!=null): ?>
			<!-- Logged into account -->
			<a href="<?php echo URL; ?>account" class="w3-bar-item w3-button w3-hide-small w3-right w3-padding-large w3-hover-white" title="My Account">
				<?php if(file_exists('public/accounts/'.$_SESSION['account'].'.png')): ?>
					<img src="public/accounts/<?php echo $_SESSION['account']; ?>.png" class="w3-circle" style="height:23px;width:23px">
				<?php else: ?>
					<img src="public/img/account.png" class="w3-circle" style="height:23px;width:23px">
				<?php endif; ?>
			</a>
		<?php else: ?>
			<!-- Not logged in -->
			<a href="./login" class="w3-bar-item w3-button w3-hide-small w3-right w3-padding-large w3-hover-white" title="Account">Log in</a>
		<?php endif; ?>
	</div>
</div>

<!-- TODO small navbar, example from w3.css -->
<div id="navSmall" class="w3-bar-block w3-theme-d2 w3-hide w3-hide-large w3-hide-medium w3-large">
	<a href="#" class="w3-bar-item w3-button w3-padding-large">Link 1</a>
	<a href="#" class="w3-bar-item w3-button w3-padding-large">Link 2</a>
	<a href="#" class="w3-bar-item w3-button w3-padding-large">Link 3</a>
	<a href="#" class="w3-bar-item w3-button w3-padding-large">My Profile</a>
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