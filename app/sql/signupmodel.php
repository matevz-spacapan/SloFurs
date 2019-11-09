<?php
class SignUpModel{
	// Database
	function __construct($db){
		try{
			$this->db=$db;
		}
		catch (PDOException $e){}
	}
	// Create Account
	public function signupAcc($username, $email, $password, $captcha, $newsletter){
		if(!empty($username) && !empty($email) && !empty($password)){
			$verifyResponse=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".RECAPTCHA_SECRET."&response=$captcha");
			$responseData=json_decode($verifyResponse);
			if(!$responseData->success){
				return L::alerts_d_captcha;
			}
			$validPw='/^(?=.{8,}$)(?=.*[a-zA-Z])(?=.*[0-9\W_]).*$/m';
			$password=password_hash($password, PASSWORD_DEFAULT);
			$created=date_format(date_create(), 'Y-m-d H:i:s');
			$activate_token=bin2hex(random_bytes(32));
			$sql_check='SELECT COUNT(id) AS accounts_with_email FROM account WHERE email=:email';
			$query_check=$this->db->prepare($sql_check);
			$query_check->execute(array(':email'=>$email));
			$accounts_with_email=$query_check->fetch()->accounts_with_email;
			if($accounts_with_email==0){
				if(preg_match($validPw, $password)==1){
					$sql="INSERT INTO account(username, email, password, created, activate, language, newsletter) VALUES (:username, :email, :password, :created, :activate, :language, :newsletter)";
					$query=$this->db->prepare($sql);
					$query->execute(array(':username'=>$username, ':email'=>$email, ':password'=>$password, ':created'=>$created, ':activate'=>$activate_token, ':language'=>$_SESSION['lang'], ':newsletter'=>$newsletter));
					require 'app/emails/confirm_email.php';
					$sql="INSERT INTO changes(who, what, for_who, changed_at) VALUES (:who, :what, :for_who, :changed_at)";
					$id=$this->db->lastInsertId();
					$query=$this->db->prepare($sql);
					$query->execute(array(':who'=>$id, ':what'=>'created a new account', ':for_who'=>$id, ':changed_at'=>date_format(date_create(), 'Y-m-d H:i:s')));
					return L::alerts_i_toConfirm;
				}
				else{
					return L::alerts_d_invalidPwFormatSignup;
				}
			}
			else{
				return L::alerts_d_alreadySignedUp;
			}
		}
		else{
			return L::alerts_d_allFields;
		}
	}
}
