<?php
class FursuitDashModel{
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

	// Fursuits brief
	public function fursuitsB(){
		$sql='SELECT count(*) AS tot FROM fursuit';
		$query=$this->db->prepare($sql);
		$query->execute();
		return $query->fetch();
	}

	// Get all fursuits from the user
	public function getFursuits(){
		$sql='SELECT * FROM fursuit';
		$query=$this->db->prepare($sql);
		$query->execute();
		return $query->fetchAll();
	}

	// Edit fursuit details
	public function editFursuit($id, $name, $animal, $in_use, $image){
		if(empty($name)||empty($animal)){
			return L::alerts_d_allFields;
		}
		$sql='SELECT * FROM account WHERE id=:id';
		$query=$this->db->prepare($sql);
		$query->execute(array(':id'=>$_SESSION['account']));
		$account=$query->fetch();
		//account doesn't have privileges
		if($account->status<SUPER){
			$this->changes($_SESSION['account'], "attempted to edit the fursuit ID $id", $_SESSION['account']);
			return L::alerts_d_cantDoThat;
		}
		$sql='SELECT * FROM fursuit WHERE id=:id';
		$query=$this->db->prepare($sql);
		$query->execute(array(':id'=>$id));
		$account=$query->fetch();
		$in_use=isset($in_use)?1:0;
		$target_dir='public/fursuits/';
		$file_name='';
		if($image['size']!=0){
			while(true){
				$file_name=substr(bin2hex(random_bytes(32)), 0, 30);
				if(!file_exists($target_dir.$file_name.'.jpg')){
					break;
				}
			}
		}
		//name, animal
		$sql='UPDATE fursuit SET name=:name, animal=:animal, in_use=:in_use WHERE id=:id';
		$query=$this->db->prepare($sql);
		$query->execute(array(':name'=>$name, ':animal'=>$animal, ':in_use'=>$in_use, ':id'=>$id));
		$this->changes($_SESSION['account'], "edited the fursuit ID $id", $_SESSION['account']);

		//changing the image too
		if($file_name!=''){
			$img_param=getimagesize($image['tmp_name']);
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
			if(imagejpeg(imagecreatefromstring(file_get_contents($image['tmp_name'])), $target_file)){
				unlink($target_dir.$account->img.'.jpg');
				$sql='UPDATE fursuit SET img=:img WHERE id=:id';
				$query=$this->db->prepare($sql);
				$query->execute(array(':img'=>$file_name, ':id'=>$id));
			}
			else{
				return L::alerts_d_errorupload;
			}
		}
		return L::alerts_s_saved;
	}
	public function delFursuit($id){
		$sql='SELECT * FROM account WHERE id=:id';
		$query=$this->db->prepare($sql);
		$query->execute(array(':id'=>$_SESSION['account']));
		$account=$query->fetch();
		//account doesn't have privileges
		if($account->status<SUPER){
			$this->changes($_SESSION['account'], "attempted to delete the fursuit ID $id", $_SESSION['account']);
			return L::alerts_d_cantDoThat;
		}
		$sql='SELECT * FROM fursuit WHERE id=:id';
		$query=$this->db->prepare($sql);
		$query->execute(array(':id'=>$id));
		$account=$query->fetch();
		unlink('public/fursuits/'.$account->img.'.jpg');
		$sql='DELETE FROM fursuit WHERE id=:id';
		$query=$this->db->prepare($sql);
		$query->execute(array(':id'=>$id));
		$this->changes($_SESSION['account'], "deleted the fursuit ID $id", $_SESSION['account']);
	}
}
