<?php
class LogInModel{
	// Database
	function __construct($db){
		try{
			$this->db=$db;
		}
		catch (PDOException $e){
			exit('Database connection could not be established.');
		}
	}
	// Login Account
	public function login($email, $password){
		if(!empty($email)&&!empty($password)){
			$email=strip_tags($email);
			$password=strip_tags($password);
			$sql_check='SELECT * FROM account WHERE email=:email';
			$query_check=$this->db->prepare($sql_check);
			$query_check->execute(array(':email'=>$email));
			$account=$query_check->fetch();
			if($account){
				if(password_verify($password, $account->password)){
					if($account->activate==""||($account->newemail!=null&&$account->activate!=null)){
						$sql='UPDATE account SET password_reset=NULL WHERE email=:email';
						$query=$this->db->prepare($sql);
						$query->execute(array(':email'=>$email));
						$_SESSION['account']=$account->id;
					}
					else{
						return 'iThis account has not been activated. Please check your email.';
					}
				}
				else{
					return 'dInvalid password or email account.';
				}
			}
			else{
				return 'dInvalid password or email account.';
			}
		}
		else{
			return 'dPlease fill all the input fields.';
		}
	}
	// Activate Account
	public function activate($email, $activate_token){
		if(!empty($email)&&!empty($activate_token)){
			$email=strip_tags($email);
			$activate_token=strip_tags($activate_token);
			$sql_check='SELECT * FROM account WHERE email=:email OR newemail=:email';
			$query_check=$this->db->prepare($sql_check);
			$query_check->execute(array(':email'=>$email));
			$account=$query_check->fetch();
			if($account){
				if($activate_token===$account->activate){
					if($account->newemail==null){ //new account
						$sql='UPDATE account SET activate=null WHERE email=:email';
						$query=$this->db->prepare($sql);
						$query->execute(array(':email'=>$email));
						$_SESSION['account']=$account->id;
						return 'sAccount activated, you may now complete your profile.';
					}
					else if($account->newemail==$email){ //change email on existing account
						$sql='UPDATE account SET activate=null, email=:email, newemail=null WHERE newemail=:email';
						$query=$this->db->prepare($sql);
						$query->execute(array(':email'=>$email));
						return 'sYour email is now updated.';
					}
					else{
						return 'dInvalid email and/or activation token.';
					}
				}
				elseif($account->activate==""){
					return 'iThis account has already been activated.';
				}
				else{
					return 'dInvalid activation token.';
				}
			}
			else{
				return 'dThis account does not exist.';
			}
		}
		else{
			return 'dNo email or activation token provided.';
		}
	}
	public function logout(){
		session_destroy();
		session_unset();
	}
	public function passwordReset1($email){
		$email=strip_tags($email);
		$sql='SELECT username FROM account WHERE email=:email';
		$query=$this->db->prepare($sql);
		$query->execute(array(':email'=>$email));
		$account=$query->fetch();
		$username=$account->username;
		if($username==null){
			return "iAccount with that email doesn't exist.";
		}
		$token=bin2hex(random_bytes(32));
		$sql='UPDATE account SET password_reset=:password_reset WHERE email=:email';
		$query=$this->db->prepare($sql);
		$query->execute(array(':email'=>$email, ':password_reset'=>$token));
		require 'app/emails/password_reset.php';
		return 'sCheck your email to reset your password.';
	}
	public function passwordReset2($email, $token){
		$email=strip_tags($email);
		$token=strip_tags($token);
		$sql='SELECT * FROM account WHERE email=:email AND password_reset=:token';
		$query=$this->db->prepare($sql);
		$query->execute(array(':email'=>$email, ':token'=>$token));
		$account=$query->fetchAll();
		if(count($account)==0){
			return false;
		}
		$sql='UPDATE account SET password_reset=NULL WHERE email=:email';
		$query=$this->db->prepare($sql);
		$query->execute(array(':email'=>$email));
		return true;
	}
	public function passwordReset3($password){
		$password=password_hash(strip_tags($password), PASSWORD_DEFAULT);
		$email=$_SESSION['reset_email'];
		$_SESSION['reset_email']=null;
		$sql='UPDATE account SET password=:pwd WHERE email=:email';
		$query=$this->db->prepare($sql);
		$query->execute(array(':email'=>$email, ':pwd'=>$password));
		return 'sYour password was successfully changed.';
	}
}
