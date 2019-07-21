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
						if($account->password_reset!=null){
							$sql='UPDATE account SET password_reset=NULL WHERE email=:email';
							$query=$this->db->prepare($sql);
							$query->execute(array(':email'=>$email));
						}
						if($account->banned==1){
							return L::alerts_d_banned;
						}
						$_SESSION['account']=$account->id;
						$_SESSION['lang']=$account->language;
					}
					else{
						return L::alerts_i_notActive;
					}
				}
				else{
					return L::alerts_d_invalidLogin;
				}
			}
			else{
				return L::alerts_d_invalidLogin;
			}
		}
		else{
			return L::alerts_d_allFields;
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
						return L::alerts_s_activated;
					}
					else if($account->newemail==$email){ //change email on existing account
						$sql='UPDATE account SET activate=null, email=:email, newemail=null WHERE newemail=:email';
						$query=$this->db->prepare($sql);
						$query->execute(array(':email'=>$email));
						return L::alerts_s_emailUpdated;
					}
					else{
						return L::alerts_d_invalidActivate;
					}
				}
				elseif($account->activate==""){
					return L::alerts_i_alreadyActive;
				}
				else{
					return L::alerts_d_invalidActivate;
				}
			}
			else{
				return L::alerts_d_noAccount;
			}
		}
		else{
			return L::alerts_d_invalidActivateParam;
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
			return L::alerts_s_resetPwEmail;
		}
		$token=bin2hex(random_bytes(32));
		$sql='UPDATE account SET password_reset=:password_reset WHERE email=:email';
		$query=$this->db->prepare($sql);
		$query->execute(array(':email'=>$email, ':password_reset'=>$token));
		require 'app/emails/password_reset.php';
		return L::alerts_s_resetPwEmail;
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
		return L::alerts_s_resetPw;
	}
}
