<?php
class FursuitModel{
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
	// Get all fursuits from the user
	public function getAccFursuits($id){
		$sql='SELECT * FROM fursuit WHERE acc_id=:id';
		$query=$this->db->prepare($sql);
		$query->execute(array(':id'=>$id));
		return $query->fetchAll();
	}
	// Count printable fursuits - badges
	public function countFursuitBadges($id){
		$sql='SELECT COUNT(*) AS num FROM fursuit WHERE acc_id=:id AND in_use=1';
		$query=$this->db->prepare($sql);
		$query->execute(array(':id'=>$id));
		return $query->fetch();
	}
	// Add a new fursuit
	public function addFursuit($name, $animal, $in_use, $image){
		if(empty($name)||empty($animal)||$image['size']==0){
			return L::alerts_d_allFields;
		}
		$in_use=isset($in_use)?1:0;
		$target_dir='public/fursuits/';
		while(true){
			$file_name=substr(bin2hex(random_bytes(32)), 0, 30);
			if(!file_exists($target_dir.$file_name.'.png')){
				break;
			}
		}
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
		$target_file=$target_dir.$file_name.'.png';
		if(imagepng(imagecreatefromstring(file_get_contents($image['tmp_name'])), $target_file)){
			$sql="INSERT INTO fursuit(name, animal, img, in_use, acc_id) VALUES (:name, :animal, :img, :in_use, :acc_id)";
			$query=$this->db->prepare($sql);
			$query->execute(array(':name'=>$name, ':animal'=>$animal, ':img'=>$file_name, ':in_use'=>$in_use, ':acc_id'=>$_SESSION['account']));
			$this->changes($_SESSION['account'], 'added a fursuit', $_SESSION['account']);
		}
		else{
			return L::alerts_d_errorupload;
		}
	}
	// Edit fursuit details
	public function editFursuit($id, $name, $animal, $in_use, $image){
		//check if fursuit id and account id match
		$sql='SELECT * FROM fursuit WHERE id=:id';
		$query=$this->db->prepare($sql);
		$query->execute(array(':id'=>$id));
		$account=$query->fetch();
		if($account->acc_id!=$_SESSION['account']){
			$this->changes($_SESSION['account'], "attempted to change fursuit info ID $id", $account->acc_id);
			return L::alerts_d_cantDoThat;
		}
		if(empty($name)||empty($animal)){
			return L::alerts_d_allFields;
		}
		$in_use=isset($in_use)?1:0;
		$target_dir='public/fursuits/';
		if($image['size']!=0){
			while(true){
				$file_name=substr(bin2hex(random_bytes(32)), 0, 30);
				if(!file_exists($target_dir.$file_name.'.png')){
					break;
				}
			}
		}
		//name, animal
		$sql='UPDATE fursuit SET name=:name, animal=:animal, in_use=:in_use WHERE id=:id';
		$query=$this->db->prepare($sql);
		$query->execute(array(':name'=>$name, ':animal'=>$animal, ':in_use'=>$in_use, ':id'=>$id));
		$this->changes($_SESSION['account'], "updated their fursuit ID $id", $_SESSION['account']);
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
			$target_file=$target_dir.$file_name.'.png';
			if(imagepng(imagecreatefromstring(file_get_contents($image['tmp_name'])), $target_file)){
				unlink($target_dir.$account->img.'.png');
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
		//check if fursuit id and account id match
		$sql='SELECT * FROM fursuit WHERE id=:id';
		$query=$this->db->prepare($sql);
		$query->execute(array(':id'=>$id));
		$account=$query->fetch();
		if($account->acc_id!=$_SESSION['account']){
			$this->changes($_SESSION['account'], "attempted to delete a fursuit ID $id", $account->acc_id);
			return L::alerts_d_cantDoThat;
		}
		unlink('public/fursuits/'.$account->img.'.png');
		$sql='DELETE FROM fursuit WHERE id=:id';
		$query=$this->db->prepare($sql);
		$query->execute(array(':id'=>$id));
		$this->changes($_SESSION['account'], "deleted their fursuit ID $id", $_SESSION['account']);
	}
}
