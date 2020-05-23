<script src="https://cdn.ckeditor.com/ckeditor5/18.0.0/classic/ckeditor.js"></script>
<form action="<?php echo URL; ?>admin/event<?php if($editEvent){echo '?id='.$event->id;} ?>" method="post" enctype="multipart/form-data" autocomplete="off" class="allforms">
  <!-- Event details -->
  <h3><?php echo L::admin_form_event_h;?></h3>

  <label for="name"><?php echo L::admin_form_event_name;?></label> <sup class="text-danger">*</sup>
  <input type="text" class="form-control" name="name" required value="<?php if($editEvent){echo $event->name;} ?>">

  <label for="start"><?php echo L::admin_form_event_start;?></label> <sup class="text-danger">*</sup> <i class="w3-opacity w3-small"><?php echo L::admin_form_event_startInfo;?></i>
  <input type="datetime-local" class="form-control" name="start" required value="<?php if($editEvent){echo $event_model->convert($event->event_start);} ?>">

  <label for="end"><?php echo L::admin_form_event_end;?></label> <sup class="text-danger">*</sup> <i class="w3-opacity w3-small"><?php echo L::admin_form_event_endInfo;?></i>
  <input type="datetime-local" class="form-control" name="end" required value="<?php if($editEvent){echo $event_model->convert($event->event_end);} ?>">

  <label for="location"><?php echo L::admin_form_event_location;?></label> <i class="w3-opacity w3-small"><?php echo L::admin_form_event_locationInfo;?></i>
  <input type="text" class="form-control" name="location" value="<?php if($editEvent){echo $event->location;} ?>">

  <label for="description"><?php echo L::admin_form_event_description;?></label> <i class="w3-opacity w3-small"><?php echo L::admin_form_event_descriptionInfo;?></i>
  <textarea name="description" id="editor"><?php if($editEvent){echo $event->description;} ?></textarea>
  <script>
    ClassicEditor
      .create(document.querySelector('#editor'))
      .catch(error=>{
        console.error(error);
      });
  </script>
  <p class="mt-4">English description</p>
  <textarea name="descriptionEn" id="editorEn"><?php if($editEvent){echo $event->description_en;} ?></textarea>
  <script>
    ClassicEditor
      .create(document.querySelector('#editorEn'))
      .catch(error=>{
        console.error(error);
      });
  </script>

  <p class="mt-4"><?php echo L::admin_form_event_photo;?></p>
  <div class="w3-display-container photoContainer">
    <?php
      $photo=URL.'public/events/head.png';
      if($editEvent&&$event->img!=null){
        $photo=URL.'public/events/'.$event->img.'.png';
      }
    ?>
    <img src="<?php echo $photo;?>" class="w3-round-large" style="height:158px;">
    <div class="w3-display-middle">
      <label for="file-upload" class="btn btn-light"><?php echo L::account_fursuit_addPhoto;?></label>
      <input id="file-upload" type="file" style="display:none" name="image" onchange="photo()">
      <i id="save" class="text-white"><?php echo L::account_fursuit_selectPhoto;?></i>
    </div>
  </div>
  <?php if($editEvent&&$event->img!=null):?>
    <div class="text-center">
      <br>
      <button type="submit" name="delete_photo" class="btn btn-danger"><?php echo L::admin_form_event_delete;?></button>
    </div>
  <?php endif;?>
  <!-- Registration details -->
  <h3><?php echo L::admin_form_registration_h;?></h3>

  <label for="viewable"><?php echo L::admin_form_registration_visibility;?></label> <i class="w3-opacity w3-small"><?php echo L::admin_form_registration_visibilityInfo;?></i>
  <input type="datetime-local" class="form-control" name="viewable" value="<?php if($editEvent){echo $event_model->convert($event->viewable);} ?>">

  <label for="pre_reg"><?php echo L::admin_form_registration_pre;?></label> <i class="w3-opacity w3-small"><?php echo L::admin_form_registration_preInfo;?></i>
  <input type="datetime-local" class="form-control" name="pre_reg" value="<?php if($editEvent){echo $event_model->convert($event->pre_reg_start);} ?>">

  <label for="reg_start"><?php echo L::admin_form_registration_start;?></label> <sup class="w3-text-red">*</sup> <i class="w3-opacity w3-small"><?php echo L::admin_form_registration_startInfo;?></i>
  <input type="datetime-local" class="form-control" name="reg_start" required value="<?php if($editEvent){echo $event_model->convert($event->reg_start);} ?>">

  <label for="reg_end"><?php echo L::admin_form_registration_end;?></label> <i class="w3-opacity w3-small"><?php echo L::admin_form_registration_endInfo;?></i>
  <input type="datetime-local" class="form-control" name="reg_end" value="<?php if($editEvent){echo $event_model->convert($event->reg_end);} ?>"><br>

  <input class="w3-check" type="checkbox" name="autoconfirm" value="1" <?php if($editEvent&&$event->autoconfirm==1){echo 'checked';} ?>>
  <label for="autoconfirm"><?php echo L::admin_form_registration_auto;?> <i class="w3-opacity w3-small"><?php echo L::admin_form_registration_autoInfo;?></i></label><br>

  <!-- Age restrictions -->
  <h3><?php echo L::admin_form_age_h;?></h3>

  <input class="w3-check" type="checkbox" id="age" onclick="displayAge()" <?php if($editEvent&&($event->age!=0||$event->restricted_age!=0)){echo 'checked';} ?>>
  <label></label><br><br>
  <div style="display: none;" id="ageSettings">
    <label for="age"><?php echo L::admin_form_age_noRestrict;?></label> <sup class="w3-text-red">*</sup> <i class="w3-opacity w3-small"><?php echo L::admin_form_age_noRestrictInfo;?></i>
    <input type="number" class="form-control" name="age" value="<?php if($editEvent){echo $event->age;}else{echo 0;} ?>" min="0" max="99" required>

    <label for="restricted_age"><?php echo L::admin_form_age_restrict;?></label> <i class="w3-opacity w3-small"><?php echo L::admin_form_age_restrictInfo;?></i>
    <input type="number" class="form-control" name="restricted_age" min="0" max="99" value="<?php if($editEvent){echo $event->restricted_age;}else{echo 0;} ?>">

    <label for="restricted_text"><?php echo L::admin_form_age_restrictText;?></label> <i class="w3-opacity w3-small"><?php echo L::admin_form_age_restrictTextInfo;?></i>
    <input type="text" class="form-control" name="restricted_text" value="<?php if($editEvent){echo $event->restricted_text;} ?>">
  </div>

  <!-- Ticket types -->
  <h3 style="display: inline;"><?php echo L::admin_form_tickets_h;?></h3> <i class="w3-opacity w3-small"><?php echo L::admin_form_tickets_hInfo;?></i><br><br>

  <div class="w3-responsive">
    <table class="w3-table">
      <tr>
        <th><?php echo L::admin_form_tickets_type;?></th>
        <th><?php echo L::admin_form_tickets_cost;?></th>
        <th><?php echo L::admin_form_tickets_description;?> <i class="w3-opacity w3-small"><?php echo L::admin_form_tickets_descriptionInfo;?></i></th>
      </tr>
      <tr>
        <td>
          <input class="w3-check" type="checkbox" id="checkfree" name="ticket" value="free" <?php if($editEvent&&$event->regular_price==0){echo 'checked';} ?>>
          <label for=""><?php echo L::admin_form_tickets_free;?></label>
        </td>
        <td>0</td>
        <td></td>
      </tr>
      <tr>
        <td>
          <input class="w3-check" type="checkbox" id="checkregular" name="ticket" value="regular" onclick="price('regular')" <?php if($editEvent&&$event->regular_price!=0&&$event->sponsor_price==-1){echo 'checked';} ?>>
          <!--<input type="text" class="form-control" id="regular_title" value="<?php if($editEvent){echo $event->regular_title;} ?>" placeholder="<?php echo L::admin_form_tickets_regular;?>" disabled>-->
          <label for=""><?php echo L::admin_form_tickets_regular;?></label>
        </td>
        <td><input type="number" class="form-control" id="regular" min="1" disabled value="<?php if($editEvent&&$event->regular_price!=0){echo $event->regular_price;} ?>"></td>
        <td>
          <div id="regular_div">
            <textarea id="regular_text"><?php if($editEvent){echo $event->regular_text;} ?></textarea>
          </div>
          <script>
            ClassicEditor
              .create(document.querySelector('#regular_text'))
              .catch(error=>{
                console.error(error);
              });
          </script>
        </td>
      </tr>
      <tr>
        <td>
          <input class="w3-check" type="checkbox" id="checksponsor" name="ticket" value="sponsor" onclick="price('sponsor')" <?php if($editEvent&&$event->sponsor_price!=-1&&$event->super_price==-1){echo 'checked';} ?>>
          <label for=""><?php echo L::admin_form_tickets_sponsor;?></label>
        </td>
        <td><input type="number" class="form-control" id="sponsor" min="1" disabled value="<?php if($editEvent&&$event->sponsor_price!=-1){echo $event->sponsor_price;} ?>"></td>
        <td>
          <div id="sponsor_div">
            <textarea id="sponsor_text"><?php if($editEvent){echo $event->sponsor_text;} ?></textarea>
          </div>
          <script>
            ClassicEditor
              .create(document.querySelector('#sponsor_text'))
              .catch(error=>{
                console.error(error);
              });
          </script>
        </td>
      </tr>
      <tr>
        <td>
          <input class="w3-check" type="checkbox" id="checksuper" name="ticket" value="super" onclick="price('super')" <?php if($editEvent&&$event->super_price!=-1){echo 'checked';} ?>>
          <label for=""><?php echo L::admin_form_tickets_super;?></label>
        </td>
        <td><input type="number" class="form-control" id="super" min="1" disabled value="<?php if($editEvent&&$event->super_price!=-1){echo $event->super_price;} ?>"></td>
        <td>
          <div id="super_div">
            <textarea id="super_text"><?php if($editEvent){echo $event->super_text;} ?></textarea>
          </div>
          <script>
            ClassicEditor
              .create(document.querySelector('#super_text'))
              .catch(error=>{
                console.error(error);
              });
          </script>
        </td>
      </tr>
    </table>
  </div>

  <!-- Accomodation -->
  <h3 style="display: inline;"><?php echo L::admin_form_accomodation_h;?></h3> <i class="w3-opacity w3-small"><?php echo L::admin_form_accomodation_hInfo;?></i><br><br>
  <?php if($editEvent): ?>
    <p class="w3-text-red"><?php echo L::admin_form_accomodation_warning;?></p>
  <?php endif; ?>

  <div class="w3-responsive">
    <table class="w3-table w3-responsive" id="accomodationTable">
      <tr>
        <th><?php echo L::admin_form_accomodation_type;?> <i class="w3-opacity w3-small"><?php echo L::admin_form_accomodation_typeInfo;?></i></th>
        <th><?php echo L::admin_form_accomodation_persons;?></th>
        <th><?php echo L::admin_form_accomodation_price;?></th>
        <th><?php echo L::admin_form_accomodation_quantity;?></th>
        <th><button class="w3-button w3-green w3-round" onclick="addRow()">+</button></th>
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
      			<td><button class="w3-button w3-red w3-round" onclick="removeRow('row<?php echo $room->id; ?>')" <?php if($booked){echo 'disabled';} ?>><b>-</b></button></td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </table>
  </div>
  <?php if($account->status>=ADMIN): ?>
    <div class="w3-center">
      <p><?php echo L::admin_form_hint;?></p>
      <?php if(!$editEvent): ?>
        <button type="submit" id="submitBtn" name="new_event" class="w3-button w3-green w3-round" disabled><?php echo L::admin_form_create;?></button>
      <?php else: ?>
        <button type="submit" id="submitBtn" name="edit_event" class="w3-button w3-green w3-round" disabled><?php echo L::admin_form_save;?></button>
      <?php endif; ?>
    </div>
  <?php endif;?>
</form>

<script>
function photo(){
	file=document.getElementById("file-upload").value.split(/(\\|\/)/g).pop();
	document.getElementById("save").innerHTML="<?php echo L::account_fursuit_file;?>: ".concat(file);
}
function price(type){
	if($("#check"+type).is(":checked")){
		$("#"+type).prop("disabled", false);
		$("#"+type+"_div").show();
		$("#"+type).prop("required", true);
		$("#"+type).prop("name", type+"_price");
		$("#"+type+"_text").prop("name", type+"_text");
	}
	else{
		$("#"+type).prop("disabled", true);
		$("#"+type+"_div").hide();
		$("#"+type).prop("required", false);
		$("#"+type).removeAttr("name");
		$("#"+type+"_text").removeAttr("name");
	}
}
function displayAge(){
	if($("#age").is(":checked")){
		$("#ageSettings").show();
		$("#ageSettings").addClass("scale-in-center");
	}
	else{
		$("#ageSettings").hide();
	}
}
var nr=1;
function addRow(){
	var row=`<tr id="row#*">
			<td><input type="text" class="form-control" name="type#*" required></td>
			<td><input type="number" class="form-control" name="persons#*" min="1" required></td>
			<td><input type="number" class="form-control" name="price#*" min="0" required></td>
			<td><input type="number" class="form-control" name="quantity#*" min="1" required></td>
			<td><button class="w3-button w3-red w3-round" onclick="removeRow('row#*')"><b>-</b></button></td>
		</tr>`;
	nr++;
	row=row.replace(/#/g, nr);
	$("#accomodationTable tr:last").after(row);
	validate();
}
function removeRow(id){
	$("#"+id).remove();
	validate();
}
$("#new_event").addClass("bg-warning");
$("#events_list").addClass("bg-light");
$('#dropdown').addClass("w3-show");

$(document).ready(function(){
	validate();
	$(document).on("keyup", "input", validate);
	$("input[type=checkbox][name='ticket']").on("change", validate);
	$("input[type=datetime-local]").on("change", validate);
  <?php if($account->status==STAFF): ?>
  $(".allforms :input").prop("disabled", true);
  <?php endif;?>
});
function validate(){
	var dateOK=true;
	var now=new Date();
  <?php if($editEvent): ?>
  now=new Date('00.00.0000');
  <?php endif; ?>
	//NOW<=PRE-REG<REG. START
	if(now>new Date($("input[name='pre_reg']").val())||new Date($("input[name='pre_reg']").val())>new Date($("input[name='reg_start']").val())){
		$("input[name='pre_reg']").addClass("w3-border w3-border-red w3-round");
		dateOK=false;
	}
	else{
		$("input[name='pre_reg']").removeClass("w3-border w3-border-red w3-round");
	}
	//NOW<=REG. START<START
	if(now>new Date($("input[name='reg_start']").val())||new Date($("input[name='reg_start']").val())>=new Date($("input[name='start']").val())){
		$("input[name='reg_start']").addClass("w3-border w3-border-red w3-round");
		dateOK=false;
	}
	else{
		$("input[name='reg_start']").removeClass("w3-border w3-border-red w3-round");
	}
	//REG. START<REG. END<=START
	if($("input[name='reg_end']").val()!=""&&(new Date($("input[name='reg_start']").val())>=new Date($("input[name='reg_end']").val())||new Date($("input[name='reg_end']").val())>new Date($("input[name='start']").val()))){
		$("input[name='reg_end']").addClass("w3-border w3-border-red w3-round");
		dateOK=false;
	}
	else{
		$("input[name='reg_end']").removeClass("w3-border w3-border-red w3-round");
	}
	//NOW<START
	if(now>new Date($("input[name='start']").val())){
		$("input[name='start']").addClass("w3-border w3-border-red w3-round");
		dateOK=false;
	}
	else{
		$("input[name='start']").removeClass("w3-border w3-border-red w3-round");
	}
	//END>START
	if(new Date($("input[name='start']").val())>=new Date($("input[name='end']").val())){
		$("input[name='end']").addClass("w3-border w3-border-red w3-round");
		dateOK=false;
	}
	else{
		$("input[name='end']").removeClass("w3-border w3-border-red w3-round");
	}
	//count required input fields and if they have data
	var inputsWVal=0;
	var requiredInputs=0;
	var myInputs=$("input:not([type='submit'])");
	myInputs.each(function(e){
		if($(this).prop("required")){
			requiredInputs++;
			if($(this).val()){
				inputsWVal++;
			}
		}
	});
  price("super");
	if($("#checksuper").is(":checked")){
		$("#checksponsor").prop("checked", true);
	}
  price("sponsor");
	if($("#checksponsor").is(":checked")){
		$("#checkregular").prop("checked", true);
	}
  price("regular");
	if($("#checkregular").is(":checked")){
		$("#checkfree").prop("checked", false);
	}

	//check if required and inputed equals (all required filled) and if at least one price category is selected
	if(inputsWVal==requiredInputs&&dateOK&&$("input[type=checkbox][name='ticket']:checked").length>0){
		$("#submitBtn").prop("disabled", false);
	}
	else{
		$("#submitBtn").prop("disabled", true);
	}
}
<?php if($editEvent): ?>
price("super");
price("sponsor");
price("regular");
displayAge();
<?php endif; ?>
</script>
