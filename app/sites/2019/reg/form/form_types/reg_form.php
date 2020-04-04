<div id="register" class="modal fade">
  <div class="modal-dialog modal-lg">
    <div class="modal-content" style="max-width:650px">
      <?php
        $view_only=false;
        if($new_reg){
          $form_type='new';
          $c='text-success';
        }
        else{
          $form_type='edit';
          $c='text-primary';
          if(new DateTime($event->reg_end)<=$now){
            $view_only=true;
          }
        }
      ?>
      <div class="modal-header">
        <h4 class="modal-title <?php echo $c; ?>"><?php echo L::register_form_modal_h;?></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <form action="<?php echo URL; ?>register/<?php echo $form_type; ?>?id=<?php echo $id; ?>" method="post" class="needs-validation" novalidate id="regForm">
      <div class="modal-body">
        <!-- TICKET TYPES -->
        <h5><?php echo L::register_form_modal_prices_h;?></h5>
        <?php if($event->pay_button==1): ?>
          <small><?php echo L::register_form_modal_prices_prePay1;?> <b><?php echo $reg_model->convertViewable($event->payment_due, true); ?></b><?php echo L::register_form_modal_prices_prePay2;?>. <a href="<?php echo URL;?>paymentfaq" target="_blank"><?php echo L::register_form_modal_prices_prePayFAQ;?></a>.</small>
        <?php endif; ?>
        <?php if($event->regular_price==0): ?>
          <p class="text-dark"><?php echo L::register_form_modal_prices_free;?></p>
        <?php else: ?>
          <table class="table table-borderless">
            <tr>
              <th><?php echo L::register_form_modal_selection;?></th>
              <th><?php echo L::register_form_modal_price;?></th>
              <th><?php echo L::register_form_modal_prices_info;?></th>
            </tr>
            <tr>
              <td>
                <div class="custom-control custom-radio custom-control-inline">
                  <input class="custom-control-input" type="radio" name="ticket" value="regular" id="regular" <?php if(!$new_reg&&$event->ticket=='regular'){echo 'checked';} ?> required>
                  <label for="regular" class="custom-control-label">
                    <?php if(strlen($event->regular_title)!=0): ?>
                      <?php echo $event->regular_title;?>
                    <?php else: ?>
                      <?php echo L::admin_form_tickets_regular;?>
                    <?php endif; ?>
                  </label>
                </div>
              </td>
              <td><?php echo $event->regular_price; ?>€</td>
              <td><?php echo nl2br($event->regular_text); ?></td>
            </tr>
            <?php if($event->sponsor_price!=-1): ?>
            <tr>
              <td>
                <div class="custom-control custom-radio custom-control-inline">
                  <input class="custom-control-input" type="radio" name="ticket" value="sponsor" id="sponsor" <?php if(!$new_reg&&$event->ticket=='sponsor'){echo 'checked';} ?>>
                  <label for="sponsor" class="custom-control-label">
                    <?php if(strlen($event->sponsor_title)!=0): ?>
                      <?php echo $event->sponsor_title;?>
                    <?php else: ?>
                      <?php echo L::admin_form_tickets_sponsor;?>
                    <?php endif; ?>
                  </label>
                </div>
              </td>
              <td><?php echo $event->sponsor_price; ?>€</td>
              <td><?php echo nl2br($event->sponsor_text); ?></td>
            </tr>
            <?php endif; ?>
            <?php if($event->super_price!=-1): ?>
            <tr>
              <td>
                <div class="custom-control custom-radio custom-control-inline">
                  <input class="custom-control-input" type="radio" name="ticket" value="super" id="super" <?php if(!$new_reg&&$event->ticket=='super'){echo 'checked';} ?>>
                  <label for="super" class="custom-control-label">
                    <?php if(strlen($event->super_title)!=0): ?>
                      <?php echo $event->super_title;?>
                    <?php else: ?>
                      <?php echo L::admin_form_tickets_super;?>
                    <?php endif; ?>
                  </label>
                </div>
              </td>
              <td><?php echo $event->super_price; ?>€</td>
              <td><?php echo nl2br($event->super_text); ?></td>
            </tr>
            <?php endif; ?>
          </table>
        <?php endif; ?>
        <!-- ACCOMODATION -->
        <h5><?php echo L::register_form_modal_accomodation_h;?></h5>
        <?php
          $rooms=$reg_model->getAccomodation($evt_id);
          $event_duration=(int)date_diff(date_create($event->event_start), date_create($event->event_end), true)->format('%d');
        ?>
        <?php if(count($rooms)>0): ?>
          <table class="table table-borderless">
            <tr>
              <th><?php echo L::register_form_modal_selection;?></th>
              <th><?php echo L::register_form_modal_price;?></th>
              <th><?php echo L::register_form_modal_accomodation_persons;?> <i class="far fa-info-circle" title="<?php echo L::register_form_modal_accomodation_personsI;?>"></i></th>
              <th><?php echo L::register_form_modal_accomodation_availability;?> <i class="far fa-info-circle" title="<?php echo L::register_form_modal_accomodation_availabilityI;?>"></i></th>
            </tr>
            <tr>
              <td>
                <div class="custom-control custom-radio custom-control-inline">
                  <input class="custom-control-input" type="radio" name="room" value="0" id="room0" required <?php if(!$new_reg&&$event->room_id==null){echo 'checked';} ?>>
                  <label for="room0" class="custom-control-label">
                    <?php echo L::register_form_modal_accomodation_none;?>
                  </label>
                </div>
              </td>
            </tr>
            <?php foreach($rooms as $room): ?>
              <tr>
                <?php $result=$room->quantity-$reg_model->getBooked($evt_id, $room->id)->quantity;?>
                <td>
                  <div class="custom-control custom-radio custom-control-inline">
                    <input class="custom-control-input" type="radio" name="room" value="<?php echo $room->id; ?>" id="room<?php echo $room->id; ?>"
                    <?php
                      if(!$new_reg&&$event->room_id==$room->id){
                        echo 'checked ';
                      }
                      if($result<=0){
                        echo 'disabled';
                        $result=L::register_form_modal_accomodation_noSpace;
                      }
                      ?>>
                    <label for="room<?php echo $room->id; ?>" class="custom-control-label">
                      <?php echo $room->type; ?>
                    </label>
                  </div>
                </td>
                <td><?php echo $room->price; ?>€</td>
                <td><?php echo $room->persons; ?></td>
                <td><?php echo $result; ?></td>
              </tr>
            <?php endforeach; ?>
          </table>
        <?php else: ?>
          <p class="text-dark"><?php echo L::register_form_modal_accomodation_noAccomodation;?> <?php if($event_duration>0){ echo L::register_form_modal_accomodation_noAccomodationI; } ?></p>
        <?php endif; ?>
        <!-- OTHER DATA -->
        <h5><?php echo L::register_form_modal_other_h;?></h5>
        <div class="form-group">
          <label for="notes"><?php echo L::register_form_modal_other_notes;?> <small class="form-text text-muted"><?php echo L::register_form_modal_other_notesI;?></small></label>
          <input type="text" name="notes" value="<?php if(!$new_reg){echo $event->notes;} ?>" class="form-control">
        </div>
        <div class="custom-control custom-checkbox">
          <input class="custom-control-input" type="checkbox" name="fursuit" value="1" id="fursuit" <?php if(!$new_reg&&$event->fursuiter==1){echo 'checked';} ?>>
          <label for="fursuit" class="custom-control-label"><?php echo L::register_form_modal_other_fursuiter;?></label>
        </div>
        <div class="custom-control custom-checkbox">
          <input class="custom-control-input" type="checkbox" name="artist" value="1" id="artist" <?php if(!$new_reg&&$event->artist==1){echo 'checked';} ?>>
          <label for="artist" class="custom-control-label"><?php echo L::register_form_modal_other_artist;?></label>
        </div>
        <?php if($new_reg): ?>
          <?php if($account->newsletter==0): ?>
            <div class="custom-control custom-checkbox">
              <input class="custom-control-input" type="checkbox" name="newsletter" value="1" id="newsletter">
              <label for="newsletter" class="custom-control-label"><?php echo L::register_form_modal_other_newsletter;?></label>
            </div>
          <?php endif; ?>
        <?php endif; ?>
      </div>
      <div class="modal-footer">
        <span class="form-control-static"><?php echo L::register_form_modal_rules1;?> <a href="<?php echo URL;?>rules" target="_blank"><?php echo L::register_form_modal_rules2;?> <i class="far fa-external-link"></i></a></span>
        <?php if($new_reg): ?>
          <button type="submit" name="new_registration" class="btn btn-success"><?php echo L::register_form_modal_register;?></button>
        <?php elseif($color=='btn-success'): ?>
          <button type="submit" name="edit_registration" class="btn btn-success"><?php echo L::register_form_modal_save;?></button>
        <?php endif; ?>
      </div>
      </form>
    </div>
  </div>
</div>
