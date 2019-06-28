<?php
class FursuitModel{
	// Database
	function __construct($db){
		try{
			$this->db=$db;
		}
		catch (PDOException $e){
			exit('Database connection could not be established.');
		}
	}
	// Get all fursuits from the user
	public function getAccFursuits($id){
		$id=strip_tags($id);
		$sql='SELECT * FROM fursuit WHERE acc_id=:id';
		$query=$this->db->prepare($sql);
		$query->execute(array(':id'=>$id));
		return $query->fetchAll();
	}
	// Count printable fursuits - badges
	public function countFursuitBadges($id){
		$id=strip_tags($id);
		$sql='SELECT COUNT(*) AS num FROM fursuit WHERE acc_id=:id AND in_use=1';
		$query=$this->db->prepare($sql);
		$query->execute(array(':id'=>$id));
		return $query->fetch();
	}
	// Add a new fursuit
	public function addFursuit($name, $animal, $in_use, $image){
		if(!empty($name)&&!empty($animal)&&$image['size']!=0){
			$name=strip_tags($name);
			$animal=strip_tags($animal);
			$in_use=isset($in_use)?1:0;
			$target_dir='public/fursuits/';
			$file_name='';
			while(true){
				$file_name=substr(bin2hex(random_bytes(32)), 0, 30);
				if(!file_exists($target_dir.$file_name.'.png')){
					break;
				}
			}
			$img_param=getimagesize($image['tmp_name']);
			if($img_param!==false){
				list($width, $height)=$img_param;
				if($width==$height){
					$target_file=$target_dir.$file_name.'.png';
					if(imagepng(imagecreatefromstring(file_get_contents($image['tmp_name'])), $target_file)){
						$sql="INSERT INTO fursuit(name, animal, img, in_use, acc_id) VALUES (:name, :animal, :img, :in_use, :acc_id)";
						$query=$this->db->prepare($sql);
						$query->execute(array(':name'=>$name, ':animal'=>$animal, ':img'=>$file_name, ':in_use'=>$in_use, ':acc_id'=>$_SESSION['account']));
						return ''; //OK
					}
					else{
						return 'dThere was an error uploading the picture.';
					}
				}
				else{
					return 'dThe image is not square shaped.';
				}
			}
			else{
				return 'dPlease choose only pictures to upload.';
			}
			
		}
		else{
			return 'dPlease fill all the input fields.';
		}
	}
	// Edit fursuit details
	public function editFursuit($id, $name, $animal, $in_use, $image){
		//check if fursuit id and account id match
		$sql='SELECT * FROM fursuit WHERE id=:id';
		$query=$this->db->prepare($sql);
		$query->execute(array(':id'=>$id));
		$account=$query->fetch();
		if($account->acc_id==$_SESSION['account']){
			if(!empty($name)&&!empty($animal)){
				$name=strip_tags($name);
				$animal=strip_tags($animal);
				$in_use=isset($in_use)?1:0;
				$target_dir='public/fursuits/';
				$file_name='';
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
				//changing the image too
				if($file_name!=''){
					$img_param=getimagesize($image['tmp_name']);
					if($img_param!==false){
						list($width, $height)=$img_param;
						if($width==$height){
							$target_file=$target_dir.$file_name.'.png';
							if(imagepng(imagecreatefromstring(file_get_contents($image['tmp_name'])), $target_file)){
								unlink($target_dir.$account->img.'.png');
								$sql='UPDATE fursuit SET img=:img WHERE id=:id';
								$query=$this->db->prepare($sql);
								$query->execute(array(':img'=>$file_name, ':id'=>$id));
							}
							else{
								return 'dThere was an error uploading the picture.';
							}
						}
						else{
							return 'dThe image is not square shaped.';
						}
					}
					else{
						return 'dPlease choose only pictures to upload.';
					}
				}
				return 'sFursuit information updated.'; //OK
			}
			else{
				return 'dPlease fill all the input fields.';
			}
		}
		else{
			//TODO report incident
			return 'dTrying to change fursuits of other users. This incident was reported.';
		}
	}
	public function delFursuit($id){
		//check if fursuit id and account id match
		$sql='SELECT * FROM fursuit WHERE id=:id';
		$query=$this->db->prepare($sql);
		$query->execute(array(':id'=>$id));
		$account=$query->fetch();
		if($account->acc_id==$_SESSION['account']){
			unlink('public/fursuits/'.$account->img.'.png');
			$sql='DELETE FROM fursuit WHERE id=:id';
			$query=$this->db->prepare($sql);
			$query->execute(array(':id'=>$id));
		}
		else{
			//TODO report incident
			return 'dTrying to delete fursuits of other users. This incident was reported.';
		}
	}
}