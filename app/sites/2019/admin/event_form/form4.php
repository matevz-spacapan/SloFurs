<div class="w3-main" style="margin-left:300px">
<div class="container-fluid mt-3">
  <form action="<?php echo URL."admin/event/$type/$step"; if($id!=null){ echo "?id=$id"; } ?>" method="post" enctype="multipart/form-data" autocomplete="off" class="needs-validation allforms" novalidate>
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
  <div class="mt-5 text-center">
    <div class="btn-group">
      <button type="submit" name="pg3" class="btn btn-outline-success">Previous</button>
      <button type="submit" name="pg1" class="btn btn-outline-success">1</button>
      <button type="submit" name="pg2" class="btn btn-outline-danger">2</button>
      <button type="submit" name="pg3" class="btn btn-outline-success">3</button>
      <button type="button" class="btn btn-primary">4</button>
      <button type="submit" name="pg5" class="btn btn-outline-primary">5</button>
      <button type="submit" name="pg5" class="btn btn-outline-primary">Next</button>
    </div>
  </div>

</form>
