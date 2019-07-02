<div class="w3-main" style="margin-left:200px">
<div class="w3-orange">
	<button class="w3-button w3-orange w3-xlarge w3-hide-large" onclick="side_open()">&#9776;</button>
	<div class="w3-container">
		<h1>New event</h1>
	</div>
</div>
<div class="w3-container">
	<div class="w3-container" style="margin: 0 auto; width: 50%">
		<form action="<?php echo URL; ?>admin/update/1" method="post">
			<h3>Event details</h3>
			<label>Event type</label> <sup class="w3-text-red">*</sup><br/>
			<input class="w3-radio" type="radio" name="type" value="meet" required>
			<label>Meet</label>
			<input class="w3-radio" type="radio" name="type" value="con">
			<label>Convention</label><p>

			<label>Name</label> <sup class="w3-text-red">*</sup>
			<input type="text" class="w3-input" name="name" required>

			<label>Start</label> <sup class="w3-text-red">*</sup> <i class="w3-opacity w3-small">when the event starts</i>
			<input type="datetime-local" class="w3-input" name="start" required>

			<label>End</label> <sup class="w3-text-red">*</sup> <i class="w3-opacity w3-small">when the event ends</i>
			<input type="datetime-local" class="w3-input" name="end" required>

			<label>Location</label>
			<input type="text" class="w3-input" name="location">

			<label>Description</label>
			<textarea class="w3-input" name="desc"></textarea><p>

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

			<h3>Ticket types</h3>

			<table class="w3-table">
				<tr>
					<th>Type</th>
					<th>Cost</th>
				</tr>
				<tr>
					<td>
						<input class="w3-check" type="checkbox" name="ticket" value="free">
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

			<h3>Accomodation</h3>

			<!-- TODO table where users can add as many rows as needed for accomodation -->
			<table class="w3-table">
				<tr>
					<th>Type</th>
					<th>Price</th>
					<th>Quantity</th>
				</tr>
			</table>

			<br><button type="submit" name="edit_fursuit" class="w3-button w3-green w3-round" disabled>Create event</button>
		</form>
	</div>
	
</div>

<script>
function side_open(){
	document.getElementById("accSidebar").style.display="block";
}
function side_close(){
	document.getElementById("accSidebar").style.display="none";
}
function price(type){
	if(document.getElementById('check'.concat(type)).checked){
		document.getElementById(type).disabled=false;
		document.getElementById(type).required=true;
	}
	else{
		document.getElementById(type).disabled=true;
		document.getElementById(type).required=false;
	}
}
function displayAge(){
	if(document.getElementById("age").checked){
		document.getElementById("ageSettings").style.display="block";
		document.getElementById("ageSettings").classList.add("scale-in-center");
	}
	else{
		document.getElementById("ageSettings").style.display="none";
	}
}
function onLoad(){
	document.getElementById("newevent").classList.add("w3-orange");
	document.getElementById("event").classList.add("w3-sand");
}
onLoad();
</script>