<?php
class Home extends Connection{
	public function index(){
		require 'app/sites/global/header.php';
		require 'app/sites/global/alerts.php';
		require 'app/sites/'.THEME.'/home.php';
		require 'app/sites/global/footer.php';
	}
}