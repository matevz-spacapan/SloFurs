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
	public function signupAcc($username, $email, $password){
		if(!empty($username) && !empty($email) && !empty($password)){
			$validPw='/^(?=.{8,}$)(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\W_]).*$/m';
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
		$sql="SELECT username, activate FROM account WHERE email=:email";
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
		return L::alerts_i_toConfirm;
	}
}
