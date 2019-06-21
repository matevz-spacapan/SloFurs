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
	public function loginAcc($email, $password){
		if(!empty($email)&&!empty($password)){
			$email=strip_tags($email);
			$password=strip_tags($password);
			$sql_check='SELECT * FROM account WHERE email=:email';
			$query_check=$this->db->prepare($sql_check);
			$query_check->execute(array(':email'=>$email));
			$account=$query_check->fetch();
			if($account){
				if(password_verify($password, $account->password)){
					if($account->activate==""){
						$_SESSION['account']=$account->id;
						return 'sOK - log in'; //TODO remove when login works completely
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
	public function activateAcc($email, $activate_token){
		if(!empty($email)&&!empty($activate_token)){
			$email=strip_tags($email);
			$activate_token=strip_tags($activate_token);
			$sql_check='SELECT * FROM account WHERE email=:email';
			$query_check=$this->db->prepare($sql_check);
			$query_check->execute(array(':email'=>$email));
			$account=$query_check->fetch();
			if($account){
				if($activate_token===$account->activate){
					$sql='UPDATE account SET activate=null WHERE email=:email';
					$query=$this->db->prepare($sql);
					$query->execute(array(':email'=>$email));
					return 'sAccount activated, you may now log in.';
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
	public function logoutAcc(){
		session_destroy();
		session_unset();
	}
}