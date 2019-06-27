<div class="w3-main" style="margin-left:200px">
<div class="w3-blue">
	<button class="w3-button w3-blue w3-xlarge w3-hide-large" onclick="side_open()">&#9776;</button>
	<div class="w3-container">
		<h1>Fursuit corner</h1>
	</div>
</div>
<div class="w3-container">
	<p>Currently, you have <?php echo count($fursuits).' '; echo (count($fursuits) > 0 ? (count($fursuits) > 1 ? 'fursuits' : 'fursuit') : 'fursuits'); ?> on record.</p>
	<!-- New fursuit -->
	<button class="w3-button w3-border-blue w3-border w3-round" onclick="editFursuit(0)">Add new</button>
	<div id="fursuit0" class="w3-modal">
		<div class="w3-modal-content w3-card-4" style="max-width:600px">
			<header class="w3-container w3-blue w3-center"> 
				<span onclick="document.getElementById('fursuit0').style.display='none'" 
				class="w3-button w3-display-topright">&times;</span>
				<h2>Add a new fursuit</h2>
			</header>
			<div class="w3-container">
				<form action="<?php echo URL; ?>account/fursuit/0" method="post" enctype="multipart/form-data">
					<label>Fursuit name</label>
					<input type="text" class="w3-input" name="suitname" required>
					<label>Animal</label>
					<input type="text" class="w3-input" name="animal" required><p>
					<label>Fursuit photo</label> <i class="w3-opacity w3-small">You must upload a photo of your fursuit. The photo must be square shaped.</i>
					<div class="w3-display-container photoContainer">
						<img src="<?php echo URL.'public/img/account.png'; ?>" class="w3-round-large" style="width:100%">
						<div class="w3-display-middle">
							<label for="file-upload0" class="w3-button w3-round w3-border w3-border-blue w3-white">Add photo</label>
							<input id="file-upload0" type="file" style="display:none" name="image" onchange="pfp(0)">
							<i id="save0">Select a photo to upload.</i>
						</div>
					</div>
					<div class="w3-center">
						<p>
						<button type="submit" id="submit0" class="w3-button w3-green w3-round" disabled>Save changes</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<br><br>

	<div class="w3-row">
		<?php if(count($fursuits) > 0): ?>
			<?php foreach($fursuits as $fursuit): ?>
				<!-- On the list -->
				<div class="card" onclick="editFursuit('<?php echo $fursuit->id; ?>')">
					<?php if(file_exists('public/fursuits/'.$fursuit->img.'.png')): ?>
						<img src="<?php echo URL.'public/fursuits/'.$fursuit->img; ?>.png" class="roundImg">
					<?php else: ?>
						<img src="<?php echo URL.'public/img/account.png' ?>" class="roundImg">
					<?php endif; ?>
					<p class="w3-center"><?php echo $fursuit->name; ?></p>
				</div>
				<!-- Pop-up modal editor -->
				<div id="fursuit<?php echo $fursuit->id; ?>" class="w3-modal">
					<div class="w3-modal-content w3-card-4" style="max-width:600px">
						<header class="w3-container w3-blue w3-center"> 
							<span onclick="document.getElementById('fursuit<?php echo $fursuit->id; ?>').style.display='none'" 
							class="w3-button w3-display-topright">&times;</span>
							<h2><?php echo $fursuit->name; ?></h2>
						</header>
						<div class="w3-container">
							<form action="<?php echo URL; ?>account/fursuit/<?php echo $fursuit->id; ?>" method="post" enctype="multipart/form-data">
								<label>Fursuit name</label>
								<input type="text" class="w3-input" name="suitname" value="<?php echo $fursuit->name; ?>" required>
								<label>Animal</label>
								<input type="text" class="w3-input" name="animal" value="<?php echo $fursuit->animal; ?>" required><p>
								<label>Fursuit photo</label> <i class="w3-opacity w3-small">The photo must be square shaped.</i>
								<div class="w3-display-container photoContainer">
									<?php if(file_exists('public/fursuits/'.$fursuit->img.'.png')): ?>
										<img src="<?php echo URL.'public/fursuits/'.$fursuit->img; ?>.png" class="w3-round-large" style="width:100%">
									<?php else: ?>
										<img src="<?php echo URL.'public/img/account.png' ?>" style="width:100%">
									<?php endif; ?>
									<div class="w3-display-middle w3-display-hover">
										<label for="file-upload<?php echo $fursuit->id; ?>" class="w3-button w3-round w3-border w3-border-blue w3-white">Change photo</label>
										<input id="file-upload<?php echo $fursuit->id; ?>" type="file" style="display:none" name="image" onchange="pfp('<?php echo $fursuit->id; ?>')">
										<div class="w3-container w3-white w3-opacity w3-round" id="save<?php echo $fursuit->id; ?>">To change, select a file.</div>
									</div>
								</div>
								<div class="w3-center">
									<p>
									<button type="submit" class="w3-button w3-green w3-round">Save changes</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>
</div>

<script>
function side_open() {
	document.getElementById("accSidebar").style.display="block";
}

function side_close() {
	document.getElementById("accSidebar").style.display="none";
}
function editFursuit(id){
	document.getElementById('fursuit'.concat(id)).style.display='block';
}
function pfp(id){
	file="file-upload".concat(id);
	file=document.getElementById(file).value.split(/(\\|\/)/g).pop();
	console.log(file);
	document.getElementById('save'.concat(id)).innerHTML="File: ".concat(file);
	if(id==0){
		document.getElementById("submit0").disabled=false;
	}
}
function onLoad(){
	document.getElementById("fursuit").classList.add("w3-blue");
}
onLoad();
</script>