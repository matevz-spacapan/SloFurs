<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<title><?php echo L::title; ?></title>
	<meta name="description" content="">
	<?php if(isset($_COOKIE['analytics'])): ?>
		<!-- Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=UA-143816535-1"></script>
		<script>
		  window.dataLayer = window.dataLayer || [];
		  function gtag(){dataLayer.push(arguments);}
		  gtag('js', new Date());
		  gtag('config', 'UA-143816535-1');
		</script>
		<!-- Heatly -->
		<script>
			(function(d,v,s,h,t,m,a,p){d.heatmapObj=t;d[t]=d[t]||function()
			{(d[t].q=d[t].q||[]).push(arguments)},d[t].l=1*new Date();a=v.createElement(s),
			p=v.getElementsByTagName(s)[0];a.async=1;a.src=h;a.setAttribute('data-hmuid',m);p.parentNode.insertBefore(a,p)
			})(window,document,'script','https://app.heatlyanalytics.com/js/heatmap.js','hm','hm-29952gj')
		</script>
	<?php endif;?>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- w3.css stylesheet -->
	<link href="https://www.w3schools.com/w3css/4/w3.css" rel="stylesheet">
	<!-- Bootstrap -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	<!-- Muli typefont -->
	<link href="https://fonts.googleapis.com/css?family=Muli&display=swap" rel="stylesheet">
	<!-- Custom styles -->
	<link href="<?php echo URL; ?>app/sites/global/custom.css?rnd=<?=time();?>" rel="stylesheet">
	<!-- FontAwesome -->
	<script src="https://kit.fontawesome.com/925a2b5078.js"></script>
	<!-- jQuery -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<!-- Favicon -->
	<link href="<?php echo URL;?>public/img/slo.png" type="image/x-icon" rel="icon"/>
	<link href="<?php echo URL;?>public/img/slo.png" type="image/x-icon" rel="shortcut icon"/>
</head>
<body>
	<?php
		require 'app/sites/global/navigation.php';
		require 'app/sites/global/loading.php';
	?>
