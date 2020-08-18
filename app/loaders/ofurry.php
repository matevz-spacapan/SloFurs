<?php
class oFurry extends Connection{
	public function index(){
		$account=$this->getSessionAcc();
		require 'app/sites/global/header.php';
		require 'app/sites/global/oFurryjih.php';
		echo "<script>document.title='".L::title_aboutfurries."';</script>";
		require 'app/sites/global/footer.php';
	}
}
