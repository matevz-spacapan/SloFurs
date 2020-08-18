<?php
class Drustvo extends Connection{
	public function index(){
		$account=$this->getSessionAcc();
		require 'app/sites/global/header.php';
		require 'app/sites/global/drustvo.php';
		echo "<script>document.title='".L::title_drustvo."';</script>";
		require 'app/sites/global/footer.php';
	}
}
