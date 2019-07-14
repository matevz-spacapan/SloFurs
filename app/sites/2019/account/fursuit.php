<div class="w3-main" style="margin-left:200px">
<div class="w3-blue">
	<button class="w3-button w3-blue w3-xlarge w3-hide-large" onclick="$('#accSidebar').show();">&#9776;</button>
	<div class="w3-container">
		<h1><?php echo L::account_fursuit_h;?></h1>
	</div>
</div>
<div class="w3-container">
	<div>
		<?php echo L::account_fursuit_currently1;?> <?php echo count($fursuits).' '; echo (count($fursuits) > 0 ? (count($fursuits) > 1 ? L::account_fursuit_fursuits : L::account_fursuit_fursuit) : L::account_fursuit_fursuits); ?> <?php echo L::account_fursuit_currently2;?><p>
		<?php if(count($fursuits)>0): ?>
			<?php echo L::account_fursuit_notice;?><p>
		<?php endif; ?>
	</div>

	<!-- NEW FURSUIT -->
	<button class="w3-button w3-border-blue w3-border w3-round" onclick="$('#fursuit0').show()"><?php echo L::account_fursuit_new;?></button>
	<div id="fursuit0" class="w3-modal">
		<div class="w3-modal-content w3-card-4 w3-round-large" style="max-width:600px">
			<header class="w3-container w3-blue w3-center roundHeaderTop">
				<span onclick="$('#fursuit0').hide()"
				class="w3-button w3-display-topright roundXTop">&times;</span>
				<h2><?php echo L::account_fursuit_newH;?></h2>
			</header>
			<div class="w3-container">
				<form action="<?php echo URL; ?>account/fursuit" method="post" enctype="multipart/form-data">
					<label><?php echo L::account_fursuit_name;?></label>
					<input type="text" class="w3-input" name="suitname" required>
					<label><?php echo L::account_fursuit_animal;?></label>
					<input type="text" class="w3-input" name="animal" required><p>
					<input class="w3-check" type="checkbox" name="in_use">
					<label><?php echo L::account_fursuit_use;?></label> <i class="w3-opacity w3-small"><?php echo L::account_fursuit_useI;?></i><p>
					<label><?php echo L::account_fursuit_photo;?></label> <i class="w3-opacity w3-small"><?php echo L::account_fursuit_photoI;?></i>
					<div class="w3-display-container photoContainer">
						<img src="<?php echo URL.'public/img/account.png'; ?>" class="w3-round-large" style="width:100%">
						<div class="w3-display-middle">
							<label for="file-upload0" class="w3-button w3-round w3-border w3-border-blue w3-white"><?php echo L::account_fursuit_addPhoto;?></label>
							<input id="file-upload0" type="file" style="display:none" name="image" onchange="pfp(0)">
							<i id="save0"><?php echo L::account_fursuit_selectPhoto;?></i>
						</div>
					</div>
					<div class="w3-center">
						<p>
						<button type="submit" id="submit0" name="new_fursuit" class="w3-button w3-green w3-round" disabled><?php echo L::account_fursuit_save;?></button>
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
				<div class="card" onclick="$('#fursuit<?php echo $fursuit->id; ?>').show()">
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
							<form action="<?php echo URL; ?>account/fursuit/?id=<?php echo $fursuit->id; ?>" method="post" enctype="multipart/form-data">
								<label><?php echo L::account_fursuit_name;?></label>
								<input type="text" class="w3-input" name="suitname" value="<?php echo $fursuit->name; ?>" required>
								<label><?php echo L::account_fursuit_animal;?></label>
								<input type="text" class="w3-input" name="animal" value="<?php echo $fursuit->animal; ?>" required><p>
								<input class="w3-check" type="checkbox" name="in_use" <?php if($fursuit->in_use==1){echo 'checked';} ?>>
								<label><?php echo L::account_fursuit_use;?></label> <i class="w3-opacity w3-small"><?php echo L::account_fursuit_useI;?></i><p>
								<label><?php echo L::account_fursuit_photo;?></label> <i class="w3-opacity w3-small"><?php echo L::account_fursuit_useIEdit;?></i>
								<div class="w3-display-container photoContainer">
									<?php if(file_exists('public/fursuits/'.$fursuit->img.'.png')): ?>
										<img src="<?php echo URL.'public/fursuits/'.$fursuit->img; ?>.png" class="w3-round-large" style="width:100%">
									<?php else: ?>
										<img src="<?php echo URL.'public/img/account.png' ?>" style="width:100%">
									<?php endif; ?>
									<div class="w3-display-middle w3-display-hover">
										<label for="file-upload<?php echo $fursuit->id; ?>" class="w3-button w3-round w3-border w3-border-blue w3-white"><?php echo L::account_fursuit_changePhoto;?></label>
										<input id="file-upload<?php echo $fursuit->id; ?>" type="file" style="display:none" name="image" onchange="pfp('<?php echo $fursuit->id; ?>')">
										<div class="w3-container w3-white w3-opacity w3-round" id="save<?php echo $fursuit->id; ?>"><?php echo L::account_fursuit_selectPhotoChange;?></div>
									</div>
								</div>
								<div class="w3-center">
									<p>
									<button type="submit" name="edit_fursuit" class="w3-button w3-green w3-round"><?php echo L::account_fursuit_save;?></button>
									<button type="button" class="w3-button w3-red w3-round" id="del<?php echo $fursuit->id; ?>" onclick="delFursuit('<?php echo $fursuit->id; ?>')"><?php echo L::account_fursuit_delete1;?></button>
									<button type="submit" name="delete_fursuit" id="delconf<?php echo $fursuit->id; ?>" class="w3-button w3-red w3-round" style="display: none;"><?php echo L::account_fursuit_delete2;?></button>
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
	document.getElementById("save".concat(id)).innerHTML="<?php echo L::account_fursuit_file;?>: ".concat(file);
	if(id==0){
		document.getElementById("submit0").disabled=false;
	}
}
$("#fursuit").addClass("w3-blue");
</script>
