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
		$sql='SELECT registration.id AS id, name, event_start, event_end, reg_end, confirmed, fursuiter, artist, img FROM event INNER JOIN registration ON event.id=registration.event_id WHERE ((event_start<=NOW() AND event_end>=NOW()) OR event_start>NOW()) AND acc_id=:acc_id ORDER BY event_start ASC';
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
		$sql='SELECT dob, status, username, email FROM account WHERE id=:id';
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
		$room=(array_key_exists('room', $data)&&$data['room']!=0)?strip_tags($data['room']):null;
		$fursuiter=(array_key_exists('fursuit', $data))?strip_tags($data['fursuit']):0;
		$artist=(array_key_exists('artist', $data))?strip_tags($data['artist']):0;
		$created=date_format(date_create(), 'Y-m-d H:i:s');
		//calculate if room is available and write result in db
		$room_confirmed=1;
		if($room!=null){
			$sql='SELECT room.id as id, type, price, quantity FROM room INNER JOIN event_to_room ON room.id=event_to_room.room_id WHERE room.id=:id';
			$query=$this->db->prepare($sql);
			$query->execute(array(':id'=>$room));
			$room_selected=$query->fetch();
			$result=$room_selected->quantity-$this->getBooked($event->id, $room_selected->id)->quantity;
			$room_confirmed=($result>0)?1:0;
		}
		$sql='INSERT INTO registration(event_id, acc_id, room_id, ticket, confirmed, fursuiter, artist, created, room_confirmed) VALUES (:event_id, :acc_id, :room_id, :ticket, :confirmed, :fursuiter, :artist, :created, :room_confirmed)';
		$query=$this->db->prepare($sql);
		$query->execute(array(':event_id'=>$id, ':acc_id'=>$_SESSION['account'], ':room_id'=>$room, ':ticket'=>$ticket, ':confirmed'=>$event->autoconfirm, ':fursuiter'=>$fursuiter, ':artist'=>$artist, ':created'=>$created, ':room_confirmed'=>$room_confirmed));
		//form confirmation email
		$event_ID=$this->db->lastInsertId();
		$event_name=$event->name;
		$username=$account->username;
		$email=$account->email;
		$url=URL.'register/edit?id='.$event_ID;
		$attendance='Attendance fee: ';
		if($event->regular_price==0){
			$attendance=$attendance.' Free.';
		}
		else{
			switch($ticket){
				case 'regular':
					$attendance=$attendance.$event->regular_price.'€.';
					break;
				case 'sponsor':
					$attendance=$attendance.$event->sponsor_price.'€.';
					break;
				case 'super':
					$attendance=$attendance.$event->super_price.'€.';
			}
		}
		$accomodation='Accomodation selection and price: ';
		if($room==null){
			$accomodation=$accomodation.'No accomodation (0€).';
		}
		else{
			$accomodation=$accomodation.$room_selected->type.' ('.$room_selected->price.'€).';
		}
		$bad_news='';
		if($room_confirmed==0){
			$bad_news='Unfortunately your selected room was not available any longer at the time when your registration was processed. You have been put on the wait-list on a first-come, first-served basis. If someone else cancels their room spot and you are next up on the list, you will take their spot. We will email you if this happens.';
		}
		if($event->autoconfirm==1){
			$confirmed='Your registration is now complete and confirmed by us. We are looking forward to seeing you soon!';
			require 'app/emails/event_confirmation.php'; //$event_name, $username, $url (edit url), $recap, $bad_news, $email
			return "sYour registration was successfull and is confirmed.";
		}
		else{
			$confirmed='Your registration is now complete, but will have to be manually checked by our staff. We are looking forward to seeing you soon!';
			require 'app/emails/event_confirmation.php'; //$event_name, $username, $url (edit url), $recap, $bad_news, $email
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
