<div class="w3-main" style="margin-left:300px">
<div class="container-fluid mt-3">
<script src="https://cdn.ckeditor.com/ckeditor5/12.3.1/classic/ckeditor.js"></script>
<?php
  $id=(isset($_GET['id']))?filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT):null;
  require_once 'app/sites/'.THEME.'/admin/event_form/datetimepicker.html';
?>
<form action="<?php echo URL."admin/event/$type/$step"; if($id!=null){ echo "?id=$id"; } ?>" method="post" enctype="multipart/form-data" autocomplete="off" class="needs-validation allforms" novalidate>
  <h3><?php echo L::admin_form_event_h;?></h3>

  <div class="form-row">
		<!-- Title -->
		<div class="col-md-4 col-sm-12">
			<div class="form-group">
        <label for="name"><?php echo L::admin_form_event_name;?></label> <sup class="w3-text-red">*</sup>
        <input type="text" class="form-control mb-2" name="name" required value="<?php if(isset($id)){echo $event->name;} ?>">
			</div>
		</div>
		<!-- Location -->
		<div class="col-md-4 col-sm-12">
			<div class="form-group">
        <label for="location"><?php echo L::admin_form_event_location;?></label>
        <a href="#" data-toggle="popover" title="<?php echo L::admin_form_event_popoverTitle;?>" data-content="<?php echo L::admin_form_event_locationInfo;?>"><i class="far fa-question-circle"></i></a>
        <input type="text" class="form-control" name="location" value="<?php if(isset($id)){echo $event->location;} ?>">
			</div>
		</div>
		<!-- Photo -->
		<div class="col">
			<div class="form-group">
        <p class="mb-2"><?php echo L::admin_form_event_photo;?> <a href="#" data-toggle="popover" title="<?php echo L::admin_form_event_popoverTitle;?>" data-content="<?php echo L::admin_form_event_photoInfo;?>"><i class="far fa-question-circle"></i></a></p>
        <div class="custom-file">
          <input type="file" class="custom-file-input" id="image" name="image">
          <label class="custom-file-label" for="image"><?php echo L::admin_form_event_photoSelect;?></label>
        </div>
			</div>
		</div>
	</div>
  <div class="form-row">
		<!-- Event start -->
		<div class="col-md-6 col-sm-12">
			<div class="form-group">
        <label for="start"><?php echo L::admin_form_event_start;?></label> <sup class="w3-text-red">*</sup>
        <a href="#" data-toggle="popover" title="<?php echo L::admin_form_event_popoverTitle;?>" data-content="<?php echo L::admin_form_event_startInfo;?>"><i class="far fa-question-circle"></i></a>
        <input type="text" name="start" class="form-control datetimepicker-input" id="datetimepicker1" data-toggle="datetimepicker" data-target="#datetimepicker1" required/>
			</div>
		</div>
		<!-- Event end -->
		<div class="col">
			<div class="form-group">
        <label for="end"><?php echo L::admin_form_event_end;?></label> <sup class="w3-text-red">*</sup>
        <a href="#" data-toggle="popover" title="<?php echo L::admin_form_event_popoverTitle;?>" data-content="<?php echo L::admin_form_event_endInfo;?>"><i class="far fa-question-circle"></i></a>
        <input type="text" name="end" class="form-control datetimepicker-input" id="datetimepicker2" data-toggle="datetimepicker" data-target="#datetimepicker2" required/>
			</div>
		</div>
	</div>

  <label for="description"><?php echo L::admin_form_event_description;?></label>
  <a href="#" data-toggle="popover" title="<?php echo L::admin_form_event_popoverTitle;?>" data-content="<?php echo L::admin_form_event_descriptionInfo;?>"><i class="far fa-question-circle"></i></a>
  <textarea name="description" id="editor"><?php if(isset($id)){echo $event->description;} ?></textarea>
  <div class="mt-5 text-center">
    <div class="btn-group">
      <button type="button" class="btn btn-outline-secondary" disabled>Previous</button>
      <button type="button" class="btn btn-primary" data-toggle="tooltip" title="<?php echo L::admin_form_event_h;?>">1</button>
      <button type="submit" name="pg2" class="btn btn-outline-primary" data-toggle="tooltip" title="<?php echo L::admin_form_registration_h;?>">2</button>
      <button type="submit" name="pg3" class="btn btn-outline-primary" data-toggle="tooltip" title="<?php echo L::admin_form_age_h;?>">3</button>
      <button type="submit" name="pg4" class="btn btn-outline-primary" data-toggle="tooltip" title="<?php echo L::admin_form_tickets_h;?>">4</button>
      <button type="submit" name="pg5" class="btn btn-outline-primary" data-toggle="tooltip" title="<?php echo L::admin_form_accomodation_h;?>">5</button>
      <button type="submit" name="pg2" class="btn btn-outline-primary">Next</button>
    </div>
  </div>
</form>

<script>
$(function () {
  $('[data-toggle="popover"]').popover();
  $('[data-toggle="tooltip"]').tooltip();
  $('#datetimepicker1').datetimepicker();
  $('#datetimepicker1').val('<?php if(isset($id)){echo $event_model->reformatForWeb($event->event_start);} ?>')
  $('#datetimepicker2').datetimepicker();
  $('#datetimepicker2').val('<?php if(isset($id)){echo $event_model->reformatForWeb($event->event_end);} ?>')
});
ClassicEditor
.create(document.querySelector('#editor'))
.catch(error=>{
  console.error(error);
});
$(".custom-file-input").on("change", function() {
  var fileName = $(this).val().split("\\").pop();
  $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
});
</script>
<?php require 'app/sites/global/validate_form.php'; ?>
