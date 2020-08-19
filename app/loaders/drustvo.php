<?php
class Drustvo extends Connection{
	public function index(){
		$account=$this->getSessionAcc();
		$title=L::title_drustvo;
		require 'app/sites/global/header.php';
		require 'app/sites/global/drustvo.php';
		require 'app/sites/global/footer.php';
	}
}
