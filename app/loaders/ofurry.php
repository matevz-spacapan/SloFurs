<?php
class oFurry extends Connection{
	public function index(){
		$account=$this->getSessionAcc();
		$title=L::title_aboutfurries;
		require 'app/sites/global/header.php';
		require 'app/sites/global/oFurryjih.php';
		require 'app/sites/global/footer.php';
	}
}
