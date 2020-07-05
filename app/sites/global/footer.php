<footer class="footer m-5 text-center">
  <i class="far fa-code"></i> with <i class="fas fa-heart"></i> in Slovenia<br>
  Powered by <a href="https://www.w3schools.com/w3css/default.asp" target="_blank">w3.css <i class="far fa-external-link"></i></a> & <a href="https://getbootstrap.com/" target="_blank">Bootstrap <i class="far fa-external-link"></i></a><br>Designed by <a href="https://twitter.com/Pur3Bolt" target="_blank">Pur3Bolt <i class="far fa-external-link"></i></a><br>
  <i class="far fa-copyright"></i> SloFurs 2019-<?php echo date("Y"); ?>
</footer>
<?php
if($account==null){
  require 'app/sites/2019/login.php';
  require 'app/sites/2019/signup.php';
  require 'app/sites/2019/password_reset.php';
}
?>
<script async defer src="https://scripts.simpleanalyticscdn.com/latest.js"></script>
<noscript><img src="https://queue.simpleanalyticscdn.com/noscript.gif" alt=""/></noscript>
</body>
