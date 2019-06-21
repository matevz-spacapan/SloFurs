<?php if(isset($_SESSION['alert'])&&$_SESSION['alert']!=''): ?>
	<?php
	$alert_type="w3-blue";
	if(substr($_SESSION['alert'], 0, 1)==="d"){
		$alert_type="w3-red";
	}
	elseif (substr($_SESSION['alert'], 0, 1)==="s") {
		$alert_type="w3-green";
	}
	?>
	<div id="alert-box" class="w3-panel <?php echo $alert_type ?> w3-display-bottomright">
		<p><?php echo substr($_SESSION['alert'], 1); ?></p>
	</div>
	<?php $_SESSION['alert'] = ''; ?>
	<script type="text/javascript">
		function showAlert(){
			$('#alert-box').animate({
				'opacity': 1,
				'right': '20px'
			}, 350);
			setTimeout(hideAlert, 4500);
		}
		function hideAlert(){
			$('#alert-box').animate({
				'opacity': 0,
				'right': '-500px'
			}, 350);
		}
		showAlert();
	</script>
<?php endif; ?>