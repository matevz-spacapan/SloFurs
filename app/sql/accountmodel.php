<?php
class AccountModel{
	// Database
	function __construct($db){
		try{
			$this->db=$db;
		}
		catch (PDOException $e){
			exit('Database connection could not be established.');
		}
	}
	// Change email
	public function changeEmail($email, $password){
		if(!empty($email)&&!empty($password)){
			$email=strip_tags($email);
			$password=strip_tags($password);
			$activate_token=bin2hex(random_bytes(32));
			$sql_check='SELECT * FROM account WHERE id=:id';
			$query_check=$this->db->prepare($sql_check);
			$query_check->execute(array(':id'=>$_SESSION['account']));
			$account=$query_check->fetch();
			if(password_verify($password, $account->password)){
				$sql='UPDATE account SET newemail=:email, activate=:activate WHERE id=:id';
				$query=$this->db->prepare($sql);
				$query->execute(array(':email'=>$email, ':activate'=>$activate_token, ':id'=>$_SESSION['account']));
				return 'iTo confirm your new email, please check your inbox.';
			}
			else{
				return 'dInvalid password. No changes were made.';
			}
		}
		else{
			return 'dPlease fill all the input fields.';
		}
	}
}