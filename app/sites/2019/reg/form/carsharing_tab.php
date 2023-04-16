<div class="tab-pane container-fluid fade mt-3" id="Rides">
  <!-- Add new -->
  <?php $limiter=date_create(); $limiter->add(new DateInterval('P1D')); ?>
  <?php if(strpos($this->getBaseUrl(), 'edit')!==false&&date_create($event->event_end)>=$limiter): ?>
    <button onclick="$('#addNew').removeClass('w3-hide')" class="w3-button w3-round w3-border w3-border-blue"><?php echo L::register_form_car_new;?></button><br>
    <div class="w3-hide col-md-10 col-lg-6" id="addNew">
      <br>
      <p><?php echo L::register_form_car_p;?></p>
      <form action="<?php echo URL;?>register/edit?id=<?php echo $id;?>" method="post" autocomplete="off">
        <label><?php echo L::register_form_car_direction;?></label> <sup class="text-danger">*</sup><br/>
        <input class="w3-radio" type="radio" name="direction" value="0" required>
        <label><?php echo L::register_form_car_to;?></label>
        <input class="w3-radio" type="radio" name="direction" value="1">
        <label><?php echo L::register_form_car_from;?></label><br>
        <label><?php echo L::register_form_car_number;?></label> <sup class="text-danger">*</sup> <i class="w3-opacity w3-small"><?php echo L::register_form_car_numberI;?></i>
        <select class="w3-select" name="passengers">
          <option value="1">1</option>
          <option value="2">2</option>
          <option value="3">3</option>
          <option value="4">4</option>
          <option value="5">5</option>
          <option value="6">6</option>
          <option value="7">7</option>
        </select>
        <label><?php echo L::register_form_car_date;?></label> <sup class="text-danger">*</sup> <i class="w3-opacity w3-small"><?php echo L::register_form_car_dateI;?></i>
        <input type="datetime-local" class="w3-input" name="outbound" required>
        <label><?php echo L::register_form_car_price;?></label> <sup class="text-danger">*</sup> <i class="w3-opacity w3-small"><?php echo L::register_form_car_priceI;?></i>
        <input type="number" class="w3-input" name="price" min="0" required>
        <label><?php echo L::register_form_car_desc;?></label> <sup class="text-danger">*</sup> <i class="w3-opacity w3-small"><?php echo L::register_form_car_descI;?></i>
        <textarea class="w3-input" name="description" required></textarea><p>
          <div class="text-center">
            <button type="submit" name="new_car_share" class="w3-button w3-round w3-green"><?php echo L::register_form_car_add;?></button>
          </div>
      </form>
    </div><br>
  <?php endif; ?>

  <!-- To event -->
  <?php $carShares=$reg_model->getAllTo($evt_id); ?>
  <h3><?php echo L::register_form_car_to;?></h3>
  <div class="row mb-3">
    <?php if(count($carShares)>0): ?>
      <div class="w3-responsive">
        <table class="w3-table w3-striped w3-hoverable text-centered">
          <tr>
            <th><?php echo L::register_form_car_driver;?></th>
            <th><?php echo L::register_form_car_at;?></th>
            <th><?php echo L::register_form_car_spots;?></th>
            <th><?php echo L::register_form_car_info;?></th>
            <th></th>
          </tr>
          <?php foreach($carShares as $carShare): ?>
            <?php if($account!=null&&$carShare->accId==$_SESSION['account']): ?>
              <div id="<?php echo $carShare->id; ?>" class="w3-modal">
                <div class="w3-modal-content w3-card-4 w3-round-large" style="max-width:600px">
                  <header class="w3-container w3-blue text-center roundHeaderTop">
                    <span onclick="$('#<?php echo $carShare->id; ?>').hide()"
                    class="w3-button w3-display-topright roundXTop">&times;</span>
                    <h2><?php echo L::register_form_car_edit;?></h2>
                  </header>
                  <div class="w3-container">
                    <form action="<?php echo URL;?>register/edit?id=<?php echo $id;?>&carshare=<?php echo $carShare->id;?>" method="post">
                      <label><?php echo L::register_form_car_direction;?></label> <sup class="text-danger">*</sup><br/>
                      <input class="w3-radio" type="radio" name="direction" value="0" required <?php if($carShare->direction==0){ echo 'checked';} ?>>
                      <label><?php echo L::register_form_car_to;?></label>
                      <input class="w3-radio" type="radio" name="direction" value="1" <?php if($carShare->direction==1){ echo 'checked';} ?>>
                      <label><?php echo L::register_form_car_from;?></label><br>
                      <label><?php echo L::register_form_car_number;?></label> <sup class="text-danger">*</sup> <i class="w3-opacity w3-small"><?php echo L::register_form_car_numberI;?></i>
                      <select class="w3-select" name="passengers">
                        <option value="1" <?php if($carShare->passengers==1){ echo 'selected';} ?>>1</option>
                        <option value="2" <?php if($carShare->passengers==2){ echo 'selected';} ?>>2</option>
                        <option value="3" <?php if($carShare->passengers==3){ echo 'selected';} ?>>3</option>
                        <option value="4" <?php if($carShare->passengers==4){ echo 'selected';} ?>>4</option>
                        <option value="5" <?php if($carShare->passengers==5){ echo 'selected';} ?>>5</option>
                        <option value="6" <?php if($carShare->passengers==6){ echo 'selected';} ?>>6</option>
                        <option value="7" <?php if($carShare->passengers==7){ echo 'selected';} ?>>7</option>
                      </select>
                      <label><?php echo L::register_form_car_date;?></label> <sup class="text-danger">*</sup> <i class="w3-opacity w3-small"><?php echo L::register_form_car_dateI;?></i>
                      <input type="datetime-local" class="w3-input" name="outbound" required value="<?php echo $reg_model->convert($carShare->outbound); ?>">
                      <label><?php echo L::register_form_car_price;?></label> <sup class="text-danger">*</sup> <i class="w3-opacity w3-small"><?php echo L::register_form_car_priceI;?></i>
                      <input type="number" class="w3-input" name="price" min="0" required value="<?php echo $carShare->price; ?>">
                      <label><?php echo L::register_form_car_desc;?></label> <sup class="text-danger">*</sup> <i class="w3-opacity w3-small"><?php echo L::register_form_car_descI;?></i>
                      <textarea class="w3-input" name="description" required><?php echo $carShare->description; ?></textarea><p>
                        <div class="text-center">
                          <button type="submit" name="edit_car_share" class="w3-button w3-round w3-green"><?php echo L::register_form_car_save;?></button>
                          <button type="submit" name="delete_car_share" class="w3-button w3-round w3-border w3-border-red"><?php echo L::register_form_car_delete;?></button>
                        </div><br>
                    </form>
                  </div>
                </div>
              </div>
            <?php endif; ?>
            <tr>
              <td><?php echo $carShare->username; ?></td>
              <td><?php echo $reg_model->convertViewable($carShare->outbound, 2); ?></td>
              <td><?php echo $carShare->passengers; ?></td>
              <td><?php echo nl2br($carShare->description); ?></td>
              <td>
                <?php if($account!=null&&$carShare->accId==$_SESSION['account']): ?>
                  <button type="button" class="w3-button w3-border w3-border-blue w3-round" onclick="$('#<?php echo $carShare->id; ?>').show()"><?php echo L::admin_dash_edit;?></button>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach;?>
        </table>
      </div>
    <?php else: ?>
      <div class="w3-container">
        <?php echo L::register_form_car_none;?></i>
      </div>
    <?php endif; ?>
  </div>

  <!-- From event -->
  <?php $carShares=$reg_model->getAllFrom($evt_id); ?>
  <h3><?php echo L::register_form_car_from;?></h3>
  <div class="row">
    <?php if(count($carShares)>0): ?>
      <div class="w3-responsive">
        <table class="w3-table w3-striped w3-hoverable text-centered">
          <tr>
            <th><?php echo L::register_form_car_driver;?></th>
            <th><?php echo L::register_form_car_at;?></th>
            <th><?php echo L::register_form_car_spots;?></th>
            <th><?php echo L::register_form_car_info;?></th>
            <th></th>
          </tr>
          <?php foreach($carShares as $carShare): ?>
            <?php if($carShare->accId==$_SESSION['account']): ?>
              <div id="<?php echo $carShare->id; ?>" class="w3-modal">
                <div class="w3-modal-content w3-card-4 w3-round-large" style="max-width:600px">
                  <header class="w3-container w3-blue text-center roundHeaderTop">
                    <span onclick="$('#<?php echo $carShare->id; ?>').hide()"
                    class="w3-button w3-display-topright roundXTop">&times;</span>
                    <h2><?php echo L::register_form_car_edit;?></h2>
                  </header>
                  <div class="w3-container">
                    <form action="<?php echo URL;?>register/edit?id=<?php echo $id;?>&carshare=<?php echo $carShare->id;?>" method="post">
                      <label><?php echo L::register_form_car_direction;?></label> <sup class="text-danger">*</sup><br/>
                      <input class="w3-radio" type="radio" name="direction" value="0" required <?php if($carShare->direction==0){ echo 'checked';} ?>>
                      <label><?php echo L::register_form_car_to;?></label>
                      <input class="w3-radio" type="radio" name="direction" value="1" <?php if($carShare->direction==1){ echo 'checked';} ?>>
                      <label><?php echo L::register_form_car_from;?></label><br>
                      <label><?php echo L::register_form_car_number;?></label> <sup class="text-danger">*</sup> <i class="w3-opacity w3-small"><?php echo L::register_form_car_numberI;?></i>
                      <select class="w3-select" name="passengers">
                        <option value="1" <?php if($carShare->passengers==1){ echo 'selected';} ?>>1</option>
                        <option value="2" <?php if($carShare->passengers==2){ echo 'selected';} ?>>2</option>
                        <option value="3" <?php if($carShare->passengers==3){ echo 'selected';} ?>>3</option>
                        <option value="4" <?php if($carShare->passengers==4){ echo 'selected';} ?>>4</option>
                        <option value="5" <?php if($carShare->passengers==5){ echo 'selected';} ?>>5</option>
                        <option value="6" <?php if($carShare->passengers==6){ echo 'selected';} ?>>6</option>
                        <option value="7" <?php if($carShare->passengers==7){ echo 'selected';} ?>>7</option>
                      </select>
                      <label><?php echo L::register_form_car_date;?></label> <sup class="text-danger">*</sup> <i class="w3-opacity w3-small"><?php echo L::register_form_car_dateI;?></i>
                      <input type="datetime-local" class="w3-input" name="outbound" required value="<?php echo $reg_model->convert($carShare->outbound); ?>">
                      <label><?php echo L::register_form_car_price;?></label> <sup class="text-danger">*</sup> <i class="w3-opacity w3-small"><?php echo L::register_form_car_priceI;?></i>
                      <input type="number" class="w3-input" name="price" min="0" required value="<?php echo $carShare->price; ?>">
                      <label><?php echo L::register_form_car_desc;?></label> <sup class="text-danger">*</sup> <i class="w3-opacity w3-small"><?php echo L::register_form_car_descI;?></i>
                      <textarea class="w3-input" name="description" required><?php echo $carShare->description; ?></textarea><p>
                        <div class="text-center">
                          <button type="submit" name="edit_car_share" class="w3-button w3-round w3-green"><?php echo L::register_form_car_save;?></button>
                          <button type="submit" name="delete_car_share" class="w3-button w3-round w3-border w3-border-red"><?php echo L::register_form_car_delete;?></button>
                        </div><br>
                    </form>
                  </div>
                </div>
              </div>
            <?php endif; ?>
            <tr>
              <td><?php echo $carShare->username; ?></td>
              <td><?php echo $reg_model->convertViewable($carShare->outbound, 2); ?></td>
              <td><?php echo $carShare->passengers; ?></td>
              <td><?php echo nl2br($carShare->description); ?></td>
              <td>
                <?php if($carShare->accId==$_SESSION['account']): ?>
                  <button type="button" class="w3-button w3-border w3-border-blue w3-round" onclick="$('#<?php echo $carShare->id; ?>').show()"><?php echo L::admin_dash_edit;?></button>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach;?>
        </table>
      </div>
    <?php else: ?>
      <div class="w3-container">
        <?php echo L::register_form_car_none;?></i>
      </div>
    <?php endif; ?>
  </div>
</div>
