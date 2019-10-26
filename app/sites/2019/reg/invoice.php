<!--<script src="https://www.paypal.com/sdk/js?client-id=AVeiP2tIrPjHNH56s96kUoQBdq3ZOtfaOIXQNN6dm18zcgfuceqXUHP9653aezkY8Iz_rk-5H7W47T3e"></script>-->
<div class="w3-border w3-border-gray w3-round" style="width:1000px; margin: 0 auto;">
  <div class="w3-row w3-padding-32 w3-padding-large">
    <h1 class="w3-left">Invoice #: <?php echo $invoice->id;?></h1>
    <div class="w3-right">
      <form action="<?php echo URL;?>register/pay?id=<?php echo filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);?>" method="post">
        <button type="submit" class="w3-button w3-round w3-blue" name="download">Download</button>
        <a href="<?php echo URL;?>register/edit?id=<?php echo filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);?>" class="w3-button w3-border-blue w3-border w3-round">Back to event</a>
      </form>
    </div>
  </div>
  <div class="w3-row w3-padding-32 w3-padding-large">
    <div class="w3-left">
      <i>Issued for:</i><br><br>
      <?php
        echo "{$invoice->fname} {$invoice->lname}<br>
        {$invoice->address}<br>";
        if($invoice->address2!=null||$invoice->address2!=''){
          echo $invoice->address2."<br>";
        }
        echo "{$invoice->post} {$invoice->city}<br>
        {$invoice->country}";
      ?>
    </div>
    <div class="w3-right" style="min-width:120px;">
      <i>Issued by:</i><br><br>
      SloFurs<br>
      Address<br>
      Post City<br>
      Slovenia
    </div>
  </div>
  <?php
    //get colour of paid row depending on sum of payments vs invoice total
    $paid=0;
  ?>
  <div class="w3-row w3-red w3-padding-large w3-margin-bottom">
    <h3>UNPAID</h3>
  </div>
  <div class="w3-row w3-padding-large">
    <table class="w3-table w3-striped w3-bordered">
      <tr>
        <th>Description</th>
        <th>Price</th>
      </tr>
      <tr> <!-- ticket -->
        <?php
        $total=0;
          echo "<td>{$invoice->ename} Attendance ({$invoice->ticket})</td><td>";
          switch($invoice->ticket){
            case 'regular':
              echo "{$invoice->regular}€";
              $total+=$invoice->regular;
              break;
            case 'sponsor':
              echo "{$invoice->sponsor}€";
              $total+=$invoice->sponsor;
              break;
              case 'super':
                echo "{$invoice->super}€";
                $total+=$invoice->super;
          }
          echo '</td>';
        ?>
      </tr>
      <?php if($invoice->rprice!=null&&$invoice->rconfirmed==1): ?>
        <tr>
          <?php
          $total+=$invoice->rprice;
            echo "<td>Accomodation ({$invoice->rtype})</td>";
            echo "<td>{$invoice->rprice}€</td>";
          ?>
        </tr>
      <?php endif; ?>
    </table>
  </div>
  <div class="w3-row w3-padding-large">
    <div class="w3-right" style="width:300px;">
      <table class="w3-table w3-bordered">
        <tr>
          <td>Total</td>
          <td><?php echo $total;?>€</td>
        </tr>
        <tr>
          <td>Amount paid</td>
          <td><?php echo $paid;?>€</td>
        </tr>
        <tr>
          <td><b>Amount due</b></td>
          <td><b><?php echo $total-$paid;?>€</b></td>
        </tr>
        <tr>
          <td>Due date</td>
          <td><?php echo $inv_model->convertViewable($invoice->due, 1);?></td>
        </tr>
      </table>
      <div class="w3-center"><p>
        <!--<div id="paypalBtn"></div>-->
      </div>
    </div>
  </div>
</div>
<!--<script>
  paypal.Buttons({
    style:{
      layout: 'horizontal'
    },
    createOrder: function(data, actions){
      // Set up the transaction
      return actions.order.create({
        purchase_units:[{
          amount:{
            value: '<?php echo $total-$paid;?>'
          }
        }]
      });
    },
    onApprove: function(data, actions){
      // Capture the funds from the transaction
      return actions.order.capture().then(function(details){
        // Show a success message to your buyer
        alert('Transaction completed by ' + details.payer.name.given_name);
      });
    }
  }).render('#paypalBtn');
</script>-->
