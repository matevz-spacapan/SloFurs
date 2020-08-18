<?php
class Home extends Connection{
	public function index(){
		$account=$this->getSessionAcc();
		require 'app/sites/global/header.php';
		require 'app/sites/global/alerts.php';
		require 'app/sites/'.THEME.'/home.php';
		echo "<script>document.title='".L::title_home."';</script>";
		require 'app/sites/global/footer.php';
	}
}
