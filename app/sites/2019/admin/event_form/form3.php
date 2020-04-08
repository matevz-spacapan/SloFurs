<div class="w3-main" style="margin-left:300px">
<div class="container-fluid mt-3">
<?php
  $id=(isset($_GET['id']))?filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT):null;
?>
<form action="<?php echo URL."admin/event/$type/$step"; if($id!=null){ echo "?id=$id"; } ?>" method="post" enctype="multipart/form-data" autocomplete="off" class="needs-validation allforms" novalidate>
  <h3><?php echo L::admin_form_age_h;?></h3>

  <div class="form-row">
		<!-- No restrictions age -->
		<div class="col-md-4 col-sm-12">
			<div class="form-group">
        <label for="age"><?php echo L::admin_form_age_noRestrict;?></label> <sup class="w3-text-red">*</sup>
        <a href="#" data-toggle="popover" title="<?php echo L::admin_form_event_popoverTitle;?>" data-content="<?php echo L::admin_form_age_noRestrictInfo;?>"><i class="far fa-question-circle"></i></a>
        <input type="number" class="form-control" name="age" value="<?php if($editEvent){echo $event->age;}else{echo 0;} ?>" min="0" max="99" required>
			</div>
		</div>
		<!-- Restricted age -->
		<div class="col-md-4 col-sm-12">
			<div class="form-group">
        <label for="restricted_age"><?php echo L::admin_form_age_restrict;?></label>
        <a href="#" data-toggle="popover" title="<?php echo L::admin_form_event_popoverTitle;?>" data-content="<?php echo L::admin_form_age_restrictInfo;?>"><i class="far fa-question-circle"></i></a>
        <input type="number" class="form-control" name="restricted_age" min="0" max="99" value="<?php if($editEvent){echo $event->restricted_age;}else{echo 0;} ?>">
			</div>
		</div>
		<!-- Restrictions description -->
		<div class="col">
			<div class="form-group">
        <label for="restricted_text"><?php echo L::admin_form_age_restrictText;?></label>
        <a href="#" data-toggle="popover" title="<?php echo L::admin_form_event_popoverTitle;?>" data-content="<?php echo L::admin_form_age_restrictTextInfo;?>"><i class="far fa-question-circle"></i></a>
        <input type="text" class="form-control" name="restricted_text" value="<?php if($editEvent){echo $event->restricted_text;} ?>">
			</div>
		</div>
	</div>

  <div class="mt-5 text-center">
    <div class="btn-group">
      <button type="submit" name="pg2" class="btn btn-outline-success">Previous</button>
      <button type="submit" name="pg1" class="btn btn-outline-success">1</button>
      <button type="submit" name="pg2" class="btn btn-outline-danger">2</button>
      <button type="button" class="btn btn-primary">3</button>
      <button type="submit" name="pg4" class="btn btn-outline-primary">4</button>
      <button type="submit" name="pg5" class="btn btn-outline-primary">5</button>
      <button type="submit" name="pg4" class="btn btn-outline-primary">Next</button>
    </div>
  </div>
</form>
<script>
$(function () {
  $('[data-toggle="popover"]').popover();
});
</script>
<?php require 'app/sites/global/validate_form.php'; ?>
