<?php if(isset($_SESSION['alert'])&&$_SESSION['alert']!=''): ?>
	<?php
	$alert_type="alert-info";
	$alert_icon='<i class="far fa-info-square"></i>';
	if(substr($_SESSION['alert'], 0, 1)==="d"){
		$alert_type="alert-danger";
		$alert_icon='<i class="far fa-exclamation-triangle"></i>';
	}
	elseif (substr($_SESSION['alert'], 0, 1)==="s"){
		$alert_type="alert-success";
		$alert_icon='<i class="far fa-laugh-beam"></i>';
	}
	?>
	<div class="alerts-box" style="z-index:99;">
		<div class="alerts-box-icon <?php echo $alert_type ?>">
			<?php echo $alert_icon; ?>
		</div>
		<div class="alerts-box-text bg-light">
			<span><?php echo substr($_SESSION['alert'], 1); ?></span>
		</div>
	</div>
	<?php $_SESSION['alert']=''; ?>
	<script type="text/javascript">
		function showAlert(){
			$('.alerts-box').animate({
				'opacity': 1,
				'right': '20px'
			}, 350);
			<?php if($_SESSION['permanent']==false): ?>
				setTimeout(hideAlert, 10000);
			<?php endif; ?>
		}
		function hideAlert(){
			$('.alerts-box').animate({
				'opacity': 0,
				'right': '-500px'
			}, 350);
		}
		showAlert();
	</script>
<?php endif; ?>
<?php $_SESSION['permanent']=false;?>
