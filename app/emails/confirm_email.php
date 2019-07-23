<?php
$mail = new \SendGrid\Mail\Mail();
$mail->setFrom("noreply@slofurs.org", "SloFurs");
$mail->setSubject("Confirm your email address");
$mail->addTo($email, $username);
$mail->addDynamicTemplateData("name", $username);
$mail->addDynamicTemplateData("url", URL."login/activate/".$email."/".$activate_token);
$mail->setTemplateId("d-6319f517b60c42ba9ab3f0acf02665dc");
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
