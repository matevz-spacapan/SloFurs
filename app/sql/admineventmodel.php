<?php
class AdminEventModel{
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
  //Get info from event ID...
  public function getEvent($id){
    $sql='SELECT * FROM event WHERE id=:id';
    $query=$this->db->prepare($sql);
    $query->execute(array(':id'=>$id));
    return $query->fetch();
  }
  //reformat datetime
  public function reformatForDB($date){
    $d = DateTime::createFromFormat("d.m.Y H:i", $date);
    return $d->format("Y-m-d H:i:s");
  }
  //reformat datetime
  public function reformatForWeb($date){
    $d = DateTime::createFromFormat("Y-m-d H:i:s", $date);
    return $d->format("d.m.Y H:i");
  }

  //create new event
  public function addEvent($fields, $image){
		$sql='SELECT * FROM account WHERE id=:id';
		$query=$this->db->prepare($sql);
		$query->execute(array(':id'=>$_SESSION['account']));
		$account=$query->fetch();
		//account doesn't have privileges
		if($account->status<ADMIN){
			$this->changes($_SESSION['account'], 'attempted to create an event', $_SESSION['account']);
			return L::alerts_d_cantDoThat;
		}

    $name=strip_tags($fields['name']);
    $start=strip_tags($fields['start']);
    $start=$this->reformatForDB($start);
    $end=strip_tags($fields['end']);
    $end=$this->reformatForDB($end);
    $location=strip_tags($fields['location']);
    $description=$fields['description'];
    $arr=$this->photoEdit($image);
    $sql="INSERT INTO event(name, event_start, event_end, location, description, img) VALUES (:name, :event_start, :event_end, :location, :description, :img)";
		$query=$this->db->prepare($sql);
		$query->execute(array(':name'=>$name, ':event_start'=>$start, ':event_end'=>$end, ':location'=>$location, ':description'=>$description, ':img'=>$arr['filename']));
		return array('id'=>$this->db->lastInsertId(), 'err'=>$arr['err']);
  }
  //edit event on step 1
  public function editEvent1($id, $fields, $image){
		$sql='SELECT * FROM account WHERE id=:id';
		$query=$this->db->prepare($sql);
		$query->execute(array(':id'=>$_SESSION['account']));
		$account=$query->fetch();
		//account doesn't have privileges
		if($account->status<ADMIN){
			$this->changes($_SESSION['account'], 'attempted to edit event '.$id, $_SESSION['account']);
			return L::alerts_d_cantDoThat;
		}

    $name=strip_tags($fields['name']);
    $start=strip_tags($fields['start']);
    $start=$this->reformatForDB($start);
    $end=strip_tags($fields['end']);
    $end=$this->reformatForDB($end);
    $location=strip_tags($fields['location']);
    $description=$fields['description'];
    $arr=$this->photoEdit($image);
    $sql="UPDATE event SET name=:name, event_start=:event_start, event_end=:event_end, location=:location, description=:description WHERE id=:id";
		$query=$this->db->prepare($sql);
		$query->execute(array(':name'=>$name, ':event_start'=>$start, ':event_end'=>$end, ':location'=>$location, ':description'=>$description, ':id'=>$id));
    if(isset($arr['filename'])){
      $sql='UPDATE event SET img=:img WHERE id=:id';
      $query=$this->db->prepare($sql);
      $query->execute(array(':img'=>$arr['filename'], ':id'=>$id));
    }
		return $arr['err'];
  }
  //add/edit photo for event
  public function photoEdit($image, $id=null){
    $file_name=null;
    $err=null;
    if($image['size']!=0){
      $target_dir='public/events/';
      $file_name='';
      while(true){
        $file_name=substr(bin2hex(random_bytes(32)), 0, 30);
        if(!file_exists($target_dir.$file_name.'.png')){
          break;
        }
      }
      $img_param=getimagesize($image['tmp_name']);
      if(!$img_param){
        $err=L::alerts_d_onlyPic;
        $file_name=null;
        goto skipping;
      }
      list($width, $height)=$img_param;
      if($width!=250||$height!=132){
        $err=L::alerts_d_not170.". w: $width h: $height";
        $file_name=null;
        goto skipping;
      }
      $target_file=$target_dir.$file_name.'.png';
      if(!imagepng(imagecreatefromstring(file_get_contents($image['tmp_name'])), $target_file)){
        $err=L::alerts_d_errorupload;
        $file_name=null;
        goto skipping;
      }
      if(isset($id)){
        $sql='SELECT img FROM event WHERE id=:id';
  		  $query=$this->db->prepare($sql);
  		  $query->execute(array(':id'=>$id));
  		  $event=$query->fetch();
  			if(file_exists($target_dir.$event->img.'.png')){
  				unlink($target_dir.$event->img.'.png');
  			}
      }
    }
    skipping:
    return array('filename'=>$file_name, 'err'=>$err);
  }
}
?>
