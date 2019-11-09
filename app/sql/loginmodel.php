<?php
class LogInModel{
	// Database
	function __construct($db){
		try{
			$this->db=$db;
		}
		catch (PDOException $e){}
	}
	//Changes storage
	public function changes($who, $what, $for_who){
		$sql="INSERT INTO changes(who, what, for_who, changed_at) VALUES (:who, :what, :for_who, :changed_at)";
		$query=$this->db->prepare($sql);
		$query->execute(array(':who'=>$who, ':what'=>$what, ':for_who'=>$for_who, ':changed_at'=>date_format(date_create(), 'Y-m-d H:i:s')));
	}
	// Login Account
	public function login($email, $password){
		if(empty($email)||empty($password)){
			return L::alerts_d_allFields;
		}
		$sql_check='SELECT * FROM account WHERE email=:email';
		$query_check=$this->db->prepare($sql_check);
		$query_check->execute(array(':email'=>$email));
		$account=$query_check->fetch();
		if($query_check->rowCount()==0){
			return L::alerts_d_invalidLogin;
		}
		if(!password_verify($password, $account->password)){
			return L::alerts_d_invalidLogin;
		}
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
	// Activate Account
	public function activate($email, $activate_token){
		if(empty($email)||empty($activate_token)){
			return L::alerts_d_invalidActivateParam;
		}
		$sql_check='SELECT * FROM account WHERE email=:email OR newemail=:email';
		$query_check=$this->db->prepare($sql_check);
		$query_check->execute(array(':email'=>$email));
		$account=$query_check->fetch();
		if($query_check->rowCount()==0){
			return L::alerts_d_noAccount;
		}
		if($account->activate==''){
			return L::alerts_i_alreadyActive;
		}
		elseif($activate_token!==$account->activate){
			return L::alerts_d_invalidActivate;
		}
		//new account
		if($account->newemail==null){
			$sql='UPDATE account SET activate=null WHERE email=:email';
			$query=$this->db->prepare($sql);
			$query->execute(array(':email'=>$email));
			$_SESSION['account']=$account->id;
			$this->changes($_SESSION['account'], 'confirmed their account', $_SESSION['account']);
			return L::alerts_s_activated;
		}
		//change email on existing account
		else if($account->newemail==$email){
			$sql='UPDATE account SET activate=null, email=:email, newemail=null WHERE newemail=:email';
			$query=$this->db->prepare($sql);
			$query->execute(array(':email'=>$email));
			$this->changes($_SESSION['account'], 'changed their email address', $_SESSION['account']);
			return L::alerts_s_emailUpdated;
		}
		else{
			return L::alerts_d_invalidActivate;
		}
	}
	public function logout(){
		session_destroy();
		session_unset();
	}
	public function passwordReset1($email){
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
		$this->changes($_SESSION['account'], 'initiated a password reset for their account', $_SESSION['account']);
		return L::alerts_s_resetPwEmail;
	}
	public function passwordReset2($email, $token){
		$sql='SELECT * FROM account WHERE email=:email AND password_reset=:token';
		$query=$this->db->prepare($sql);
		$query->execute(array(':email'=>$email, ':token'=>$token));
		$account=$query->fetchAll();
		return count($account)!=0;
	}
	public function passwordReset3($password){
		$password=password_hash($password, PASSWORD_DEFAULT);
		$email=$_SESSION['reset_email'];
		$_SESSION['reset_email']=null;
		$sql='UPDATE account SET password=:pwd, password_reset=NULL WHERE email=:email';
		$query=$this->db->prepare($sql);
		$query->execute(array(':email'=>$email, ':pwd'=>$password));
		$this->changes($_SESSION['account'], 'changed their password via password reset', $_SESSION['account']);
		return L::alerts_s_resetPw;
	}
}
