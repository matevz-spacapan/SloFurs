<?php
class Privacy extends Connection{
	public function index(){
		$account=$this->getSessionAcc();
		require 'app/sites/global/header.php';
		if(isset($_SESSION['lang'])){
			if($_SESSION['lang']=='si'){
				require 'app/sites/global/politika_o_zasebnosti.html';
			}
			else{
				require 'app/sites/global/privacy_policy.html';
			}
		}
		else{
			require 'app/sites/global/politika_o_zasebnosti.html';
		}
		echo "<script>document.title='".L::title_privacy."';</script>";
		require 'app/sites/global/footer.php';
	}
}
