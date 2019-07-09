<?php
class Privacy extends Connection{
	public function index(){
		$account=$this->getSessionAcc();
		require 'app/sites/global/header.php';
		require 'app/sites/global/alerts.php';
		if(isset($_GET['lang'])){
			if($_GET['lang']=='si'){
				require 'app/sites/global/politika_zasebnosti.html';
			}
			else{
				require 'app/sites/global/privacy_policy.html';
			}
		}
		else{
			require 'app/sites/global/privacy_policy.html';
		}
		require 'app/sites/global/footer.php';
	}
}
