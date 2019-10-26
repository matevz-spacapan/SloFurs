<?php
class UsersDashModel{
	// Database
	function __construct($db){
		try{
			$this->db=$db;
		}
		catch (PDOException $e){}
	}
	// Get account with given ID
	public function define($id){
		$sql='SELECT * FROM account WHERE id=:id';
		$query=$this->db->prepare($sql);
		$query->execute(array(':id'=>$id));
		return $query->fetch();
	}
	// Account brief
	public function accountsB1(){
		$sql='SELECT count(*) AS tot FROM account';
		$query=$this->db->prepare($sql);
		$query->execute();
		return $query->fetch();
	}
	public function accountsB2(){
		$sql='SELECT count(*) AS tot FROM account WHERE fname IS NULL';
		$query=$this->db->prepare($sql);
		$query->execute();
		return $query->fetch();
	}
	//Newest accounts
	public function list(){
		$sql='SELECT id, pfp, username, email, fname, lname, created, status, activate, newemail FROM account ORDER BY username ASC';
		$query=$this->db->prepare($sql);
		$query->execute();
		return $query->fetchAll();
	}

	/*
	 * CHANGING DATA
	*/

	// Change email
	public function changeEmail($email, $id, $forced, $who){
		if(empty($email)){
			return L::alerts_d_allFields;
		}
		//check if current email is the same as the new one
		$sql='SELECT email FROM account WHERE id=:id';
		$query=$this->db->prepare($sql);
		$query->execute(array(':id'=>$id));
		$acc=$query->fetch();
		if($email==$acc->email){
			return L::alerts_d_sameEmail;
		}
		$activate_token=bin2hex(random_bytes(32));
		if($forced){
			$sql='UPDATE account SET email=:email, activate=NULL WHERE id=:id';
			$query=$this->db->prepare($sql);
			$query->execute(array(':email'=>$email, ':id'=>$id));
			$sql="INSERT INTO changes(who, what, for_who, changed_at) VALUES (:who, :what, :for_who, :changed_at)";
			$query=$this->db->prepare($sql);
			$query->execute(array(':who'=>$who, ':what'=>'forcefully changed email', ':for_who'=>$id, ':changed_at'=>date_format(date_create(), 'Y-m-d H:i:s')));
		}
		else{
			$sql='UPDATE account SET email=:email, activate=:activate WHERE id=:id';
			$query=$this->db->prepare($sql);
			$query->execute(array(':email'=>$email, ':activate'=>$activate_token, ':id'=>$id));
			$sql="INSERT INTO changes(who, what, for_who, changed_at) VALUES (:who, :what, :for_who, :changed_at)";
			$query=$this->db->prepare($sql);
			$query->execute(array(':who'=>$who, ':what'=>'changed email', ':for_who'=>$id, ':changed_at'=>date_format(date_create(), 'Y-m-d H:i:s')));
		}
		if(!$forced){
			$sql='SELECT username FROM account WHERE id=:id';
			$query=$this->db->prepare($sql);
			$query->execute(array(':id'=>$id));
			$acc=$query->fetch();
			$username=$acc->username;
			require 'app/emails/confirm_email.php';
		}
		return L::alerts_s_saved;
	}

	//Reset password
	public function resetPw($id, $who){
		$sql='SELECT username, email, password FROM account WHERE id=:id';
		$query=$this->db->prepare($sql);
		$query->execute(array(':id'=>$id));
		$acc=$query->fetch();
		$pw='!'.$acc->password;
		$token=bin2hex(random_bytes(32));
		$sql='UPDATE account SET password=:password, password_reset=:token WHERE id=:id';
		$query=$this->db->prepare($sql);
		$query->execute(array(':password'=>$pw, ':token'=>$token, ':id'=>$id));
		$email=$acc->email;
		$username=$acc->username;
		require 'app/emails/password_reset.php';
		$sql="INSERT INTO changes(who, what, for_who, changed_at) VALUES (:who, :what, :for_who, :changed_at)";
		$query=$this->db->prepare($sql);
		$query->execute(array(':who'=>$who, ':what'=>'reset the password', ':for_who'=>$id, ':changed_at'=>date_format(date_create(), 'Y-m-d H:i:s')));
		return L::alerts_s_pwAdminReset;
	}

	// Set account status
	public function setStatus($status, $id, $who){
		$sql='UPDATE account SET status=:status WHERE id=:id';
		$query=$this->db->prepare($sql);
		$query->execute(array(':status'=>$status, ':id'=>$id));
		$sql="INSERT INTO changes(who, what, for_who, changed_at) VALUES (:who, :what, :for_who, :changed_at)";
		$query=$this->db->prepare($sql);
		$query->execute(array(':who'=>$who, ':what'=>'changed the status', ':for_who'=>$id, ':changed_at'=>date_format(date_create(), 'Y-m-d H:i:s')));
	}

	// Change PFP
	public function changePFP($img_file, $id, $who){
		$target_dir='public/accounts/';
		//check if pfp was already uploaded
		$sql='SELECT pfp FROM account WHERE id=:id';
		$query=$this->db->prepare($sql);
		$query->execute(array(':id'=>$id));
		$account=$query->fetch();
		$file_name='';
		while(true){
			$file_name=substr(bin2hex(random_bytes(32)), 0, 30);
			if(!file_exists($target_dir.$file_name.'.png')){
				break;
			}
		}
		$img_param=getimagesize($img_file['tmp_name']);
		if($img_param!==false){
			list($width, $height)=$img_param;
			if($width!=$height){
				return L::alerts_d_notSquare;
			}
			$target_file=$target_dir.$file_name.'.png';
			if(imagepng(imagecreatefromstring(file_get_contents($img_file['tmp_name'])), $target_file)){
				if($account->pfp!=null){
					unlink($target_dir.$account->pfp.'.png');
				}
				$sql='UPDATE account SET pfp=:pfp WHERE id=:id';
				$query=$this->db->prepare($sql);
				$query->execute(array(':pfp'=>$file_name, ':id'=>$id));
				$sql="INSERT INTO changes(who, what, for_who, changed_at) VALUES (:who, :what, :for_who, :changed_at)";
				$query=$this->db->prepare($sql);
				$query->execute(array(':who'=>$who, ':what'=>'changed the PFP', ':for_who'=>$id, ':changed_at'=>date_format(date_create(), 'Y-m-d H:i:s')));
				return L::alerts_s_pfpChanged;
			}
			else{
				return L::alerts_d_errorupload;
			}
		}
		else{
			return L::alerts_d_onlyPic;
		}
	}

	// Delete PFP
	public function deletePFP($id, $who){
		$target_dir='public/accounts/';
		$sql='SELECT pfp FROM account WHERE id=:id';
		$query=$this->db->prepare($sql);
		$query->execute(array(':id'=>$id));
		$account=$query->fetch();
		if($account->pfp!=null){
			unlink($target_dir.$account->pfp.'.png');
			$sql='UPDATE account SET pfp=null WHERE id=:id';
			$query=$this->db->prepare($sql);
			$query->execute(array(':id'=>$id));
			$sql="INSERT INTO changes(who, what, for_who, changed_at) VALUES (:who, :what, :for_who, :changed_at)";
			$query=$this->db->prepare($sql);
			$query->execute(array(':who'=>$who, ':what'=>'deleted the PFP', ':for_who'=>$id, ':changed_at'=>date_format(date_create(), 'Y-m-d H:i:s')));
		}
	}

	// Update contact info
	public function updateProfile($fname, $lname, $address, $address2, $city, $postcode, $country, $phone, $dob, $gender, $language, $id, $who){
		if(!empty($fname)&&!empty($lname)&&!empty($address)&&!empty($city)&&!empty($postcode)&&!empty($country)&&!empty($phone)&&!empty($dob)&&!empty($gender)){
			$sql='UPDATE account SET fname=:fname, lname=:lname, address=:address, address2=:address2, post=:post, city=:city, country=:country, phone=:phone, dob=:dob, gender=:gender, language=:language WHERE id=:id';
			$query=$this->db->prepare($sql);
			$query->execute(array(':fname'=>$fname, ':lname'=>$lname, ':address'=>$address, ':address2'=>$address2, ':post'=>$postcode, ':city'=>$city, ':country'=>$country, ':phone'=>$phone, ':dob'=>$dob, ':gender'=>$gender, ':language'=>$language, ':id'=>$id));
			$sql="INSERT INTO changes(who, what, for_who, changed_at) VALUES (:who, :what, :for_who, :changed_at)";
			$query=$this->db->prepare($sql);
			$query->execute(array(':who'=>$who, ':what'=>'updated profile info', ':for_who'=>$id, ':changed_at'=>date_format(date_create(), 'Y-m-d H:i:s')));
			return L::alerts_s_accUpdated;
		}
		else{
			return L::alerts_d_allMandatory;
		}
	}

	// Delete profile info, if possible
	public function deleteProfile($id, $who){
		//if no upcoming Evt
		$sql='SELECT registration.id AS id, name, event_start, event_end, reg_end, confirmed, fursuiter, artist FROM event INNER JOIN registration ON event.id=registration.event_id WHERE ((event_start<=NOW() AND event_end>=NOW()) OR event_start>NOW()) AND acc_id=:acc_id ORDER BY event_start ASC';
		$query=$this->db->prepare($sql);
		$query->execute(array(':acc_id'=>$id));
		$upcoming=$query->fetchAll();
		if(count($upcoming)!=0){
			return L::alerts_d_cantDeleteAcc1;
		}
		//if past Evt>5 days ago
		$sql='SELECT * FROM event INNER JOIN registration ON event.id=registration.event_id WHERE event_end<NOW()-INTERVAL 5 DAY AND acc_id=:id ORDER BY event_end ASC';
		$query=$this->db->prepare($sql);
		$query->execute(array(':id'=>$id));
		$past=$query->fetchAll();
		if(count($past)!=0){
			return L::alerts_d_cantDeleteAcc2;
		}
		$sql='UPDATE account SET fname=NULL, lname=NULL, address=NULL, address2=NULL, post=NULL, city=NULL, country=NULL, phone=NULL, dob=NULL, gender=NULL WHERE id=:id';
		$query=$this->db->prepare($sql);
		$query->execute(array(':id'=>$id));
		$sql="INSERT INTO changes(who, what, for_who, changed_at) VALUES (:who, :what, :for_who, :changed_at)";
		$query=$this->db->prepare($sql);
		$query->execute(array(':who'=>$who, ':what'=>'cleared account info', ':for_who'=>$id, ':changed_at'=>date_format(date_create(), 'Y-m-d H:i:s')));
		return L::alerts_s_accDeleted;
	}

	// Change ban status
	public function ban($id, $who){
		$sql='SELECT banned FROM account WHERE id=:id';
		$query=$this->db->prepare($sql);
		$query->execute(array(':id'=>$id));
		$account=$query->fetch();
		$banned=1;
		$text='banned';
		if($account->banned==1){
			$banned=0;
			$text='unbanned';
		}
		$sql='UPDATE account SET banned=:banned WHERE id=:id';
		$query=$this->db->prepare($sql);
		$query->execute(array(':banned'=>$banned, ':id'=>$id));
		$sql="INSERT INTO changes(who, what, for_who, changed_at) VALUES (:who, :what, :for_who, :changed_at)";
		$query=$this->db->prepare($sql);
		$query->execute(array(':who'=>$who, ':what'=>$text, ':for_who'=>$id, ':changed_at'=>date_format(date_create(), 'Y-m-d H:i:s')));
	}
}
