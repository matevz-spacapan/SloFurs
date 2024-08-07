<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<title><?php echo isset($title)?$title:'SloFurs'; ?></title>
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- w3.css stylesheet -->
	<link href="https://www.w3schools.com/w3css/4/w3.css" rel="stylesheet">
	<!-- Bootstrap -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
	<!-- jQuery -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
	<!-- Muli typefont -->
	<link href="https://fonts.googleapis.com/css?family=Muli&display=swap" rel="stylesheet">
	<!-- Custom styles -->
	<link href="<?php echo URL; ?>app/sites/global/custom.css" rel="stylesheet">
	<!-- FontAwesome -->
	<link href="<?php echo URL;?>public/fa/css/all.min.css?v=1" rel="stylesheet">
	<!-- Favicon -->
	<link href="<?php echo URL;?>public/img/slo.jpg" type="image/x-icon" rel="icon"/>
	<link href="<?php echo URL;?>public/img/slo.jpg" type="image/x-icon" rel="shortcut icon"/>
</head>
<body>
	<?php
		require 'app/sites/global/navigation.php';
		require 'app/sites/global/loading.php';
	?>
