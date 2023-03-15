<?php
class Hooks extends Connection{
	public function index(){
    \Stripe\Stripe::setApiKey(STRIPE_PRIVATE);
    $account=$this->getSessionAcc();
    $payload = @file_get_contents('php://input');
    $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
    $event = null;
    try {
        $event = \Stripe\Webhook::constructEvent(
            $payload, $sig_header, STRIPE_WEBHOOK
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
    // Handle successful payments
    if ($event->type == 'checkout.session.completed' || $event->type == 'checkout.session.async_payment_succeeded') {
        $session = $event->data->object;
        //update payment into DB (amount, session_id)
        $reg_model=$this->loadSQL('RegModel');
        $reg_model->hookPaymentCompletedStripe($session['id']);
    }
    // Handle failed payments
    if($event->type == 'checkout.session.expired' || $event->type == 'checkout.session.async_payment_failed'){
        $session = $event->data->object;
        //delete payment from DB
        $reg_model=$this->loadSQL('RegModel');
        $reg_model->hookPaymentFailedStripe($session['id']);
    }
    http_response_code(200);
	}
}
