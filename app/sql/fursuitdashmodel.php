<?php
class FursuitDashModel{
	// Database
	function __construct($db){
		try{
			$this->db=$db;
		}
		catch (PDOException $e){
			exit('Database connection could not be established.');
		}
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
		if(!empty($name)&&!empty($animal)){
			$sql='SELECT * FROM fursuit WHERE id=:id';
			$query=$this->db->prepare($sql);
			$query->execute(array(':id'=>$id));
			$account=$query->fetch();
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
			$sql="INSERT INTO changes(who, what, for_who, changed_at) VALUES (:who, :what, :for_who, :changed_at)";
			$query=$this->db->prepare($sql);
			$query->execute(array(':who'=>$_SESSION['account'], ':what'=>"edited the fursuit ID $id", ':for_who'=>$account->acc_id, ':changed_at'=>date_format(date_create(), 'Y-m-d H:i:s')));
			//changing the image too
			if($file_name!=''){
				$img_param=getimagesize($image['tmp_name']);
				if($img_param!==false){
					list($width, $height)=$img_param;
					$min=$width-10;
					$max=$width+10;
					if($min<=$height&&$height<=$max){
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
					else{
						return L::alerts_d_notSquare;
					}
				}
				else{
					return L::alerts_d_onlyPic;
				}
			}
			return L::alerts_s_saved;
		}
		else{
			return L::alerts_d_allFields;
		}
	}
	public function delFursuit($id){
		$sql='SELECT * FROM fursuit WHERE id=:id';
		$query=$this->db->prepare($sql);
		$query->execute(array(':id'=>$id));
		$account=$query->fetch();
		unlink('public/fursuits/'.$account->img.'.png');
		$sql='DELETE FROM fursuit WHERE id=:id';
		$query=$this->db->prepare($sql);
		$query->execute(array(':id'=>$id));
		$sql="INSERT INTO changes(who, what, for_who, changed_at) VALUES (:who, :what, :for_who, :changed_at)";
		$query=$this->db->prepare($sql);
		$query->execute(array(':who'=>$_SESSION['account'], ':what'=>"deleted the fursuit ID $id", ':for_who'=>$account->acc_id, ':changed_at'=>date_format(date_create(), 'Y-m-d H:i:s')));
	}
}
