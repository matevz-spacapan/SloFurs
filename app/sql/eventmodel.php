<?php
class EventModel{
	// Database
	function __construct($db){
		try{
			$this->db=$db;
		}
		catch (PDOException $e){
			exit('Database connection could not be established.');
		}
	}
	// Get all current/upcoming events
	public function getCEvents(){
		$sql='SELECT * FROM event WHERE (event_start<=NOW() AND event_end>=NOW()) OR event_start>NOW() ORDER BY event_start ASC';
		$query=$this->db->prepare($sql);
		$query->execute();
		return $query->fetchAll();
	}
	// Get all past events
	public function getPEvents(){
		$sql='SELECT * FROM event WHERE event_end<NOW() ORDER BY event_end ASC';
		$query=$this->db->prepare($sql);
		$query->execute();
		return $query->fetchAll();
	}
	//Get info from event ID...
	public function getEvent($id){
		$id=(int)strip_tags($id);
		$sql='SELECT * FROM event WHERE id=:id';
		$query=$this->db->prepare($sql);
		$query->execute(array(':id'=>$id));
		return $query->fetch();
	}
	//Get rooms for event ID...
	public function getRooms($id){
		$id=(int)strip_tags($id);
		$sql='SELECT etr.id AS id, quantity, type, persons, price FROM event_to_room AS etr INNER JOIN room ON etr.room_id=room.id WHERE event_id=:id';
		$query=$this->db->prepare($sql);
		$query->execute(array(':id'=>$id));
		return $query->fetchAll();
	}
	//Get registered accounts for event ID...
	public function getRegistered($id){
		$id=(int)strip_tags($id);
		$sql='SELECT registration.id as id, ticket, confirmed, fursuiter, artist, username, type FROM registration INNER JOIN account ON registration.acc_id=account.id LEFT JOIN room ON registration.room_id=room.id WHERE event_id=:id ORDER BY registration.created ASC';
		$query=$this->db->prepare($sql);
		$query->execute(array(':id'=>$id));
		return $query->fetchAll();
	}
	//Get fursuits that have in_use=1 and on this event ID...
	public function getFursuits($id){
		$id=(int)strip_tags($id);
		$sql='SELECT username, name, animal, img FROM fursuit INNER JOIN account ON fursuit.acc_id=account.id INNER JOIN registration ON registration.acc_id=account.id WHERE event_id=:id AND in_use=1';
		$query=$this->db->prepare($sql);
		$query->execute(array(':id'=>$id));
		return $query->fetchAll();
	}
	// Convert MySQL datetime to HTML datetime
	public function convert($date){
		return date_format(new DateTime($date),"Y-m-d\TH:i");
	}
	//how=true or 1: just date; how=false or 0: just hours; how=2: both
	public function convertViewable($date, $how){
		if(gettype($how)=='boolean'){
			return ($how)?date_format(new DateTime($date),"d.m.Y"):date_format(new DateTime($date),"H:i");
		}
		else{
			if($how==0){
				return date_format(new DateTime($date),"H:i");
			}
			elseif($how==1){
				return date_format(new DateTime($date),"d.m.Y");
			}
			else{
				return date_format(new DateTime($date),"d.m.Y H:i");
			}
		}
	}
	// Add new event
	/* Fields in following order:
	** name
	** (event) start
	** (event) end
	** location
	** description
	** reg_start
	** pre_reg
	** reg_end
	** age: 0<=age<=99
	** restricted_age: 0<=age<=99
	** restricted_text (for age restrictions, if applicable)
	** ticket: free, regular, sponsor, super
	**** regular_price, sponsor_price, super_price (if checked above)
	**** type#, persons#, price#, quantity# (0 or more times)
	*/
	public function addEvent($fields, $image){
		$sql='SELECT * FROM account WHERE id=:id';
		$query=$this->db->prepare($sql);
		$query->execute(array(':id'=>$_SESSION['account']));
		$account=$query->fetch();
		if($account->status!=ADMIN){
			//TODO report incident
			return "dYou can't do that. This incident was reported.";
		}

		//EVENT
		$name=strip_tags($fields["name"]);
		$start=strip_tags($fields["start"]);
		$end=strip_tags($fields["end"]);
		$location=strip_tags($fields["location"]);
		$description=$fields["description"];
		$reg_start=strip_tags($fields["reg_start"]);
		$pre_reg=strip_tags($fields["pre_reg"]);
		if($pre_reg==''){
			$pre_reg=$reg_start;
		}
		$reg_end=strip_tags($fields["reg_end"]);
		if($reg_end==''){
			$reg_end=$start;
		}
		$created=date_format(date_create(), 'Y-m-d H:i:s');
		$autoconfirm=(array_key_exists('autoconfirm', $data))?strip_tags($data['autoconfirm']):0;
		$age=strip_tags($fields["age"]);
		$restricted_age=strip_tags($fields["restricted_age"]);
		$restricted_text=$fields["restricted_text"];
		$regular_price=0;
		$regular_text='';
		$sponsor_price=-1;
		$sponsor_text='';
		$super_price=-1;
		$super_text='';
		//if price!=free, then update all prices
		switch(strip_tags($fields["ticket"])){
			case 'super':
				$super_price=strip_tags($fields["super_price"]);
				$super_text=$fields["super_text"];
			case 'sponsor':
				$sponsor_price=strip_tags($fields["sponsor_price"]);
				$sponsor_text=$fields["sponsor_text"];
			case 'regular':
				$regular_price=strip_tags($fields["regular_price"]);
				$regular_text=$fields["regular_text"];
			default:
				break;
		}
		//photo
		$file_name=null;
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
			if($img_param!==false){
				list($width, $height)=$img_param;
				if($width==$height&&$width==170){
					$target_file=$target_dir.$file_name.'.png';
					if(imagepng(imagecreatefromstring(file_get_contents($image['tmp_name'])), $target_file)){}
					else{
						return 'dThere was an error uploading the picture.';
					}
				}
				else{
					return 'dThe image is not 170x170px.';
				}
			}
			else{
				return 'dPlease choose only pictures to upload.';
			}
		}
		//create event, get event ID for accomodation creation
		$sql="INSERT INTO event(name, event_start, event_end, reg_start, pre_reg_start, reg_end, location, description, age, restricted_age, restricted_text, regular_price, regular_text, sponsor_price, sponsor_text, super_price, super_text, autoconfirm, img) VALUES (:name, :event_start, :event_end, :reg_start, :pre_reg_start, :reg_end, :location, :description, :age, :restricted_age, :restricted_text, :regular_price, :regular_text, :sponsor_price, :sponsor_text, :super_price, :super_text, :autoconfirm, :img)";
		$query=$this->db->prepare($sql);
		$query->execute(array(':name'=>$name, ':event_start'=>$start, ':event_end'=>$end, ':reg_start'=>$reg_start, ':pre_reg_start'=>$pre_reg, ':reg_end'=>$reg_end, ':location'=>$location, ':description'=>$description, 'age'=>$age, 'restricted_age'=>$restricted_age, 'restricted_text'=>$restricted_text, 'regular_price'=>$regular_price, ':regular_text'=>$regular_text, ':sponsor_text'=>$sponsor_text, ':super_text'=>$super_text, 'sponsor_price'=>$sponsor_price, 'super_price'=>$super_price, ':autoconfirm'=>$autoconfirm, ':img'=>$file_name));
		$event_ID=$this->db->lastInsertId();

		//ACCOMODATION
		$keys=preg_grep('/(type\d)+/m', array_keys($fields));
		if(!empty($keys)){
			foreach($keys as $key){
				//type#, persons#, price#, quantity#
				$id=substr($key, -1);
				$type=strip_tags($fields["type".$id]);
				$persons=strip_tags($fields["persons".$id]);
				$price=strip_tags($fields["price".$id]);
				$quantity=strip_tags($fields["quantity".$id]);

				//check if item (room) with these parameters already exists to prevent duplicate entries
				$sql_check='SELECT id FROM room WHERE type=:type AND persons=:persons AND price=:price';
				$query_check=$this->db->prepare($sql_check);
				$query_check->execute(array(':type'=>$type, ':persons'=>$persons, ':price'=>$price));
				//NEW ROOM
				$room_ID=null;
				if($query_check->rowCount()==0){
					$sql="INSERT INTO room(type, persons, price) VALUES (:type, :persons, :price)";
					$query=$this->db->prepare($sql);
					$query->execute(array(':type'=>$type, ':persons'=>$persons, ':price'=>$price));
					$room_ID=$this->db->lastInsertId();
				}
				//EXISTING ROOM
				else{
					$room_ID=$query_check->fetch()->id;
				}

				//EVENT_TO_ROOM
				$sql="INSERT INTO event_to_room(quantity, event_id, room_id) VALUES (:quantity, :event_id, :room_id)";
				$query=$this->db->prepare($sql);
				$query->execute(array(':quantity'=>$quantity, ':event_id'=>$event_ID, ':room_id'=>$room_ID));
			}
		}
		return "sEvent created!";
	}
	// Edit an event
	public function editEvent($id, $fields, $image){
	  $sql='SELECT * FROM account WHERE id=:id';
	  $query=$this->db->prepare($sql);
	  $query->execute(array(':id'=>$_SESSION['account']));
	  $account=$query->fetch();
	  if($account->status!=ADMIN){
	    //TODO report incident
	    return "dYou can't do that. This incident was reported.";
	  }

	  //EVENT
	  $id=strip_tags($id);
	  $name=strip_tags($fields["name"]);
	  $start=strip_tags($fields["start"]);
	  $end=strip_tags($fields["end"]);
	  $location=strip_tags($fields["location"]);
	  $description=$fields["description"];
	  $reg_start=strip_tags($fields["reg_start"]);
	  $pre_reg=strip_tags($fields["pre_reg"]);
	  if($pre_reg==''){
	    $pre_reg=$reg_start;
	  }
	  $reg_end=strip_tags($fields["reg_end"]);
	  if($reg_end==''){
	    $reg_end=$start;
	  }
	  $autoconfirm=(array_key_exists('autoconfirm', $data))?strip_tags($data['autoconfirm']):0;
	  $age=strip_tags($fields["age"]);
	  $restricted_age=strip_tags($fields["restricted_age"]);
	  $restricted_text=$fields["restricted_text"];
	  $regular_price=0;
	  $regular_text='';
	  $sponsor_price=-1;
	  $sponsor_text='';
	  $super_price=-1;
	  $super_text='';
	  //if price!=free, then update all prices
	  switch(strip_tags($fields["ticket"])){
	    case 'super':
	      $super_price=strip_tags($fields["super_price"]);
	      $super_text=$fields["super_text"];
	    case 'sponsor':
	      $sponsor_price=strip_tags($fields["sponsor_price"]);
	      $sponsor_text=$fields["sponsor_text"];
	    case 'regular':
	      $regular_price=strip_tags($fields["regular_price"]);
	      $regular_text=$fields["regular_text"];
	    default:
	      break;
	  }
	  //create event, get event ID for accomodation creation
	  $sql="UPDATE event SET name=:name, event_start=:event_start, event_end=:event_end, reg_start=:reg_start, pre_reg_start=:pre_reg_start, reg_end=:reg_end, location=:location, description=:description, age=:age, restricted_age=:restricted_age, restricted_text=:restricted_text, regular_price=:regular_price, regular_text=:regular_text, sponsor_price=:sponsor_price, sponsor_text=:sponsor_text, super_price=:super_price, super_text=:super_text, autoconfirm=:autoconfirm WHERE id=:id";
	  $query=$this->db->prepare($sql);
	  $query->execute(array(':name'=>$name, ':event_start'=>$start, ':event_end'=>$end, ':reg_start'=>$reg_start, ':pre_reg_start'=>$pre_reg, ':reg_end'=>$reg_end, ':location'=>$location, ':description'=>$description, 'age'=>$age, 'restricted_age'=>$restricted_age, 'restricted_text'=>$restricted_text, 'regular_price'=>$regular_price, ':regular_text'=>$regular_text, ':sponsor_text'=>$sponsor_text, ':super_text'=>$super_text, 'sponsor_price'=>$sponsor_price, 'super_price'=>$super_price, ':autoconfirm'=>$autoconfirm, ':id'=>$id));

		//IMAGE
		$file_name='';
		$target_dir='public/events/';
		if($image['size']!=0){
			while(true){
				$file_name=substr(bin2hex(random_bytes(32)), 0, 30);
				if(!file_exists($target_dir.$file_name.'.png')){
					break;
				}
			}
		}
		if($file_name!=''){
			$sql='SELECT img FROM event WHERE id=:id';
		  $query=$this->db->prepare($sql);
		  $query->execute(array(':id'=>$id));
		  $event=$query->fetch();
			$img_param=getimagesize($image['tmp_name']);
			if($img_param!==false){
				list($width, $height)=$img_param;
				if($width==$height){
					$target_file=$target_dir.$file_name.'.png';
					if(imagepng(imagecreatefromstring(file_get_contents($image['tmp_name'])), $target_file)){
						if(file_exists($target_dir.$event->img.'.png')){
							unlink($target_dir.$event->img.'.png');
						}
						$sql='UPDATE event SET img=:img WHERE id=:id';
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

	  //ACCOMODATION (TODO)
		//get all rooms for this event, compare for deleted items, update all others
	  return "sChanges saved!";
	}
	//Delete event photo
	public function deletePhoto($id){
		$sql='SELECT * FROM account WHERE id=:id';
	  $query=$this->db->prepare($sql);
	  $query->execute(array(':id'=>$_SESSION['account']));
	  $account=$query->fetch();
	  if($account->status!=ADMIN){
	    //TODO report incident
	    return "dYou can't do that. This incident was reported.";
	  }
		$id=strip_tags($id);
		$sql='SELECT img FROM event WHERE id=:id';
		$query=$this->db->prepare($sql);
		$query->execute(array(':id'=>$id));
		$event=$query->fetch();
		$target_dir='public/events/';
		if(file_exists($target_dir.$event->img.'.png')){
			unlink($target_dir.$event->img.'.png');
		}
		else{
			return 'dThere is no photo set for this event.';
		}
		$sql='UPDATE event SET img=null WHERE id=:id';
		$query=$this->db->prepare($sql);
		$query->execute(array(':id'=>$id));
		return 'sEvent photo successfully reset to default image.';
	}
	//Change confirmed status of Attendees for event ID
	public function editConfirm($event, $ids){
		$event=strip_tags($event);
		$sql='SELECT id FROM registration WHERE event_id=:id';
		$query=$this->db->prepare($sql);
		$query->execute(array(':id'=>$event));
		$attendees=$query->fetchAll();
		foreach($attendees as $id){
			$confirmed=(array_key_exists($id->id, $ids))?1:0;
			$sql="UPDATE registration SET confirmed=:confirmed WHERE id=:id";
		  $query=$this->db->prepare($sql);
		  $query->execute(array(':confirmed'=>$confirmed, ':id'=>$id->id));
		}
		return 'sConfirmed statuses updated successfully.';
	}
}
