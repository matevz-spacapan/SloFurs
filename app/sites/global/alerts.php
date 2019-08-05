<?php if(isset($_SESSION['alert'])&&$_SESSION['alert']!=''): ?>
	<?php
	$alert_type="w3-blue";
	$alert_icon='<i class="far fa-info-square"></i>';
	if(substr($_SESSION['alert'], 0, 1)==="d"){
		$alert_type="w3-red";
		$alert_icon='<i class="far fa-exclamation-triangle"></i>';
	}
	elseif (substr($_SESSION['alert'], 0, 1)==="s"){
		$alert_type="w3-green";
		$alert_icon='<i class="far fa-laugh-beam"></i>';
	}
	?>
	<div id="alert-box" class="w3-panel w3-round <?php echo $alert_type ?> w3-display-bottomright w3-padding-16" style="z-index:99;">
		<?php echo $alert_icon; ?>
		<p style="display:inline;"> <?php echo substr($_SESSION['alert'], 1); ?></p>
	</div>
	<?php $_SESSION['alert']=''; ?>
	<script type="text/javascript">
		function showAlert(){
			$('#alert-box').animate({
				'opacity': 1,
				'right': '20px'
			}, 350);
			<?php if($_SESSION['permanent']==false): ?>
				setTimeout(hideAlert, 4500);
			<?php endif; ?>
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
<?php $_SESSION['permanent']=false;?>
