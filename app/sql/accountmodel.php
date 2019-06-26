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
	// Change PFP
	public function changePFP($img_file){
		$target_dir='public/accounts/';
		//check if pfp was already uploaded
		$sql='SELECT pfp FROM account WHERE id=:id';
		$query=$this->db->prepare($sql);
		$query->execute(array(':id'=>$_SESSION['account']));
		$account=$query->fetch();
		$file_name='';
		while(true){
			$file_name=substr(bin2hex(random_bytes(32)), 0, 30);
			if(!file_exists('public/accounts/'.$file_name.'.png')){
				break;
			}
		}
		if(getimagesize($img_file['tmp_name'])!==false){
			$target_file=$target_dir.$file_name.'.png';
			if(imagepng(imagecreatefromstring(file_get_contents($img_file['tmp_name'])), $target_file)){
				if($account->pfp!=null){
					unlink($target_dir.$account->pfp.'.png');
				}
				$sql='UPDATE account SET pfp=:pfp WHERE id=:id';
				$query=$this->db->prepare($sql);
				$query->execute(array(':pfp'=>$file_name, ':id'=>$_SESSION['account']));
				return 'sYour profile picture has been changed.';
			}
			else{
				return 'dThere was an error uploading the picture.';
			}
		}
		else{
			return 'dPlease choose only pictures.';
		}
	}
	// Update contact info
	public function updateProfile($fname, $lname, $address, $address2, $city, $postcode, $country, $phone, $dob, $gender){
		if(!empty($fname)&&!empty($lname)&&!empty($address)&&!empty($city)&&!empty($postcode)&&!empty($country)&&!empty($phone)&&!empty($dob)&&!empty($gender)){
			$fname=strip_tags($fname);
			$lname=strip_tags($lname);
			$address=strip_tags($address);
			$address2=strip_tags($address2);
			$city=strip_tags($city);
			$postcode=strip_tags($postcode);
			$country=strip_tags($country);
			$phone=strip_tags($phone);
			$dob=strip_tags($dob);
			$gender=strip_tags($gender);
			$sql='UPDATE account SET fname=:fname, lname=:lname, address=:address, address2=:address2, post=:post, city=:city, country=:country, phone=:phone, dob=:dob, gender=:gender WHERE id=:id';
			$query=$this->db->prepare($sql);
			$query->execute(array(':fname'=>$fname, ':lname'=>$lname, ':address'=>$address, ':address2'=>$address2, ':post'=>$postcode, ':city'=>$city, ':country'=>$country, ':phone'=>$phone, ':dob'=>$dob, ':gender'=>$gender, ':id'=>$_SESSION['account']));
			return 'sAccount information updated.';
		}
		else{
			return 'dPlease fill all the non-optional input fields.';
		}
	}
	// Change password
	public function changePw($oldPw, $newPw){
		$validPw='/^(?=.{8,}$)(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\W_]).*$/m';
		$oldPw=strip_tags($oldPw);
		$newPw=strip_tags($newPw);
		if(preg_match($validPw, $newPw)==1){
			$newPw=password_hash(strip_tags($newPw), PASSWORD_DEFAULT);
			$sql='SELECT password FROM account WHERE id=:id';
			$query=$this->db->prepare($sql);
			$query->execute(array(':id'=>$_SESSION['account']));
			$account=$query->fetch();
			if(password_verify($oldPw, $account->password)){
				$sql='UPDATE account SET password=:password WHERE id=:id';
				$query=$this->db->prepare($sql);
				$query->execute(array(':password'=>$newPw, ':id'=>$_SESSION['account']));
				return 'sYour password has been changed.';
			}
			else{
				return 'dYour current password is not valid.';
			}
		}
		else{
			return 'dYour new password is not valid.';
		}
	}
}