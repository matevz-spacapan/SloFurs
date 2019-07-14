<?php
  class Ouch extends Connection{
    public function index(){
  		$account=$this->getSessionAcc();
      require 'app/sites/global/header.php';
      echo '
      <div class="w3-container w3-center" style="margin-top:20px">
        <h1>404</h1>
        <p>'.L::notFound.'</p>
        <img src="';
        echo URL.'public/img/this-is-fine.jpg" alt="Oops, 404." style="max-width: 500px;"><br>
      </div>';
      require 'app/sites/global/footer.php';
    }
  }
?>
