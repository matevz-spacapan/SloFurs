<?php
class Hooks extends Connection{
	public function index(){
    \Stripe\Stripe::setApiKey(STRIPE_PRIVATE);
    $endpoint_secret = 'whsec_...';
		$account=$this->getSessionAcc();
    $payload = @file_get_contents('php://input');
    $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
    $event = null;
    try {
      $event = \Stripe\Webhook::constructEvent(
        $payload, $sig_header, $endpoint_secret
      );
    } catch(\UnexpectedValueException $e) {
      // Invalid payload
      http_response_code(400);
      exit();
    } catch(\Stripe\Exception\SignatureVerificationException $e) {
      // Invalid signature
      http_response_code(400);
      exit();
    }
    // Handle the checkout.session.completed event
    if ($event->type == 'checkout.session.completed') {
      $session = $event->data->object;
      //handle payment into DB (amount, session_id); connect payment to registration on redirect from Stripe on client side
      
    }
    http_response_code(200);
	}
}
