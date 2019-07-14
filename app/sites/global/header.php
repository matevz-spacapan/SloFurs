<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<title>SloFurs Events</title>
	<meta name="description" content="">
	<!-- Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-143816535-1"></script>
	<script>
	  window.dataLayer = window.dataLayer || [];
	  function gtag(){dataLayer.push(arguments);}
	  gtag('js', new Date());
	  gtag('config', 'UA-143816535-1');
	</script>

	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- w3.css stylesheet -->
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<!-- Muli typefont -->
	<link href="https://fonts.googleapis.com/css?family=Muli&display=swap" rel="stylesheet">
	<!-- Custom styles -->
	<link rel="stylesheet" href="<?php echo URL; ?>app/sites/global/custom.css">
	<!-- FontAwesome -->
	<script src="https://kit.fontawesome.com/925a2b5078.js"></script>
	<!-- jQuery -->
	<script src="http://code.jquery.com/jquery-3.4.1.min.js"></script>
	<!-- Favicon -->
	<link rel="icon" href="<?php echo URL;?>public/img/favicon.png" type="image/x-icon"/>
	<link rel="shortcut icon" href="<?php echo URL;?>public/img/favicon.png" type="image/x-icon"/>
</head>
<body>
	<?php
		if(!isset($_SESSION['lang'])){
			$_SESSION['lang']='si';
		}
		require_once "public/spyc/spyc.php";
		require_once 'public/i18n/i18n.class.php';
		$i18n = new i18n('app/lang/lang_{LANGUAGE}.yml', 'public/i18n/langcache/');
		$i18n->init();
		require 'app/sites/global/topnavbar.php';
	?>
