<?php
class RegModel{
	// Database
	function __construct($db){
		try{
			$this->db=$db;
		}
		catch (PDOException $e){
			exit('Database connection could not be established.');
		}
	}

	/*
	 * DISPLAYING EVENTS
	*/

	// Get all usere's registered current events: id, event name, duration (start, end), reg end (changes possible until then). confirmed, fursuiter, artist
	public function getREvents(){
		$sql='SELECT registration.id AS id, name, event_start, event_end, reg_end, confirmed, fursuiter, artist FROM event INNER JOIN registration ON event.id=registration.event_id WHERE ((event_start<=NOW() AND event_end>=NOW()) OR event_start>NOW()) AND acc_id=:acc_id ORDER BY event_start ASC';
		$query=$this->db->prepare($sql);
		$query->execute(array(':acc_id'=>$_SESSION['account']));
		return $query->fetchAll();
	}
	// Get all current/upcoming events
	public function getCEvents(){
		$sql='SELECT * FROM event WHERE id NOT IN (SELECT event_id FROM registration INNER JOIN event ON event.id=registration.event_id AND acc_id=:acc_id) ORDER BY event_start ASC';
		$query=$this->db->prepare($sql);
		$query->execute(array(':acc_id'=>$_SESSION['account']));
		return $query->fetchAll();
	}
	// Get all past events
	public function getPEvents(){
		$sql='SELECT * FROM event INNER JOIN registration ON event.id=registration.event_id WHERE event_end<NOW() AND acc_id=:id ORDER BY event_end ASC';
		$query=$this->db->prepare($sql);
		$query->execute(array(':id'=>$_SESSION['account']));
		return $query->fetchAll();
	}

	/*
	 * NEW REGISTRATION
	*/

	//Get details for selected event
	public function newReg($id){
		$id=strip_tags($id);
		$sql='SELECT * FROM event WHERE id=:id';
		$query=$this->db->prepare($sql);
		$query->execute(array(':id'=>$id));
		return $query->fetch();
	}
	//Get all rooms for given event_id
	public function getAccomodation($id){
		$id=strip_tags($id);
		$sql='SELECT room.id as id, type, persons, price, quantity FROM room INNER JOIN event_to_room ON room.id=event_to_room.room_id WHERE event_id=:id';
		$query=$this->db->prepare($sql);
		$query->execute(array(':id'=>$id));
		return $query->fetchAll();
	}
	//Get number of booked rooms for given event_id and room_id
	public function getBooked($eid, $rid){
		$eid=strip_tags($eid);
		$rid=strip_tags($rid);
		$sql='SELECT count(id) as quantity FROM registration WHERE event_id=:eid AND room_id=:rid';
		$query=$this->db->prepare($sql);
		$query->execute(array(':eid'=>$eid, ':rid'=>$rid));
		return $query->fetch();
	}
	//id=event id, data=null or [ticket selection and/or room selection and/or fursuiter and/or artist]
	public function doReg($id, $data){
		$id=strip_tags($id);
		$event=$this->newReg($id);
		//check if can reg (bday, reg dates)
		$sql='SELECT dob, status FROM account WHERE id=:id';
		$query=$this->db->prepare($sql);
		$query->execute(array(':id'=>$_SESSION['account']));
		$account=$query->fetch();
		$age=(int)date_diff(date_create($event->event_start), date_create($account->dob), true)->format('%y');
		if($age<$event->restricted_age){
			return "dYou aren't old enough to register for this event.";
		}
		$now=new DateTime();
		if(new DateTime($event->reg_end)<=$now){
			return "dYou can't register for this event any longer.";
		}
		elseif((new DateTime($event->reg_start)>$now && $account->status<PRE_REG) || ($event->pre_reg_start!=0 && new DateTime($event->pre_reg_start)>$now && $account->status>=PRE_REG) ){
			return "dYou can't register for this event yet.";
		}
		$ticket=(array_key_exists('ticket', $data))?strip_tags($data['ticket']):'regular';
		$room=(array_key_exists('room', $data))?($data['room']!=0)?strip_tags($data['room']):null:null;
		$fursuiter=(array_key_exists('fursuit', $data))?strip_tags($data['fursuit']):0;
		$artist=(array_key_exists('artist', $data))?strip_tags($data['artist']):0;
		$created=date_format(date_create(), 'Y-m-d H:i:s');
		$sql='INSERT INTO registration(event_id, acc_id, room_id, ticket, confirmed, fursuiter, artist, created) VALUES (:event_id, :acc_id, :room_id, :ticket, :confirmed, :fursuiter, :artist, :created)';
		$query=$this->db->prepare($sql);
		$query->execute(array(':event_id'=>$id, ':acc_id'=>$_SESSION['account'], ':room_id'=>$room, ':ticket'=>$ticket, ':confirmed'=>$event->autoconfirm, ':fursuiter'=>$fursuiter, ':artist'=>$artist, ':created'=>$created));
		if($event->autoconfirm==1){
			return "sYour registration was successfull and is confirmed.";
		}
		else{
			return "sYour registration was successfull and is awaiting confirmation.";
		}
	}

	/*
	 * EDIT EVENT REGISTRATIONS
	*/

	//Get details for selected event
	public function existingReg($id, $all=false){
		$id=strip_tags($id);
		$sql='SELECT * FROM event INNER JOIN registration ON event.id=registration.event_id WHERE registration.id=:id';
		$query=$this->db->prepare($sql);
		$query->execute(array(':id'=>$id));
		if($all){
			return $query->fetchAll();
		}
		return $query->fetch();
	}

	//Edit existing registration
	public function editReg($id, $data){
		$id=strip_tags($id);
		$event=$this->existingReg($id);
		//check if can edit reg (reg end)
		if(new DateTime($event->reg_end)<=new DateTime()){
			return "dYou can't edit your registration for this event any longer.";
		}
		$ticket=(array_key_exists('ticket', $data))?strip_tags($data['ticket']):'regular';
		$room=(array_key_exists('room', $data))?($data['room']!=0)?strip_tags($data['room']):null:null;
		$fursuiter=(array_key_exists('fursuit', $data))?strip_tags($data['fursuit']):0;
		$artist=(array_key_exists('artist', $data))?strip_tags($data['artist']):0;
		$sql='UPDATE registration SET room_id=:room_id, ticket=:ticket, fursuiter=:fursuiter, artist=:artist WHERE id=:id';
		$query=$this->db->prepare($sql);
		$query->execute(array(':room_id'=>$room, ':ticket'=>$ticket, ':fursuiter'=>$fursuiter, ':artist'=>$artist, ':id'=>$id));
		return "sYour event registration data was successfully updated.";
	}

	/*
	 * CHECKING DATA
	*/

	//how=true: just date; how=false: just hours
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
	//true=complete
	public function checkProfile(){
		$sql='SELECT id FROM account WHERE id=:id AND fname IS NULL';
		$query=$this->db->prepare($sql);
		$query->execute(array(':id'=>$_SESSION['account']));
		return $query->rowCount()==0;
	}
	// Check if account has already registered for event
	public function registered($id, $type_id){
		$id=strip_tags($id);
		$type_id=strip_tags($type_id);
		$sql='SELECT id FROM registration WHERE acc_id=:id AND '.$type_id.'=:type_id';
		$query=$this->db->prepare($sql);
		$query->execute(array(':id'=>$_SESSION['account'], ':type_id'=>$id));
		return $query->rowCount()==0;
	}
	// Check if event exists
	public function exists($id){
		$id=strip_tags($id);
		$sql='SELECT id FROM event WHERE id=:id';
		$query=$this->db->prepare($sql);
		$query->execute(array(':id'=>$id));
		return $query->rowCount()!=0;
	}
}
