<?php require 'app/sites/'.THEME.'/admin/sidebar.php';?>

<div class="w3-main" style="margin-left:300px;">

  <header class="container-fluid" style="padding-top:22px">
    <h5><b><i class="fal fa-users-cog"></i> <?php echo L::admin_dash_h.': '.L::admin_sidebar_accounts;?></b></h5>
  </header>
  <div class="container-fluid row mb-2">
    <div class="col-3">
      <div class="d-flex p-4 mt-3">
        <div class="mr-auto">
          <i class="fal fa-paw fa-3x"></i>
          <h4><?php echo L::admin_sidebar_fursuits;?></h4>
        </div>
        <div class="row">
          <div class="cell text-center p-2">
            <h5><?php echo L::admin_dash_total;?></h5>
            <p><?php echo $dash_model->fursuitsB()->tot;?></p>
          </div>
        </div>
      </div>
      <?php if($account->status>=SUPER){echo '</a>';}?>
    </div>
  </div>

  <div class="container-fluid row ml-1">
		<?php if(count($fursuits) > 0): ?>
			<?php foreach($fursuits as $fursuit): ?>
				<!-- On the list -->
        <div class="card fursuit card-round mr-3 bg-light" data-toggle="modal" data-target="#fursuit<?php echo $fursuit->id; ?>">
					<?php if(file_exists('public/fursuits/'.$fursuit->img.'.png')): ?>
						<img src="<?php echo URL.'public/fursuits/'.$fursuit->img; ?>.png" class="roundImg">
					<?php else: ?>
						<img src="<?php echo URL.'public/img/account.png' ?>" class="roundImg">
					<?php endif; ?>
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
							<form action="<?php echo URL; ?>admin/fursuits/?id=<?php echo $fursuit->id; ?>" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
								<div class="modal-body">
									<div class="form-group">
										<label for="suitname"><?php echo L::account_fursuit_name;?></label>
										<input type="text" class="form-control" name="suitname" required value="<?php echo $fursuit->name; ?>">
									</div>
									<div class="form-group">
										<label for="animal"><?php echo L::account_fursuit_animal;?></label>
										<input type="text" class="form-control" name="animal" required value="<?php echo $fursuit->animal; ?>">
									</div>
									<div class="form-group">
										<label><?php echo L::account_fursuit_photo;?></label> <small class="text-muted"><?php echo L::account_fursuit_photoI;?></small>
										<div class="w3-display-container photoContainer">
											<img src="<?php echo URL.'public/fursuits/'.$fursuit->img; ?>.png" class="rounded" style="width:100%">
											<div class="w3-display-middle w3-display-hover">
												<label for="file-upload<?php echo $fursuit->id; ?>" class="btn btn-light"><?php echo L::account_fursuit_changePhoto;?></label>
												<input id="file-upload<?php echo $fursuit->id; ?>" type="file" style="display:none" name="image" onchange="pfp('<?php echo $fursuit->id; ?>')">
												<div class="w3-container w3-white w3-opacity w3-round" id="save<?php echo $fursuit->id; ?>"><?php echo L::account_fursuit_selectPhotoChange;?></div>
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
$("#fursuits").addClass("bg-warning");
</script>
