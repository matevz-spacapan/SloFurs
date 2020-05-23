<div class="w3-main" style="margin-left:300px">
<div class="container-fluid mt-3">
<?php
  $id=(isset($_GET['id']))?filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT):null;
  require_once 'app/sites/'.THEME.'/admin/event_form/datetimepicker.html';
?>
<form action="<?php echo URL."admin/event/$type/$step"; if($id!=null){ echo "?id=$id"; } ?>" method="post" enctype="multipart/form-data" autocomplete="off" class="needs-validation allforms" novalidate>
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
  <div class="mt-5 text-center">
    <div class="btn-group">
      <button type="submit" name="pg4" class="btn btn-outline-success">Previous</button>
      <button type="submit" name="pg1" class="btn btn-outline-success">1</button>
      <button type="submit" name="pg2" class="btn btn-outline-danger">2</button>
      <button type="submit" name="pg3" class="btn btn-outline-success">3</button>
      <button type="submit" name="pg4" class="btn btn-outline-success">4</button>
      <button type="button" class="btn btn-primary">5</button>
      <button type="button" class="btn btn-outline-secondary" disabled>Next</button>
    </div>
  </div>
</form>

<script>
$(function () {
  $('[data-toggle="popover"]').popover();
});
var nr=1;
function addRow(){
var row=`<tr id="row#">
    <td><input type="text" class="form-control" name="type#" required></td>
    <td><input type="number" class="form-control" name="persons#" min="1" required></td>
    <td><input type="number" class="form-control" name="price#" min="0" required></td>
    <td><input type="number" class="form-control" name="quantity#" min="1" required></td>
    <td><button class="btn btn-danger" type="button" onclick="removeRow('row#')"><b>-</b></button></td>
  </tr>`;
nr++;
row=row.replace(/#/g, nr);
$("#accomodationTable tr:last").after(row);
}
function removeRow(id){
$("#"+id).remove();
console.log(id);
}
</script>
