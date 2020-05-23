<div class="w3-main" style="margin-left:300px">
<div class="container-fluid mt-3">
<?php
  $id=(isset($_GET['id']))?filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT):null;
  require_once 'app/sites/'.THEME.'/admin/event_form/datetimepicker.html';
?>
<form action="<?php echo URL."admin/event/$type/$step"; if($id!=null){ echo "?id=$id"; } ?>" method="post" enctype="multipart/form-data" autocomplete="off" class="needs-validation allforms" novalidate>
  <h3><?php echo L::admin_form_registration_h;?></h3>

  <div class="form-row">
		<!-- Public visibility -->
		<div class="col-md-3 col-sm-12">
			<div class="form-group">
        <label for="viewable"><?php echo L::admin_form_registration_visibility;?></label>
        <a href="#" data-toggle="popover" title="<?php echo L::admin_form_event_popoverTitle;?>" data-content="<?php echo L::admin_form_registration_visibilityInfo;?>"><i class="far fa-question-circle"></i></a>
        <input type="text" name="viewable" class="form-control datetimepicker-input" id="datetimepicker1" data-toggle="datetimepicker" data-target="#datetimepicker1" required/>
			</div>
		</div>
		<!-- Pre-reg -->
		<div class="col-md-3 col-sm-12">
			<div class="form-group">
        <label for="pre_reg"><?php echo L::admin_form_registration_pre;?></label>
        <a href="#" data-toggle="popover" title="<?php echo L::admin_form_event_popoverTitle;?>" data-content="<?php echo L::admin_form_registration_preInfo;?>"><i class="far fa-question-circle"></i></a>
        <input type="text" name="pre_reg" class="form-control datetimepicker-input" id="datetimepicker2" data-toggle="datetimepicker" data-target="#datetimepicker2" required/>
			</div>
		</div>
		<!-- Regular reg -->
		<div class="col-md-3 col-sm-12">
			<div class="form-group">
        <label for="reg_start"><?php echo L::admin_form_registration_start;?></label> <sup class="text-danger">*</sup>
        <a href="#" data-toggle="popover" title="<?php echo L::admin_form_event_popoverTitle;?>" data-content="<?php echo L::admin_form_registration_startInfo;?>"><i class="far fa-question-circle"></i></a>
        <input type="text" name="reg_start" class="form-control datetimepicker-input" id="datetimepicker3" data-toggle="datetimepicker" data-target="#datetimepicker3" required/>
			</div>
		</div>
		<!-- End of reg -->
		<div class="col">
			<div class="form-group">
        <label for="reg_end"><?php echo L::admin_form_registration_end;?></label>
        <a href="#" data-toggle="popover" title="<?php echo L::admin_form_event_popoverTitle;?>" data-content="<?php echo L::admin_form_registration_endInfo;?>"><i class="far fa-question-circle"></i></a>
        <input type="text" name="reg_end" class="form-control datetimepicker-input" id="datetimepicker4" data-toggle="datetimepicker" data-target="#datetimepicker4" required/>
      </div>
		</div>
	</div>
  <div class="custom-control custom-checkbox">
    <input class="custom-control-input" type="checkbox" name="autoconfirm" value="1" <?php if($editEvent&&$event->autoconfirm==1){echo 'checked';} ?>>
    <label for="autoconfirm" class="custom-control-label"><?php echo L::admin_form_registration_auto;?></label>
    <a href="#" data-toggle="popover" title="<?php echo L::admin_form_event_popoverTitle;?>" data-content="<?php echo L::admin_form_registration_autoInfo;?>"><i class="far fa-question-circle"></i></a>
  </div>
  <div class="mt-5 text-center">
    <div class="btn-group">
      <button type="submit" name="pg1" class="btn btn-outline-success">Previous</button>
      <button type="submit" name="pg1" class="btn btn-outline-success">1</button>
      <button type="button" class="btn btn-primary">2</button>
      <button type="submit" name="pg3" class="btn btn-outline-primary">3</button>
      <button type="submit" name="pg4" class="btn btn-outline-primary">4</button>
      <button type="submit" name="pg5" class="btn btn-outline-primary">5</button>
      <button type="submit" name="pg2" class="btn btn-outline-primary">Next</button>
    </div>
  </div>

</form>
<script>
$(function () {
  $('[data-toggle="popover"]').popover();
  $('#datetimepicker1').datetimepicker();
  $('#datetimepicker2').datetimepicker();
  $('#datetimepicker3').datetimepicker();
  $('#datetimepicker4').datetimepicker();
});
</script>
<?php require 'app/sites/global/validate_form.php'; ?>
