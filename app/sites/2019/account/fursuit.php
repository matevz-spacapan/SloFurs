<div class="w3-main" style="margin-left:200px">
<div class="w3-blue">
	<button class="w3-button w3-blue w3-xlarge w3-hide-large" onclick="side_open()">&#9776;</button>
	<div class="w3-container">
		<h1>Fursuit corner</h1>
	</div>
</div>
<div class="w3-container">
	<div>
		Currently, you have <?php echo count($fursuits).' '; echo (count($fursuits) > 0 ? (count($fursuits) > 1 ? 'fursuits' : 'fursuit') : 'fursuits'); ?> on record.<p>
		<?php if(count($fursuits)>0): ?>
			Fursuits with <i class="far fa-id-card-alt fa-lg"></i> next to their name will be printed for events (unless otherwise stated on the event).
			<i class="w3-opacity w3-small">Please note that only one such badge will be printed free of charge. Additional badges will be charged, the cost for this is posted on each event.</i><p>
		<?php endif; ?>
	</div>
	
	<!-- NEW FURSUIT -->
	<button class="w3-button w3-border-blue w3-border w3-round" onclick="editFursuit(0)">Add new</button>
	<div id="fursuit0" class="w3-modal">
		<div class="w3-modal-content w3-card-4 w3-round-large" style="max-width:600px">
			<header class="w3-container w3-blue w3-center roundHeaderTop"> 
				<span onclick="$('#fursuit0').hide()" 
				class="w3-button w3-display-topright roundXTop">&times;</span>
				<h2>Add a new fursuit</h2>
			</header>
			<div class="w3-container">
				<form action="<?php echo URL; ?>account/update/5" method="post" enctype="multipart/form-data">
					<label>Fursuit name</label>
					<input type="text" class="w3-input" name="suitname" required>
					<label>Animal</label>
					<input type="text" class="w3-input" name="animal" required><p>
					<input class="w3-check" type="checkbox" name="in_use">
					<label>Use as badge</label> <i class="w3-opacity w3-small">If checked, this fursuit will be printed for events as a badge.</i><p>
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

	<!-- CREATED FURSUITS, LOOPED -->
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
					<p class="w3-center"><?php if($fursuit->in_use==1){echo '<i class="far fa-id-card-alt fa-lg"></i> ';} echo $fursuit->name; ?></p>
				</div>
				<!-- Pop-up modal editor -->
				<div id="fursuit<?php echo $fursuit->id; ?>" class="w3-modal">
					<div class="w3-modal-content w3-card-4 w3-round-large" style="max-width:600px">
						<header class="w3-container w3-blue w3-center roundHeaderTop"> 
							<span onclick="$('#fursuit<?php echo $fursuit->id; ?>').hide()" 
							class="w3-button w3-display-topright roundXTop">&times;</span>
							<h2><?php echo $fursuit->name; ?></h2>
						</header>
						<div class="w3-container">
							<form action="<?php echo URL; ?>account/update/6/<?php echo $fursuit->id; ?>" method="post" enctype="multipart/form-data">
								<label>Fursuit name</label>
								<input type="text" class="w3-input" name="suitname" value="<?php echo $fursuit->name; ?>" required>
								<label>Animal</label>
								<input type="text" class="w3-input" name="animal" value="<?php echo $fursuit->animal; ?>" required><p>
								<input class="w3-check" type="checkbox" name="in_use" <?php if($fursuit->in_use==1){echo 'checked';} ?>>
								<label>Use as badge</label> <i class="w3-opacity w3-small">If checked, this fursuit will be printed for events as a badge.</i><p>
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
									<button type="submit" name="edit_fursuit" class="w3-button w3-green w3-round">Save changes</button>
									<button type="button" class="w3-button w3-red w3-round" id="del<?php echo $fursuit->id; ?>" onclick="delFursuit('<?php echo $fursuit->id; ?>')">Delete fursuit</button>
									<button type="submit" name="delete_fursuit" id="delconf<?php echo $fursuit->id; ?>" class="w3-button w3-red w3-round" style="display: none;">Are you sure?</button>
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
function side_open(){
	$("#accSidebar").show();
}
function side_close(){
	$("#accSidebar").hide();
}
function editFursuit(id){
	$("#fursuit"+id).show();
}
function delFursuit(id){
	$("#del"+id).addClass("scale-out-center");
	setTimeout(function(){
		contDel(id);
	}, 500);
}
function contDel(id){
	$("#del"+id).hide();
	$("#delconf"+id).css("display", "inline-block");
	$("#delconf"+id).addClass("scale-in-center");
	setTimeout(function(){
		$("#delconf"+id).removeClass("scale-in-center");
	}, 500);
}
function pfp(id){
	file="file-upload".concat(id);
	file=document.getElementById(file).value.split(/(\\|\/)/g).pop();
	document.getElementById("save".concat(id)).innerHTML="File: ".concat(file);
	if(id==0){
		document.getElementById("submit0").disabled=false;
	}
}
function onLoad(){
	$("#fursuit").addClass("w3-blue");
}
onLoad();
</script>