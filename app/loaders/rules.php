<?php
class Rules extends Connection{
	public function index(){
		$account=$this->getSessionAcc();
		require 'app/sites/global/header.php';
		if(isset($_SESSION['lang'])){
			if($_SESSION['lang']=='si'){
				require 'app/sites/global/pravila.html';
			}
			else{
				require 'app/sites/global/rules.html';
			}
		}
		else{
			require 'app/sites/global/pravila.html';
		}
		require 'app/sites/global/footer.php';
	}
}
