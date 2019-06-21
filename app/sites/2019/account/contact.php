<div class="w3-main" style="margin-left:200px">
<div class="w3-blue">
  <button class="w3-button w3-blue w3-xlarge w3-hide-large" onclick="side_open()">&#9776;</button>
  <div class="w3-container">
    <h1>Account information</h1>
  </div>
</div>

<div class="w3-container">
<p>This is where users will be able to add/change info like email, address, profile picture...</p>
</div>

<script>
function side_open() {
  document.getElementById("mySidebar").style.display = "block";
}

function side_close() {
  document.getElementById("mySidebar").style.display = "none";
}
</script>