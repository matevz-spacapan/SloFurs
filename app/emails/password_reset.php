<?php
$mail = new \SendGrid\Mail\Mail();
$mail->setFrom("info@slofurs.org", "SloFurs");
$mail->setSubject("Reset Password");
$mail->addTo($email, $username);
$mail->addDynamicTemplateData("name", $username);
$mail->addDynamicTemplateData("url", URL."login/forgot/".$email."/".$token);
$mail->setTemplateId("d-66e9ef5afd4a4cf6ad4298fd7e2987e5");
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
