<?php
class AccountModel{
	// Database
	function __construct($db){
		try{
			$this->db=$db;
		}
		catch (PDOException $e){}
	}
	//Changes storage
	public function changes($who, $what, $for_who){
		$sql="INSERT INTO changes(who, what, for_who, changed_at) VALUES (:who, :what, :for_who, NOW())";
		$query=$this->db->prepare($sql);
		$query->execute(array(':who'=>$who, ':what'=>$what, ':for_who'=>$for_who));
	}
	// Change email
	public function changeEmail($email, $password){
		if(empty($email)||empty($password)){
			return L::alerts_d_allFields;
		}
		$sql='SELECT * FROM account WHERE id=:id';
		$query=$this->db->prepare($sql);
		$query->execute(array(':id'=>$_SESSION['account']));
		$account=$query->fetch();
		//if new email is the same as the old one
		if($email==$account->email){
			return L::alerts_d_sameEmail;
		}
		//if password is invalid
		if(!password_verify($password, $account->password)){
			return L::alerts_d_invalidPw;
		}
		$activate_token=bin2hex(random_bytes(32));
		$sql='UPDATE account SET newemail=:email, activate=:activate WHERE id=:id';
		$query=$this->db->prepare($sql);
		$query->execute(array(':email'=>$email, ':activate'=>$activate_token, ':id'=>$_SESSION['account']));
		require 'app/emails/confirm_email.php';
		$this->changes($_SESSION['account'], 'initiated an email change', $_SESSION['account']);
		return L::alerts_i_confirm;
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
			if(!file_exists($target_dir.$file_name.'.jpg')){
				break;
			}
		}
		$img_param=getimagesize($img_file['tmp_name']);
		if(!$img_param){
			return L::alerts_d_onlyPic;
		}
		list($width, $height)=$img_param;
		$min=$width-10;
		$max=$width+10;
		if($min>=$height||$height>=$max){
			return L::alerts_d_notSquare;
		}
		$target_file=$target_dir.$file_name.'.jpg';
		if(imagejpeg(imagecreatefromstring(file_get_contents($img_file['tmp_name'])), $target_file)){
			if($account->pfp!=null){
				unlink($target_dir.$account->pfp.'.jpg');
			}
			$sql='UPDATE account SET pfp=:pfp WHERE id=:id';
			$query=$this->db->prepare($sql);
			$query->execute(array(':pfp'=>$file_name, ':id'=>$_SESSION['account']));
			$this->changes($_SESSION['account'], 'changed their PFP', $_SESSION['account']);
			return L::alerts_s_pfpChanged;
		}
		else{
			return L::alerts_d_errorupload;
		}
	}
	// Delete PFP
	public function deletePFP(){
		$target_dir='public/accounts/';
		$sql='SELECT pfp FROM account WHERE id=:id';
		$query=$this->db->prepare($sql);
		$query->execute(array(':id'=>$_SESSION['account']));
		$account=$query->fetch();
		if($account->pfp!=null){
			unlink($target_dir.$account->pfp.'.jpg');
			$sql='UPDATE account SET pfp=null WHERE id=:id';
			$query=$this->db->prepare($sql);
			$query->execute(array(':id'=>$_SESSION['account']));
			$sql="INSERT INTO changes(who, what, for_who, changed_at) VALUES (:who, :what, :for_who, :changed_at)";
			$query=$this->db->prepare($sql);
			$query->execute(array(':who'=>$_SESSION['account'], ':what'=>'deleted their PFP', ':for_who'=>$_SESSION['account'], ':changed_at'=>date_format(date_create(), 'Y-m-d H:i:s')));
		}
	}
	// Update contact info
	public function updateProfile($fname, $lname, $address, $address2, $city, $postcode, $country, $phone, $dob, $gender, $language){
		if(empty($fname)||empty($lname)||empty($address)||empty($city)||empty($postcode)||empty($country)||empty($phone)||empty($dob)||empty($gender)){
			return L::alerts_d_allMandatory;
		}
		$_SESSION['lang']=$language;
		$sql='UPDATE account SET fname=:fname, lname=:lname, address=:address, address2=:address2, post=:post, city=:city, country=:country, phone=:phone, dob=:dob, gender=:gender, language=:language WHERE id=:id';
		$query=$this->db->prepare($sql);
		$query->execute(array(':fname'=>$fname, ':lname'=>$lname, ':address'=>$address, ':address2'=>$address2, ':post'=>$postcode, ':city'=>$city, ':country'=>$country, ':phone'=>$phone, ':dob'=>$dob, ':gender'=>$gender, ':language'=>$language, ':id'=>$_SESSION['account']));
		$this->changes($_SESSION['account'], 'updated their profile info', $_SESSION['account']);
		return L::alerts_s_accUpdated;
	}
	// Delete profile info, if possible
	public function deleteProfile(){
		//if no upcoming Evt
		$sql='SELECT * FROM event INNER JOIN registration ON event.id=registration.event_id WHERE acc_id=:acc_id AND ((event_start <= NOW() AND event_end >= NOW()) OR event_start >= NOW()) ORDER BY event_start ASC';
		$query=$this->db->prepare($sql);
		$query->execute(array(':acc_id'=>$_SESSION['account']));
		$upcoming=$query->fetchAll();
		if(count($upcoming)!=0){
			return L::alerts_d_cantDeleteAcc1;
		}
		//if past Evt>5 days ago
		$sql='SELECT * FROM event INNER JOIN registration ON event.id=registration.event_id WHERE event_end>NOW()-INTERVAL 5 DAY AND acc_id=:id ORDER BY event_end ASC';
		$query=$this->db->prepare($sql);
		$query->execute(array(':id'=>$_SESSION['account']));
		$past=$query->fetchAll();
		if(count($past)!=0){
			return L::alerts_d_cantDeleteAcc2;
		}
		$sql='UPDATE account SET fname=NULL, lname=NULL, address=NULL, address2=NULL, post=NULL, city=NULL, country=NULL, phone=NULL, dob=NULL, gender=NULL WHERE id=:id';
		$query=$this->db->prepare($sql);
		$query->execute(array(':id'=>$_SESSION['account']));
		$this->changes($_SESSION['account'], 'cleared their profile info', $_SESSION['account']);
		return L::alerts_s_accDeleted;
	}
	// Change password
	public function changePw($oldPw, $newPw){
		$validPw='/^(?=.{8,}$)(?=.*[a-zA-Z])(?=.*[0-9\W_]).*$/m';
		if(preg_match($validPw, $newPw)!=1){
			return L::alerts_d_invalidPwFormat;
		}
		$sql='SELECT password FROM account WHERE id=:id';
		$query=$this->db->prepare($sql);
		$query->execute(array(':id'=>$_SESSION['account']));
		$account=$query->fetch();
		if(!password_verify($oldPw, $account->password)){
			return L::alerts_d_currPwInvalid;
		}
		$newPw=password_hash($newPw, PASSWORD_DEFAULT);
		$sql='UPDATE account SET password=:password WHERE id=:id';
		$query=$this->db->prepare($sql);
		$query->execute(array(':password'=>$newPw, ':id'=>$_SESSION['account']));
		$this->changes($_SESSION['account'], 'changed their password', $_SESSION['account']);
		return L::alerts_s_pwChanged;
	}
	// Change newsletter status
	public function newsletter(){
		$sql='SELECT newsletter FROM account WHERE id=:id';
		$query=$this->db->prepare($sql);
		$query->execute(array(':id'=>$_SESSION['account']));
		$account=$query->fetch();
		$new=0;
		if($account->newsletter==0){
			$new=1;
		}
		$sql='UPDATE account SET newsletter=:newsletter WHERE id=:id';
		$query=$this->db->prepare($sql);
		$query->execute(array(':newsletter'=>$new, ':id'=>$_SESSION['account']));
		if($new==1){
			$this->changes($_SESSION['account'], 'subscribed to the newsletter', $_SESSION['account']);
		}
		else{
			$this->changes($_SESSION['account'], 'unsubscribed from the newsletter', $_SESSION['account']);
		}
		return L::alerts_s_newsletter;
	}
}
