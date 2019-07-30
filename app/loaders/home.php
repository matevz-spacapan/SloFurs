<?php
class Home extends Connection{
	public function index(){
		$account=$this->getSessionAcc();
		if(!isset($_COOKIE['cookie_warn'])){
			$_SESSION['alert']=L::alerts_i_cookies1.'. <a href="'.URL.'privacy">'.L::alerts_i_cookies2.'.</a>';
			$_SESSION['permanent']=true;
			setcookie('cookie_warn', 'true', time()+(60*60*24*30), '/');
		}
		require 'app/sites/global/header.php';
		require 'app/sites/global/alerts.php';
		require 'app/sites/'.THEME.'/home.php';
		require 'app/sites/global/footer.php';
	}
}
