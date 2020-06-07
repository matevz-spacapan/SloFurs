<div class="container-fluid mt-3">
<script src="https://cdn.ckeditor.com/ckeditor5/19.0.0/classic/ckeditor.js"></script>
<?php
  $id=(isset($_GET['id']))?filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT):null;
  require_once 'app/sites/'.THEME.'/admin/event_form/datetimepicker.html';
?>
<form action="<?php echo URL."admin/event"; if($id!=null){ echo "?id=$id"; } ?>" method="post" enctype="multipart/form-data" autocomplete="off" class="needs-validation allforms" novalidate>
<div id="formParent">
  <!-- General info about the event -->
  <div id="pg1" class="collapse show" data-parent="#formParent">
    <h3><?php echo L::admin_form_event_h;?></h3>
    <div class="form-row">
  		<!-- Title -->
  		<div class="col-md-2 col-sm-12">
  			<div class="form-group">
          <label for="name"><?php echo L::admin_form_event_name;?></label> <sup class="text-danger">*</sup>
          <input type="text" class="form-control mb-2" name="name" required value="<?php if(isset($id)){echo $event->name;} ?>">
  			</div>
  		</div>
  		<!-- Location -->
  		<div class="col-md-2 col-sm-12">
  			<div class="form-group">
          <label for="location"><?php echo L::admin_form_event_location;?></label>
          <a href="#" data-toggle="popover" title="<?php echo L::admin_form_event_popoverTitle;?>" data-content="<?php echo L::admin_form_event_locationInfo;?>"><i class="far fa-question-circle"></i></a>
          <input type="text" class="form-control" name="location" value="<?php if(isset($id)){echo $event->location;} ?>">
  			</div>
  		</div>
  		<!-- Additional navigation instructions -->
  		<div class="col-md-2 col-sm-12">
  			<div class="form-group">
          <label for="navigation"><?php echo L::admin_form_event_navigation;?></label>
          <a href="#" data-toggle="popover" title="<?php echo L::admin_form_event_popoverTitle;?>" data-content="<?php echo L::admin_form_event_navigationInfo;?>"><i class="far fa-question-circle"></i></a>
          <select id="navigation" name="navigation" class="custom-select">
          	<option value=""><?php echo L::admin_form_event_noNavigation;?></option>
          	<option value="gabrje">Gabrje</option>
          </select>
  			</div>
  		</div>
  		<!-- Photo gallery -->
  		<div class="col-md-2 col-sm-12">
  			<div class="form-group">
          <label for="gallery"><?php echo L::admin_form_event_gallery;?></label>
          <a href="#" data-toggle="popover" title="<?php echo L::admin_form_event_popoverTitle;?>" data-content="<?php echo L::admin_form_event_galleryInfo;?>"><i class="far fa-question-circle"></i></a>
          <input type="text" class="form-control" name="gallery" value="<?php if(isset($id)){echo $event->gallery;} ?>">
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
          <label for="start"><?php echo L::admin_form_event_start;?></label> <sup class="text-danger">*</sup>
          <a href="#" data-toggle="popover" title="<?php echo L::admin_form_event_popoverTitle;?>" data-content="<?php echo L::admin_form_event_startInfo;?>"><i class="far fa-question-circle"></i></a>
          <input type="text" name="start" class="form-control datetimepicker-input" id="dtp1" data-toggle="datetimepicker" data-target="#dtp1" required/>
  			</div>
  		</div>
  		<!-- Event end -->
  		<div class="col">
  			<div class="form-group">
          <label for="end"><?php echo L::admin_form_event_end;?></label> <sup class="text-danger">*</sup>
          <a href="#" data-toggle="popover" title="<?php echo L::admin_form_event_popoverTitle;?>" data-content="<?php echo L::admin_form_event_endInfo;?>"><i class="far fa-question-circle"></i></a>
          <input type="text" name="end" class="form-control datetimepicker-input" id="dtp2" data-toggle="datetimepicker" data-target="#dtp2" required/>
  			</div>
  		</div>
  	</div>
    <!-- Description (Slovenian) -->
    <label for="description"><?php echo L::admin_form_event_description;?></label>
    <a href="#" data-toggle="popover" title="<?php echo L::admin_form_event_popoverTitle;?>" data-content="<?php echo L::admin_form_event_descriptionInfo;?>"><i class="far fa-question-circle"></i></a>
    <textarea name="description" id="editor"><?php if(isset($id)){echo $event->description;} ?></textarea>
    <!-- Description (English) -->
    <label for="descriptionEn" class="mt-3"><?php echo L::admin_form_event_descriptionEn;?></label>
    <textarea name="descriptionEn" id="editorEn"><?php if(isset($id)){echo $event->description_en;} ?></textarea>
  </div>

  <!-- Registration dates and times -->
  <div id="pg2" class="collapse" data-parent="#formParent">
    <h3><?php echo L::admin_form_registration_h;?></h3>

    <div class="form-row">
  		<!-- Public visibility -->
  		<div class="col-md-3 col-sm-12">
  			<div class="form-group">
          <label for="viewable"><?php echo L::admin_form_registration_visibility;?></label>
          <a href="#" data-toggle="popover" title="<?php echo L::admin_form_event_popoverTitle;?>" data-content="<?php echo L::admin_form_registration_visibilityInfo;?>"><i class="far fa-question-circle"></i></a>
          <input type="text" name="viewable" class="form-control datetimepicker-input" id="dtp3" data-toggle="datetimepicker" data-target="#dtp3" required/>
  			</div>
  		</div>
  		<!-- Pre-reg -->
  		<div class="col-md-3 col-sm-12">
  			<div class="form-group">
          <label for="pre_reg"><?php echo L::admin_form_registration_pre;?></label>
          <a href="#" data-toggle="popover" title="<?php echo L::admin_form_event_popoverTitle;?>" data-content="<?php echo L::admin_form_registration_preInfo;?>"><i class="far fa-question-circle"></i></a>
          <input type="text" name="pre_reg" class="form-control datetimepicker-input" id="dtp4" data-toggle="datetimepicker" data-target="#dtp4" required/>
  			</div>
  		</div>
  		<!-- Regular reg -->
  		<div class="col-md-3 col-sm-12">
  			<div class="form-group">
          <label for="reg_start"><?php echo L::admin_form_registration_start;?></label> <sup class="text-danger">*</sup>
          <a href="#" data-toggle="popover" title="<?php echo L::admin_form_event_popoverTitle;?>" data-content="<?php echo L::admin_form_registration_startInfo;?>"><i class="far fa-question-circle"></i></a>
          <input type="text" name="reg_start" class="form-control datetimepicker-input" id="dtp5" data-toggle="datetimepicker" data-target="#dtp5" required/>
  			</div>
  		</div>
  		<!-- End of reg -->
  		<div class="col">
  			<div class="form-group">
          <label for="reg_end"><?php echo L::admin_form_registration_end;?></label>
          <a href="#" data-toggle="popover" title="<?php echo L::admin_form_event_popoverTitle;?>" data-content="<?php echo L::admin_form_registration_endInfo;?>"><i class="far fa-question-circle"></i></a>
          <input type="text" name="reg_end" class="form-control datetimepicker-input" id="dtp6" data-toggle="datetimepicker" data-target="#dtp6" required/>
        </div>
  		</div>
  	</div>
    <div class="custom-control custom-checkbox">
      <input class="custom-control-input" type="checkbox" name="autoconfirm" id="autoconfirm" value="1" <?php if($editEvent&&$event->autoconfirm==1){echo 'checked';} ?>>
      <label for="autoconfirm" class="custom-control-label"><?php echo L::admin_form_registration_auto;?></label>
      <a href="#" data-toggle="popover" title="<?php echo L::admin_form_event_popoverTitle;?>" data-content="<?php echo L::admin_form_registration_autoInfo;?>"><i class="far fa-question-circle"></i></a>
    </div>
  </div>

  <!-- Age restrictions -->
  <div id="pg3" class="collapse" data-parent="#formParent">
    <h3><?php echo L::admin_form_age_h;?></h3>

    <div class="form-row">
  		<!-- No restrictions age -->
  		<div class="col-md-2 col-sm-12">
  			<div class="form-group">
          <label for="age"><?php echo L::admin_form_age_noRestrict;?></label> <sup class="text-danger">*</sup>
          <a href="#" data-toggle="popover" title="<?php echo L::admin_form_event_popoverTitle;?>" data-content="<?php echo L::admin_form_age_noRestrictInfo;?>"><i class="far fa-question-circle"></i></a>
          <input type="number" class="form-control" name="age" value="<?php if($editEvent){echo $event->age;}else{echo 0;} ?>" min="0" max="99" required>
  			</div>
  		</div>
  		<!-- Restricted age -->
  		<div class="col-md-2 col-sm-12">
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
  </div>

  <!-- Ticket types -->
  <div id="pg4" class="collapse" data-parent="#formParent">
    <h3><?php echo L::admin_form_tickets_h;?></h3>
    <p class="text-secondary"><?php echo L::admin_form_tickets_hInfo;?></p>

    <div class="table-responsive">
      <table class="table table-striped table-hover thead-light">
        <tr>
          <th></th>
          <th><?php echo L::admin_form_tickets_type;?> <a href="#" data-toggle="popover" title="<?php echo L::admin_form_event_popoverTitle;?>" data-content="<?php echo L::admin_form_tickets_typeInfo;?>"><i class="far fa-question-circle"></i></a></th>
          <th><?php echo L::admin_form_tickets_cost;?></th>
          <th><?php echo L::admin_form_tickets_description;?> <small class="text-secondary"><?php echo L::admin_form_tickets_descriptionInfo;?></small></th>
        </tr>
        <tr>
          <td>
            <div class="custom-control custom-checkbox">
              <input class="custom-control-input" type="checkbox" id="checkfree" name="ticket" value="free" <?php if($editEvent&&$event->regular_price==0){echo 'checked';} ?>>
              <label for="checkfree" class="custom-control-label"></label>
            </div>
          </td>
          <td>
            <?php echo L::admin_form_tickets_free;?>
          </td>
          <td>0€</td>
          <td></td>
        </tr>
        <tr>
          <td>
            <div class="custom-control custom-checkbox">
              <input class="custom-control-input" type="checkbox" id="checkregular" name="ticket" value="regular" <?php if($editEvent&&$event->regular_price!=0&&$event->sponsor_price==-1){echo 'checked';} ?>>
              <label for="checkregular" class="custom-control-label"></label>
            </div>
          </td>
          <td><input type="text" class="form-control" name="regular_title" value="<?php if($editEvent){echo $event->regular_title;} ?>" placeholder="<?php echo L::admin_form_tickets_regular;?>"></td>
          <td><input type="number" class="form-control" id="regular" min="1" value="<?php if($editEvent&&$event->regular_price!=0){echo $event->regular_price;} ?>"></td>
          <td>
            <div id="regular_div">
              <textarea id="regular_text"><?php if($editEvent){echo $event->regular_text;} ?></textarea>
            </div>
          </td>
        </tr>
        <tr>
          <td>
            <div class="custom-control custom-checkbox">
              <input class="custom-control-input" type="checkbox" id="checksponsor" name="ticket" value="sponsor" <?php if($editEvent&&$event->sponsor_price!=-1&&$event->super_price==-1){echo 'checked';} ?>>
              <label for="checksponsor" class="custom-control-label"></label>
            </div>
          </td>
          <td><input type="text" class="form-control" name="sponsor_title" value="<?php if($editEvent){echo $event->sponsor_title;} ?>" placeholder="<?php echo L::admin_form_tickets_sponsor;?>"></td>
          <td><input type="number" class="form-control" id="sponsor" min="1" value="<?php if($editEvent&&$event->sponsor_price!=-1){echo $event->sponsor_price;} ?>"></td>
          <td>
            <div id="sponsor_div">
              <textarea id="sponsor_text"><?php if($editEvent){echo $event->sponsor_text;} ?></textarea>
            </div>
          </td>
        </tr>
        <tr>
          <td>
            <div class="custom-control custom-checkbox">
              <input class="custom-control-input" type="checkbox" id="checksuper" name="ticket" value="super" <?php if($editEvent&&$event->super_price!=-1){echo 'checked';} ?>>
              <label for="checksuper" class="custom-control-label"></label>
            </div>
          </td>
          <td><input type="text" class="form-control" name="super_title" value="<?php if($editEvent){echo $event->super_title;} ?>" placeholder="<?php echo L::admin_form_tickets_super;?>"></td>
          <td><input type="number" class="form-control" id="super" min="1" value="<?php if($editEvent&&$event->super_price!=-1){echo $event->super_price;} ?>"></td>
          <td>
            <div id="super_div">
              <textarea id="super_text"><?php if($editEvent){echo $event->super_text;} ?></textarea>
            </div>
          </td>
        </tr>
      </table>
    </div>
    <div class="custom-control custom-checkbox">
      <input class="custom-control-input" type="checkbox" id="prepay" name="pay_button" value="1" <?php if($editEvent&&$event->pay_button==1){echo 'checked';} ?>>
      <label for="prepay" class="custom-control-label"><?php echo L::admin_form_tickets_prepay;?> <a href="#" data-toggle="popover" title="<?php echo L::admin_form_event_popoverTitle;?>" data-content="<?php echo L::admin_form_tickets_prepayInfo;?>"><i class="far fa-question-circle"></i></a></label>
    </div>
  </div>

  <!-- Accomodation -->
  <div id="pg5" class="collapse" data-parent="#formParent">
    <h3 style="display: inline-block;"><?php echo L::admin_form_accomodation_h;?></h3> <a href="#" data-toggle="popover" title="<?php echo L::admin_form_event_popoverTitle;?>" data-content="<?php echo L::admin_form_accomodation_hInfo;?>"><i class="far fa-question-circle"></i></a>
    <?php if($editEvent): ?>
      <p class="text-danger"><?php echo L::admin_form_accomodation_warning;?></p>
    <?php endif; ?>

    <div class="container-fluid">
      <div class="table-responsive">
        <table class="table table-striped table-hover thead-light" id="accomodationTable">
          <tr>
            <th><?php echo L::admin_form_accomodation_type;?> <a href="#" data-toggle="popover" title="<?php echo L::admin_form_event_popoverTitle;?>" data-content="<?php echo L::admin_form_accomodation_typeInfo;?>"><i class="far fa-question-circle"></i></a></th>
            <th><?php echo L::admin_form_accomodation_persons;?></th>
            <th><?php echo L::admin_form_accomodation_price;?></th>
            <th><?php echo L::admin_form_accomodation_quantity;?> <a href="#" data-toggle="popover" title="<?php echo L::admin_form_event_popoverTitle;?>" data-content="<?php echo L::admin_form_accomodation_quantityInfo;?>"><i class="far fa-question-circle"></i></a></th>
            <th><button class="btn btn-success" type="button" onclick="addRow()">+</button></th>
          </tr>
          <?php
            if($editEvent){
              $rooms=$event_model->getRooms($event->id);
            }
          ?>
          <?php if($editEvent&&count($rooms)>0): ?>
            <?php foreach($rooms as $room): ?>
              <?php
                $booked=$event_model->getBooked($room->id);
                $booked=$booked->counter!=0;
              ?>
              <tr id="row<?php echo $room->id; ?>">
                <td><input type="text" class="form-control" name="type<?php echo $room->id; ?>" required value="<?php echo $room->type; ?>"></td>
                <td><input type="number" class="form-control" name="persons<?php echo $room->id; ?>" min="1" required value="<?php echo $room->persons; ?>" <?php if($booked){echo 'disabled';} ?>></td>
                <td><input type="number" class="form-control" min="0" name="price<?php echo $room->id; ?>" required value="<?php echo $room->price; ?>"></td>
                <td><input type="number" class="form-control" name="quantity<?php echo $room->id; ?>" min="1" required value="<?php echo $room->quantity; ?>"></td>
                <td><button class="btn btn-danger" type="button" onclick="removeRow('row<?php echo $room->id; ?>')" <?php if($booked){echo 'disabled';} ?>><b>-</b></button></td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </table>
      </div>
    </div>
  </div>
</div>



<div class="container-fluid p-5 text-center">
  <ul class="pagination justify-content-center">
    <li class="page-item active" id="link1" data-toggle="tooltip" data-placement="bottom" title="<?php echo L::admin_form_event_h;?>"><a class="page-link" href="#" data-toggle="collapse" data-target="#pg1" onclick="changeActive('1')">1</a></li>
    <li class="page-item" id="link2" data-toggle="tooltip" data-placement="bottom" title="<?php echo L::admin_form_registration_h;?>"><a class="page-link" href="#" data-toggle="collapse" data-target="#pg2" onclick="changeActive('2')">2</a></li>
    <li class="page-item" id="link3" data-toggle="tooltip" data-placement="bottom" title="<?php echo L::admin_form_age_h;?>"><a class="page-link" href="#" data-toggle="collapse" data-target="#pg3" onclick="changeActive('3')">3</a></li>
    <li class="page-item" id="link4" data-toggle="tooltip" data-placement="bottom" title="<?php echo L::admin_form_tickets_h;?>"><a class="page-link" href="#" data-toggle="collapse" data-target="#pg4" onclick="changeActive('4')">4</a></li>
    <li class="page-item" id="link5" data-toggle="tooltip" data-placement="bottom" title="<?php echo L::admin_form_accomodation_h;?>"><a class="page-link" href="#" data-toggle="collapse" data-target="#pg5" onclick="changeActive('5')">5</a></li>
  </ul>
  <?php if(!$editEvent): ?>
    <button type="submit" class="btn btn-success text-center" disabled>TRANSLATE Publish event</button>
    <p>Show btn only when all * is filled</p>
  <?php else: ?>
    <button type="submit" class="btn btn-success text-center" disabled>TRANSLATE Save changes</button>
  <?php endif; ?>
</div>

</form>

<script>
$(function () {
  $('[data-toggle="popover"]').popover();
  $('[data-toggle="tooltip"]').tooltip();
  $('#dtp1').datetimepicker();
  $('#dtp1').val('<?php if(isset($id)){echo $event_model->reformatForWeb($event->event_start);} ?>');
  $('#dtp2').datetimepicker();
  $('#dtp2').val('<?php if(isset($id)){echo $event_model->reformatForWeb($event->event_end);} ?>');
  $('#dtp3').datetimepicker();
  $('#dtp3').val('<?php if(isset($id)){echo $event_model->reformatForWeb($event->viewable);} ?>');
  $('#dtp4').datetimepicker();
  $('#dtp4').val('<?php if(isset($id)){echo $event_model->reformatForWeb($event->pre_reg_start);} ?>');
  $('#dtp5').datetimepicker();
  $('#dtp5').val('<?php if(isset($id)){echo $event_model->reformatForWeb($event->reg_start);} ?>');
  $('#dtp6').datetimepicker();
  $('#dtp6').val('<?php if(isset($id)){echo $event_model->reformatForWeb($event->reg_end);} ?>');
});
ClassicEditor
.create(document.querySelector('#editor'))
.catch(error=>{
  console.error(error);
});
ClassicEditor
.create(document.querySelector('#editorEn'))
.catch(error=>{
  console.error(error);
});
ClassicEditor
  .create(document.querySelector('#regular_text'))
  .catch(error=>{
    console.error(error);
  });
ClassicEditor
  .create(document.querySelector('#sponsor_text'))
  .catch(error=>{
    console.error(error);
  });
ClassicEditor
  .create(document.querySelector('#super_text'))
  .catch(error=>{
    console.error(error);
  });
$(".custom-file-input").on("change", function() {
  var fileName = $(this).val().split("\\").pop();
  $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
});
function changeActive(id){
  $(".pagination li").removeClass("active");
  $("#link"+id).addClass("active");
  // TODO: AJAX save data
}
var nr=1;
function addRow(){
	var row=`<tr id="row#x">
			<td><input type="text" class="form-control" name="type#x" required></td>
			<td><input type="number" class="form-control" name="persons#x" min="1" required></td>
			<td><input type="number" class="form-control" name="price#x" min="0" required></td>
			<td><input type="number" class="form-control" name="quantity#x" min="1" required></td>
			<td><button class="w3-button w3-red w3-round" onclick="removeRow('row#x')"><b>-</b></button></td>
		</tr>`;
	nr++;
	row=row.replace(/#/g, nr);
	$("#accomodationTable tr:last").after(row);
}
function removeRow(id){
	$("#"+id).remove();
}
$("#new_event").addClass("bg-warning");
$("#events_list").addClass("bg-light");
$('#dropdown').addClass("w3-show");
</script>
<?php require 'app/sites/global/validate_form.php'; ?>
