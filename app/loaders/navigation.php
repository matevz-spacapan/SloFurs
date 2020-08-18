<?php
class Navigation extends Connection{
	public function index(){
		require 'app/sites/404.php';
		$ouch=new Ouch();
		$ouch->index();
	}
	public function gabrje(){
		$account=$this->getSessionAcc();
		require 'app/sites/global/header.php';
		require 'app/sites/global/alerts.php';
		require 'app/sites/'.THEME.'/gabrje.php';
		echo "<script>document.title='".L::title_navigation."';</script>";
		require 'app/sites/global/footer.php';
	}
}
