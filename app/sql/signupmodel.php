<?php
class SignUpModel{
	// Database
	function __construct($db){
		try{
			$this->db=$db;
		}
		catch(PDOException $e){
			exit('Database connection could not be established.');
		}
	}
	// Create Account
	public function signupAcc($username, $email, $password, $captcha){
		if(!empty($username) && !empty($email) && !empty($password)){
			$secret='6Leegq0UAAAAALXssdWnbWSfrGuT01ZaKFcwslq1';
			$verifyResponse=file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$captcha);
			$responseData=json_decode($verifyResponse);
			if(!$responseData->success){
				return L::alerts_d_captcha;
			}
			$validPw='/^(?=.{8,}$)(?=.*[a-zA-Z])(?=.*[0-9\W_]).*$/m';
			$username=strip_tags($username);
			$email=strip_tags($email);
			$password=password_hash(strip_tags($password), PASSWORD_DEFAULT);
			$created=date_format(date_create(), 'Y-m-d H:i:s');
			$activate_token=bin2hex(random_bytes(32));
			$sql_check='SELECT COUNT(id) AS accounts_with_email FROM account WHERE email=:email';
			$query_check=$this->db->prepare($sql_check);
			$query_check->execute(array(':email'=>$email));
			$accounts_with_email=$query_check->fetch()->accounts_with_email;
			if($accounts_with_email==0){
				if(preg_match($validPw, $password)==1){
					$sql="INSERT INTO account(username, email, password, created, activate, language) VALUES (:username, :email, :password, :created, :activate, :language)";
					$query=$this->db->prepare($sql);
					$query->execute(array(':username'=>$username, ':email'=>$email, ':password'=>$password, ':created'=>$created, ':activate'=>$activate_token, ':language'=>$_SESSION['lang']));
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
	// Resend activation email
	public function resendEmail($email){
		$email=strip_tags($email);
		$activate_token=bin2hex(random_bytes(32));
		$sql="SELECT id, username, activate FROM account WHERE email=:email";
		$query=$this->db->prepare($sql);
		$query->execute(array(':email'=>$email));
		$result=$query->fetch();
		if($result->activate==null){
			return L::alerts_i_alreadyActive;
		}
		$username=$result->username;
		$sql="UPDATE account SET activate=:activate WHERE email=:email";
		$query=$this->db->prepare($sql);
		$query->execute(array(':email'=>$email, ':activate'=>$activate_token));
		require 'app/emails/confirm_email.php';
		$sql="INSERT INTO changes(who, what, for_who, changed_at) VALUES (:who, :what, :for_who, :changed_at)";
		$query=$this->db->prepare($sql);
		$query->execute(array(':who'=>$result->id, ':what'=>'resent the account activation email', ':for_who'=>$result->id, ':changed_at'=>date_format(date_create(), 'Y-m-d H:i:s')));
		return L::alerts_i_toConfirm;
	}
}
