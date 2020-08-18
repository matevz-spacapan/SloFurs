<?php
  class Ouch extends Connection{
    public function index(){
  		$account=$this->getSessionAcc();
      require 'app/sites/global/header.php';
      echo '
      <div class="container text-center" style="margin-top:20px">
        <h1>404</h1>
        <p>'.L::notFound.'</p>
        <img src="'.URL.'public/img/this-is-fine.jpg" style="max-width: 500px;"><br>
      </div>';
      echo "<script>document.title='".L::title_error."';</script>";
      require 'app/sites/global/footer.php';
    }
  }
?>
