<div class="w3-col l7">
  <form action="<?php echo URL; ?>admin/event<?php if($editEvent){echo '?id='.$event->id;} ?>" method="post" autocomplete="off">
    <h3>Event details</h3>

    <label>Name</label> <sup class="w3-text-red">*</sup>
    <input type="text" class="w3-input" name="name" required value="<?php if($editEvent){echo $event->name;} ?>">

    <label>Start</label> <sup class="w3-text-red">*</sup> <i class="w3-opacity w3-small">when the event starts</i>
    <input type="datetime-local" class="w3-input" name="start" required value="<?php if($editEvent){echo $event_model->convert($event->event_start);} ?>">

    <label>End</label> <sup class="w3-text-red">*</sup> <i class="w3-opacity w3-small">when the event ends</i>
    <input type="datetime-local" class="w3-input" name="end" required value="<?php if($editEvent){echo $event_model->convert($event->event_end);} ?>">

    <label>Location</label>
    <input type="text" class="w3-input" name="location" value="<?php if($editEvent){echo $event->location;} ?>">

    <label>Description</label>
    <textarea class="w3-input" name="description"> <?php if($editEvent){echo $event->description;} ?></textarea><p>

    <h3>Registration details</h3>

    <label>Start</label> <sup class="w3-text-red">*</sup> <i class="w3-opacity w3-small">when attendees can register for the event</i>
    <input type="datetime-local" class="w3-input" name="reg_start" required value="<?php if($editEvent){echo $event_model->convert($event->reg_start);} ?>">

    <label>Pre-reg start</label> <i class="w3-opacity w3-small">optional. When staff can register before everyone else</i>
    <input type="datetime-local" class="w3-input" name="pre_reg" value="<?php if($editEvent){echo $event_model->convert($event->pre_reg_start);} ?>">

    <label>End</label> <i class="w3-opacity w3-small">when attendees can't register anymore. If left blank, then they can register until the event starts</i>
    <input type="datetime-local" class="w3-input" name="reg_end" value="<?php if($editEvent){echo $event_model->convert($event->reg_end);} ?>"><br>

    <input class="w3-check" type="checkbox" name="autoconfirm" value="1" <?php if($editEvent&&$event->autoconfirm==1){echo 'checked';} ?>>
    <label>Auto-confirm registrations <i class="w3-opacity w3-small">if checked, then all registrations for the event will be confirmed without staff's input</i></label><br>

    <h3>Age restrictions</h3>

    <input class="w3-check" type="checkbox" id="age" onclick="displayAge()" <?php if($editEvent&&($event->age!=0||$event->restricted_age!=0)){echo 'checked';} ?>>
    <label>Age restricted</label><br><br>
    <div style="display: none;" id="ageSettings">
      <label>Age for full duration</label> <sup class="w3-text-red">*</sup> <i class="w3-opacity w3-small">attendees need to be at least this old at the start of event to attend the entire event</i>
      <input type="number" class="w3-input" name="age" value="<?php if($editEvent){echo $event->age;}else{echo 0;} ?>" min="0" max="99" required>

      <label>Age with restricted access</label> <i class="w3-opacity w3-small">attendees need to be at least this old at the start of event to attend at least part of the event</i>
      <input type="number" class="w3-input" name="restricted_age" min="0" max="99" value="<?php if($editEvent){echo $event->restricted_age;}else{echo 0;} ?>">

      <label>Restrictions</label> <i class="w3-opacity w3-small">if the above field is filled, enter the restrictions that apply for this age group (eg. allowed until 8PM, allowed only on the weekend...)</i>
      <input type="text" class="w3-input" name="restricted_text" value="<?php if($editEvent){echo $event->restricted_text;} ?>">
    </div>

    <h3 style="display: inline;">Ticket types</h3> <i class="w3-opacity w3-small">at least one option must be selected. If you want to select free, then no other option may be selected.</i><br><br>

    <table class="w3-table">
      <tr>
        <th>Type</th>
        <th>Cost</th>
        <th>Additional description</th>
      </tr>
      <tr>
        <td>
          <input class="w3-check" type="checkbox" id="checkfree" name="ticket" value="free" <?php if($editEvent&&$event->regular_price==0){echo 'checked';} ?>>
          <label>Free</label>
        </td>
        <td>0</td>
        <td></td>
      </tr>
      <tr>
        <td>
          <input class="w3-check" type="checkbox" id="checkregular" name="ticket" value="regular" onclick="price('regular')" <?php if($editEvent&&$event->regular_price!=0&&$event->sponsor_price==-1){echo 'checked';} ?>>
          <label>Regular</label>
        </td>
        <td><input type="number" class="w3-input" id="regular" min="1" disabled value="<?php if($editEvent&&$event->regular_price!=0){echo $event->regular_price;} ?>"></td>
        <td><textarea class="w3-input" id="regular_text" disabled><?php if($editEvent){echo $event->regular_text;} ?></textarea></td>
      </tr>
      <tr>
        <td>
          <input class="w3-check" type="checkbox" id="checksponsor" name="ticket" value="sponsor" onclick="price('sponsor')" <?php if($editEvent&&$event->sponsor_price!=-1&&$event->super_price==-1){echo 'checked';} ?>>
          <label>Sponsor</label>
        </td>
        <td><input type="number" class="w3-input" id="sponsor" min="1" disabled value="<?php if($editEvent&&$event->sponsor_price!=-1){echo $event->sponsor_price;} ?>"></td>
        <td><textarea class="w3-input" id="sponsor_text" disabled><?php if($editEvent){echo $event->sponsor_text;} ?></textarea></td>
      </tr>
      <tr>
        <td>
          <input class="w3-check" type="checkbox" id="checksuper" name="ticket" value="super" onclick="price('super')" <?php if($editEvent&&$event->super_price!=-1){echo 'checked';} ?>>
          <label>Super-sponsor</label>
        </td>
        <td><input type="number" class="w3-input" id="super" min="1" disabled value="<?php if($editEvent&&$event->super_price!=-1){echo $event->super_price;} ?>"></td>
        <td><textarea class="w3-input" id="super_text" disabled><?php if($editEvent){echo $event->super_text;} ?></textarea></td>
      </tr>
    </table>

    <h3 style="display: inline;">Accomodation</h3> <i class="w3-opacity w3-small">if there is no accomodation for this event, don't add any rows below</i><br><br>
    <?php if($editEvent): ?>
      <p class="w3-text-red"><i class="far fa-exclamation-triangle"></i> <b>WARNING</b> <i class="far fa-exclamation-triangle"></i> Rooms can be removed or fully edited only before (pre-)registration starts. After this you can only add new rooms or edit the Type, Price and Quantity of existing rooms. <b>Double-check before saving and notify attendees of changes.</b></p>
      <h3 class="w3-red w3-center">ROOM EDITING IS NOT WORKING YET. ANY CHANGES MADE WILL BE IGNORED.</h3>
    <?php endif; ?>

    <table class="w3-table" id="accomodationTable">
      <tr>
        <th>Type <i class="w3-opacity w3-small">description of the room</i></th>
        <th>Persons/room</th>
        <th>Price/person</th>
        <th>Quantity</th>
        <th><button class="w3-button w3-green w3-round" onclick="addRow()">+</button></th>
      </tr>
      <?php
        $is_disabled=false;
        if($editEvent){
          $rooms=$event_model->getRooms($event->id);
          if(new DateTime($event->pre_reg_start)<=new DateTime()){
            $is_disabled=true;
          }
        }
      ?>
      <?php if($editEvent&&count($rooms)>0): ?>
        <?php foreach($rooms as $room): ?>
          <tr id="row<?php echo $room->id; ?>">
            <td><input type="text" class="w3-input" name="type<?php echo $room->id; ?>" required value="<?php echo $room->type; ?>"></td>
      			<td><input type="number" class="w3-input" name="persons<?php echo $room->id; ?>" min="1" required value="<?php echo $room->persons; ?>" <?php if($is_disabled){echo 'disabled';} ?>></td>
      			<td><input type="number" class="w3-input" name="price<?php echo $room->id; ?>" required value="<?php echo $room->price; ?>"></td>
      			<td><input type="number" class="w3-input" name="quantity<?php echo $room->id; ?>" min="1" required value="<?php echo $room->quantity; ?>"></td>
      			<td><button class="w3-button w3-red w3-round" onclick="removeRow('row<?php echo $room->id; ?>')" <?php if($is_disabled){echo 'disabled';} ?>><b>-</b></button></td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </table><br>
    <div class="w3-center">
      <?php if(!$editEvent): ?>
        <button type="submit" id="submitBtn" name="new_event" class="w3-button w3-green w3-round" disabled>Create event</button>
      <?php else: ?>
        <button type="submit" id="submitBtn" name="edit_event" class="w3-button w3-green w3-round" disabled>Save changes</button>
      <?php endif; ?>
    </div>
  </form>
</div>

<script>
function price(type){
	if($("#check"+type).is(":checked")){
		$("#"+type).prop("disabled", false);
		$("#"+type+"_text").prop("disabled", false);
		$("#"+type).prop("required", true);
		$("#"+type).prop("name", type+"_price");
		$("#"+type+"_text").prop("name", type+"_text");
	}
	else{
		$("#"+type).prop("disabled", true);
		$("#"+type+"_text").prop("disabled", true);
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
var nr=0;
function addRow(){
	var row=`<tr id="row#">
			<td><input type="text" class="w3-input" name="type#" required></td>
			<td><input type="number" class="w3-input" name="persons#" min="1" required></td>
			<td><input type="text" class="w3-input" name="price#" pattern="^\\d{1,3}(,\\d{1,2})?$" title="xxx.xx" required></td>
			<td><input type="number" class="w3-input" name="quantity#" min="1" required></td>
			<td><button class="w3-button w3-red w3-round" onclick="removeRow('row#')"><b>-</b></button></td>
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
$("#newevent").addClass("w3-orange");
$("#event").addClass("w3-sand");

$(document).ready(function(){
	validate();
	$(document).on("keyup", "input", validate);
	$("input[type=checkbox][name='ticket']").on("change", validate);
	$("input[type=datetime-local]").on("change", validate);
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
	if($("#checksuper").is(":checked")){
		$("#checksponsor").prop("checked", true);
		price("sponsor");
	}
	if($("#checksponsor").is(":checked")){
		$("#checkregular").prop("checked", true);
		price("regular");
	}
	if($("#checkregular").is(":checked")){
		$("#checkfree").prop("checked", false);
		console.log("uncheck");
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
