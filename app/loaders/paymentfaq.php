<?php
class PaymentFAQ extends Connection{
	public function index(){
		$account=$this->getSessionAcc();
		require 'app/sites/global/header.php';
		if(isset($_SESSION['lang'])){
			if($_SESSION['lang']=='si'){
				require 'app/sites/global/paymentFAQ_si.php';
			}
			else{
				require 'app/sites/global/paymentFAQ_en.php';
			}
		}
		else{
			require 'app/sites/global/paymentFAQ_si.php';
		}
		require 'app/sites/global/footer.php';
	}
}
