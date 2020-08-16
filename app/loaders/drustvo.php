<?php
class Drustvo extends Connection{
	public function index(){
		$account=$this->getSessionAcc();
		require 'app/sites/global/header.php';
		require 'app/sites/global/drustvo.php';
		require 'app/sites/global/footer.php';
	}
}
