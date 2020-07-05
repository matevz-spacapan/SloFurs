<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<title><?php echo L::title; ?></title>
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- w3.css stylesheet -->
	<link href="https://www.w3schools.com/w3css/4/w3.css" rel="stylesheet">
	<!-- Bootstrap -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
	<!-- jQuery -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
	<!-- Muli typefont -->
	<link href="https://fonts.googleapis.com/css?family=Muli&display=swap" rel="stylesheet">
	<!-- Custom styles -->
	<link href="<?php echo URL; ?>app/sites/global/custom.css?rnd=<?=time();?>" rel="stylesheet">
	<!-- FontAwesome -->
	<link href="<?php echo URL;?>public/fa/css/all.min.css" rel="stylesheet">
	<!-- Favicon -->
	<link href="<?php echo URL;?>public/img/slo.jpg" type="image/x-icon" rel="icon"/>
	<link href="<?php echo URL;?>public/img/slo.jpg" type="image/x-icon" rel="shortcut icon"/>
</head>
<body>
	<?php
		require 'app/sites/global/navigation.php';
		require 'app/sites/global/loading.php';
	?>
