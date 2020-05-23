<div class="w3-main" style="margin-left:210px">
<div class="bg-primary text-white">
	<button class="btn btn-primary btn-lg w3-hide-large" onclick="$('#accSidebar').show()">&#9776;</button>
</div>
<div class="container-fluid">
	<p class="mt-4"><?php echo L::account_fursuit_currently1;?> <?php echo count($fursuits).' '; echo (count($fursuits) > 0 ? (count($fursuits) > 1 ? L::account_fursuit_fursuits : L::account_fursuit_fursuit) : L::account_fursuit_fursuits); ?> <?php echo L::account_fursuit_currently2;?></p>
	<?php
	/*if(count($fursuits)>0){
		echo L::account_fursuit_notice.'<p>';
	}*/
	?>

	<!-- NEW FURSUIT -->
	<button type="button" data-toggle="modal" data-target="#fursuit0" class="btn btn-outline-primary"><?php echo L::account_fursuit_new;?></button>

	<div class="modal fade" id="fursuit0">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title"><?php echo L::account_fursuit_newH;?></h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<form action="<?php echo URL; ?>account/fursuit" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
					<div class="modal-body">
						<div class="form-group">
							<label for="suitname"><?php echo L::account_fursuit_name;?></label>
							<input type="text" class="form-control" name="suitname" required>
						</div>
						<div class="form-group">
							<label for="animal"><?php echo L::account_fursuit_animal;?></label>
							<input type="text" class="form-control" name="animal" required>
						</div>
						<!--<div class="form-group">
							<div class="custom-control custom-checkbox">
								<input class="custom-control-input" type="checkbox" name="in_use" id="in_use">
								<label class="custom-control-label" for="in_use"><?php echo L::account_fursuit_use;?></label> <small class="text-muted"><?php echo L::account_fursuit_useI;?></small>
							</div>
						</div>-->
						<div class="form-group">
							<label><?php echo L::account_fursuit_photo;?></label> <small class="text-muted"><?php echo L::account_fursuit_photoI;?></small>
							<div class="w3-display-container photoContainer">
								<img src="<?php echo URL.'public/img/account.png'; ?>" class="rounded" style="width:100%">
								<div class="w3-display-middle">
									<label for="file-upload0" class="btn btn-light"><?php echo L::account_fursuit_addPhoto;?></label>
									<input id="file-upload0" type="file" style="display:none" name="image" onchange="pfp(0)" required class="custom-file">
									<i id="save0"><?php echo L::account_fursuit_selectPhoto;?></i>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="submit" id="submit0" name="new_fursuit" class="btn btn-success"><?php echo L::account_fursuit_save;?></button>
						</form>
					</div>
				</form>
			</div>
		</div>
	</div>
	<br><br>

	<!-- CREATED FURSUITS -->
	<div class="row">
		<?php if(count($fursuits) > 0): ?>
			<?php foreach($fursuits as $fursuit): ?>
				<!-- On the list -->
				<div class="card fursuit card-round mr-3 bg-light" data-toggle="modal" data-target="#fursuit<?php echo $fursuit->id; ?>">
					<img src="<?php echo URL.'public/fursuits/'.$fursuit->img; ?>.png" class="roundImg">
					<p class="text-center pt-2"><b><?php /*if($fursuit->in_use==1){echo '<i class="far fa-id-card-alt fa-lg"></i> ';}*/ echo $fursuit->name; ?></b></p>
				</div>
				<!-- Pop-up modal editor -->
				<div class="modal fade" id="fursuit<?php echo $fursuit->id; ?>">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h4 class="modal-title"><?php echo $fursuit->name; ?></h4>
								<button type="button" class="close" data-dismiss="modal">&times;</button>
							</div>
							<form action="<?php echo URL; ?>account/fursuit/?id=<?php echo $fursuit->id; ?>" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
								<div class="modal-body">
									<div class="form-group">
										<label for="suitname"><?php echo L::account_fursuit_name;?></label>
										<input type="text" class="form-control" name="suitname" required value="<?php echo $fursuit->name; ?>">
									</div>
									<div class="form-group">
										<label for="animal"><?php echo L::account_fursuit_animal;?></label>
										<input type="text" class="form-control" name="animal" required value="<?php echo $fursuit->animal; ?>">
									</div>
									<!--<div class="form-group">
										<div class="custom-control custom-checkbox">
											<input class="custom-control-input" type="checkbox" name="in_use" id="in_use" <?php if($fursuit->in_use==1){echo 'checked';} ?>>
											<label class="custom-control-label" for="in_use"><?php echo L::account_fursuit_use;?></label> <small class="text-muted"><?php echo L::account_fursuit_useI;?></small>
										</div>
									</div>-->
									<div class="form-group">
										<label><?php echo L::account_fursuit_photo;?></label> <small class="text-muted"><?php echo L::account_fursuit_photoI;?></small>
										<div class="w3-display-container photoContainer">
											<img src="<?php echo URL.'public/fursuits/'.$fursuit->img; ?>.png" class="rounded" style="width:100%">
											<div class="w3-display-middle w3-display-hover">
												<label for="file-upload<?php echo $fursuit->id; ?>" class="btn btn-light"><?php echo L::account_fursuit_changePhoto;?></label>
												<input id="file-upload<?php echo $fursuit->id; ?>" type="file" style="display:none" name="image" onchange="pfp('<?php echo $fursuit->id; ?>')">
												<div class="container bg-light w3-opacity rounded" id="save<?php echo $fursuit->id; ?>"><?php echo L::account_fursuit_selectPhotoChange;?></div>
											</div>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type="submit" name="edit_fursuit" class="btn btn-success"><?php echo L::account_fursuit_save;?></button>
									<button type="button" class="btn btn-outline-danger" id="del<?php echo $fursuit->id; ?>" onclick="delFursuit('<?php echo $fursuit->id; ?>')"><?php echo L::account_fursuit_delete1;?></button>
									<button type="submit" name="delete_fursuit" id="delconf<?php echo $fursuit->id; ?>" class="btn btn-danger" style="display: none;"><?php echo L::account_fursuit_delete2;?></button>
									</form>
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
$("#fursuit").removeClass("text-body btn-link");
$("#fursuit").addClass("btn-primary");
</script>
<?php require 'app/sites/global/validate_form.php'; ?>
