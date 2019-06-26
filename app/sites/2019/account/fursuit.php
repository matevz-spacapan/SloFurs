<div class="w3-main" style="margin-left:200px">
<div class="w3-blue">
	<button class="w3-button w3-blue w3-xlarge w3-hide-large" onclick="side_open()">&#9776;</button>
	<div class="w3-container">
		<h1>Fursuit corner</h1>
	</div>
</div>
<div class="w3-container">
	<p>Currently, you have <?php echo count($fursuits).' '; echo (count($fursuits) > 0 ? (count($fursuits) > 1 ? 'fursuits' : 'fursuit') : 'fursuits'); ?> on record.</p>
	<?php if(count($fursuits)==0): ?>
		<p>If you have a fursuit, make sure to add it below. Your first fursuit badge is completely free!</p>
	<?php else: ?>
		<p>You are free too add more, but please keep in mind that only one fursuit badge will be printed free of charge</p>
	<?php endif; ?>
	<div class="w3-row-padding">
			<div class="card" onclick="editFursuit(0)"> <!-- TODO add ID using PHP -->
				<img src="<?php echo URL.'public/fursuits/1.png'; ?>" class="roundImg">
				<p class="w3-center">Fursuit name goes here</p>
			</div>
			
		<div id="fursuit0" class="w3-modal">
			<div class="w3-modal-content w3-card-4" style="max-width:600px">
				<header class="w3-container w3-blue w3-center"> 
					<span onclick="document.getElementById('fursuit0').style.display='none'" 
					class="w3-button w3-display-topright">&times;</span>
					<h2>Fursuit name goes here</h2>
				</header>
				<div class="w3-container">
					<form action="<?php echo URL; ?>account/fursuit/ID_HERE" method="post" enctype="multipart/form-data" id="updatePFP">
						<label>Fursuit name</label>
						<input type="text" class="w3-input" name="suitname" value="Edgar">
						<label>Animal</label>
						<input type="text" class="w3-input" name="animal" value="Fox"><p>
						<div class="w3-display-container" style="max-height:200px; max-width:200px; margin: 0 auto;">
							<img src="<?php echo URL.'public/fursuits/1.png'; ?>" class="w3-round-large" style="width:100%">
							<div class="w3-display-middle w3-display-hover">
								<label for="file-upload" class="w3-button w3-round w3-border w3-border-blue w3-white">Change photo</label>
								<input id="file-upload" type="file" style="display:none" name="image"/>
							</div>
						</div>
						<!-- TODO add warning to save changes if picture is to be changed -->
						<div class="w3-center">
							<p>
							<button type="submit" class="w3-button w3-blue w3-round">Save changes</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
function side_open() {
	document.getElementById("accSidebar").style.display="block";
}

function side_close() {
	document.getElementById("accSidebar").style.display="none";
}
function editFursuit($id){
	document.getElementById('fursuit'.concat($id)).style.display='block';
}
// When the user clicks anywhere outside of the modal, close it
var modal = document.getElementById('fursuit0');
window.onclick = function(event) {
	if (event.target == modal) {
		modal.style.display = "none";
	}
}
function onLoad(){
	document.getElementById("fursuit").classList.add("w3-blue");
}
onLoad();
</script>