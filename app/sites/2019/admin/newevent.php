<div class="w3-main" style="margin-left:200px">
<div class="w3-orange">
	<button class="w3-button w3-orange w3-xlarge w3-hide-large" onclick="side_open()">&#9776;</button>
	<div class="w3-container">
		<h1>New event</h1>
	</div>
</div>
<div class="w3-container">
	<div class="w3-container" style="margin: 0 auto; width: 50%">
		<form action="<?php echo URL; ?>admin/update/1" method="post" autocomplete="off" id="event">
			<h3>Event details</h3>

			<label>Name</label> <sup class="w3-text-red">*</sup>
			<input type="text" class="w3-input" name="name" required autofocus>

			<label>Start</label> <sup class="w3-text-red">*</sup> <i class="w3-opacity w3-small">when the event starts</i>
			<input type="datetime-local" class="w3-input" name="start" required>

			<label>End</label> <sup class="w3-text-red">*</sup> <i class="w3-opacity w3-small">when the event ends</i>
			<input type="datetime-local" class="w3-input" name="end" required>

			<label>Location</label>
			<input type="text" class="w3-input" name="location">

			<label>Description</label>
			<textarea class="w3-input" name="description"></textarea><p>

			<h3>Registration details</h3>

			<label>Start</label> <sup class="w3-text-red">*</sup> <i class="w3-opacity w3-small">when attendees can register for the event</i>
			<input type="datetime-local" class="w3-input" name="reg_start" required>

			<label>Pre-reg start</label> <i class="w3-opacity w3-small">optional. When staff can register before everyone else</i>
			<input type="datetime-local" class="w3-input" name="pre_reg">

			<label>End</label> <i class="w3-opacity w3-small">when attendees can't register anymore. If left blank, then they can register until the event starts</i>
			<input type="datetime-local" class="w3-input" name="reg_end">

			<h3>Age restrictions</h3>

			<input class="w3-check" type="checkbox" id="age" onclick="displayAge()">
			<label>Age restricted</label><br><br>
			<div style="display: none;" id="ageSettings">
				<label>Age for full duration</label> <sup class="w3-text-red">*</sup> <i class="w3-opacity w3-small">attendees need to be at least this old at the start of event to attend the entire event</i>
				<input type="number" class="w3-input" name="age" value="0" min="0" max="99" required>

				<label>Age with restricted access</label> <i class="w3-opacity w3-small">attendees need to be at least this old at the start of event to attend at least part of the event</i>
				<input type="number" class="w3-input" name="restricted_age" value="0" min="0" max="99">

				<label>Restrictions</label> <i class="w3-opacity w3-small">if the above field is filled, enter the restrictions that apply for this age group (eg. allowed until 8PM, allowed only on the weekend...)</i>
				<input type="text" class="w3-input" name="restricted_text">
			</div>

			<h3 style="display: inline;">Ticket types</h3> <i class="w3-opacity w3-small">at least one option must be selected</i><br><br>

			<table class="w3-table">
				<tr>
					<th>Type</th>
					<th>Cost</th>
				</tr>
				<tr>
					<td>
						<input class="w3-check" type="checkbox" name="ticket" value="free" onclick="">
						<label>Free</label>
					</td>
					<td>0</td>
				</tr>
				<tr>
					<td>
						<input class="w3-check" type="checkbox" id="checkregular" name="ticket" value="regular" onclick="price('regular')">
						<label>Regular</label>
					</td>
					<td><input type="text" class="w3-input" id="regular" pattern="^\d{1,3}(,\d{1,2})?$" title="###.##" disabled></td>
				</tr>
				<tr>
					<td>
						<input class="w3-check" type="checkbox" id="checksponsor" name="ticket" value="sponsor" onclick="price('sponsor')">
						<label>Sponsor</label>
					</td>
					<td><input type="text" class="w3-input" id="sponsor" pattern="^\d{1,3}(,\d{1,2})?$" title="###.##" disabled></td>
				</tr>
				<tr>
					<td>
						<input class="w3-check" type="checkbox" id="checksuper" name="ticket" value="super" onclick="price('super')">
						<label>Super-sponsor</label>
					</td>
					<td><input type="text" class="w3-input" id="super" pattern="^\d{1,3}(,\d{1,2})?$" title="###.##" disabled></td>
				</tr>
			</table>

			<h3 style="display: inline;">Accomodation</h3> <i class="w3-opacity w3-small">if there is no accomodation for this event, don't add any rows below</i><br><br>

			<!-- TODO table where users can add as many rows as needed for accomodation -->
			<table class="w3-table" id="accomodationTable">
				<tr>
					<th>Type <i class="w3-opacity w3-small">description of the room</i></th>
					<th>Persons/room</th>
					<th>Price/person</th>
					<th>Quantity</th>
					<th><button class="w3-button w3-green w3-round" onclick="addRow()">+</button></th>
				</tr>
			</table><br>
			<div class="w3-center">
				<button type="submit" id="submitBtn" class="w3-button w3-green w3-round" disabled>Create event</button>
			</div>
		</form>
	</div>
	
</div>

<script>
function side_open(){
	$("#accSidebar").show();
}
function side_close(){
	$("#accSidebar").hide();
}
function price(type){
	if($("#check"+type).is(":checked")){
		$("#"+type).attr("disabled", false);
		$("#"+type).attr("required", true);
		$("#"+type).attr("name", type+"_price");
	}
	else{
		$("#"+type).attr("disabled", true);
		$("#"+type).attr("required", false);
		$("#"+type).removeAttr("name");
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
function onLoad(){
	$("#newevent").addClass("w3-orange");
	$("#event").addClass("w3-sand");
}
onLoad();

$(document).ready(function() {
  validate();
  $(document).on("keyup", "input", validate);
  $("input[type=checkbox][name='ticket']").on("change", validate);
  $("input[type=datetime-local]").on("change", validate);
});
function validate(){
	var dateOK=true;
	var now=new Date();
	//NOW<=PRE-REG<REG. START
	if(now>new Date($("input[name='pre_reg']").val())||new Date($("input[name='pre_reg']").val())>=new Date($("input[name='reg_start']").val())){
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
	//check if required and inputed equals (all required filled) and if at least one price category is selected
	if(inputsWVal==requiredInputs&&dateOK&&$("input[type=checkbox][name='ticket']:checked").length>0){
		$("#submitBtn").attr("disabled", false);
	}
	else{
		$("#submitBtn").attr("disabled", true);
	}
}
</script>