<?php
//if event is set for pre-payments
if($event->pay_button==1){
  //get price of this registration
  if($event->ticket=='regular'){
    $price=$event->regular_price;
  }
  elseif($event->ticket=='sponsor'){
    $price=$event->sponsor_price;
  }
  else{
    $price=$event->super_price;
  }

  //if Stripe payment was cancelled
  if(isset($_GET["cancel"])){
    $_SESSION['alert']='dPlačilo ni uspelo, poskusite ponovno.';
  }
  //if customer returned successfully from Stripe
  elseif(isset($_GET["session"])){
    $get_session=strip_tags($_GET["session"]);
    //update row in DB for the session (user returned)
    $reg_model->returnFromStripe($id, $get_session);
    //check if payment is in the DB
    $_SESSION['alert']=$reg_model->checkStripePayment($get_session);
  }
  if($price>0){
    //sum up existing payments and check if paid<due
    $paid=$reg_model->sumPayments($id)->paid;
    $pending=$reg_model->sumPendingPayments($id)->paid;
    if($paid<$price){
      $price-=$paid;
      if(!isset($_SESSION['session_exists']) || $_SESSION['session_exists'][0]!=$id || $_SESSION['session_exists'][1]!=$price){
        \Stripe\Stripe::setApiKey(STRIPE_PRIVATE);
        $session = \Stripe\Checkout\Session::create([
          'customer_email' => $account->email,
          'payment_method_types' => ['card'],
          'line_items' => [[
            'name' => 'Vstopnina',
            'description' => "Vstopnina za dogodek {$event->name} - {$event->ticket}",
            'amount' => $price*100,
            'currency' => 'eur',
            'quantity' => 1,
          ]],
          'success_url' => URL.'register/edit?id='.$id.'&session={CHECKOUT_SESSION_ID}',
          'cancel_url' => URL.'register/edit?id='.$id.'&cancel=1',
        ]);
        $_SESSION['session_exists']=[$id, $price];
        $_SESSION['stripesession']=$session;
        $reg_model->startStripeSession($id, $session['id'], $price);
      }
      else{
        $session=$_SESSION['stripesession'];
      }
    }
    else{
      $session=null;
      unset($_SESSION['session_exists']);
      unset($_SESSION['stripesession']);
    }
  }
}
