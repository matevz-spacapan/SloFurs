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
	public function signupAcc($username, $email, $password, $url){
		if(!empty($username) && !empty($email) && !empty($password)){
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
				$sql="INSERT INTO account(username, email, password, created, activate) VALUES (:username, :email, :password, :created, :activate)";
				$query=$this->db->prepare($sql);
				$query->execute(array(':username'=>$username, ':email'=>$email, ':password'=>$password, ':created'=>$created, ':activate'=>$activate_token));
				//TODO add email sending to confirm account
				return 'iTo confirm your account please check your e-mail.';
			}
			else{
				return 'dThis e-mail has already been registered.';
			}
		}
		else{
			return 'dPlease fill all the input fields.';
		}
	}
}