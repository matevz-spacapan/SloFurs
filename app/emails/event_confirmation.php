<?php
$mail = new \SendGrid\Mail\Mail();
$mail->setFrom("noreply@slofurs.org", "SloFurs");
$mail->setSubject("Event confirmation");
$mail->addTo($email, $username);
$mail->addBcc("pur3bolt@slofurs.org", "Pur3Bolt - BCC");
$mail->addDynamicTemplateData("event", $event_name);
$mail->addDynamicTemplateData("name", $username);
$mail->addDynamicTemplateData("edit_url", $url);
$mail->addDynamicTemplateData("attendance", $attendance);
$mail->addDynamicTemplateData("accomodation", $accomodation);
$mail->addDynamicTemplateData("bad_news", $bad_news);
$mail->addDynamicTemplateData("confirmed", $confirmed);
$mail->setTemplateId("d-ed851baf46554554be5f8d46d3f1aba2");
$sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));
try {
	$response = $sendgrid->send($mail);
	/*print $response->statusCode() . "\n";
	print_r($response->headers());
	print $response->body() . "\n";*/
} catch (Exception $e) {
	//echo 'Caught exception: '.  $e->getMessage(). "\n";
}
?>
