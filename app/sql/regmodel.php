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
	public function getCEvents($fromHome=false){
		$sql='SELECT * FROM event WHERE id NOT IN (SELECT event_id FROM registration INNER JOIN event ON event.id=registration.event_id AND acc_id=:acc_id) ORDER BY event_start ASC';
		$query=$this->db->prepare($sql);
		if(isset($_SESSION['account'])&&!$fromHome){
			$query->execute(array(':acc_id'=>$_SESSION['account']));
		}
		else{
			$query->execute(array(':acc_id'=>null));
		}
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
			return L::alerts_d_tooYoung;
		}
		$now=new DateTime();
		if(new DateTime($event->reg_end)<=$now){
			return L::alerts_d_noMore;
		}
		elseif((new DateTime($event->reg_start)>$now && $account->status<PRE_REG) || ($event->pre_reg_start!=0 && new DateTime($event->pre_reg_start)>$now && $account->status>=PRE_REG) ){
			return L::alerts_d_notYet;
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
		$attendance=L::register_model_attendance.': ';
		if($event->regular_price==0){
			$attendance=$attendance.' '.L::register_model_free.'.';
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
		$accomodation=L::register_model_accomodation.': ';
		if($room==null){
			$accomodation=$accomodation.L::register_model_noAccomodation.' (0€).';
		}
		else{
			$accomodation=$accomodation.$room_selected->type.' ('.$room_selected->price.'€).';
		}
		$bad_news='';
		if($room_confirmed==0){
			$bad_news=L::register_model_notAvailable;
		}
		if($event->autoconfirm==1){
			$confirmed=L::register_model_confirmed;
			require 'app/emails/event_confirmation.php'; //$event_name, $username, $url (edit url), $recap, $bad_news, $email
			$sql="INSERT INTO changes(who, what, for_who, changed_at) VALUES (:who, :what, :for_who, :changed_at)";
			$query=$this->db->prepare($sql);
			$query->execute(array(':who'=>$_SESSION['account'], ':what'=>"registered for an event ID $id", ':for_who'=>$_SESSION['account'], ':changed_at'=>date_format(date_create(), 'Y-m-d H:i:s')));
			return L::alerts_s_regSucc;
		}
		else{
			$confirmed=L::register_model_manual;
			require 'app/emails/event_confirmation.php'; //$event_name, $username, $url (edit url), $recap, $bad_news, $email
			$sql="INSERT INTO changes(who, what, for_who, changed_at) VALUES (:who, :what, :for_who, :changed_at)";
			$query=$this->db->prepare($sql);
			$query->execute(array(':who'=>$_SESSION['account'], ':what'=>"registered for an event ID $id", ':for_who'=>$_SESSION['account'], ':changed_at'=>date_format(date_create(), 'Y-m-d H:i:s')));
			return L::alerts_s_regManual;
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
			return L::alerts_d_noModeEdit;
		}
		$ticket=(array_key_exists('ticket', $data))?strip_tags($data['ticket']):'regular';
		$room=(array_key_exists('room', $data))?($data['room']!=0)?strip_tags($data['room']):null:null;
		$fursuiter=(array_key_exists('fursuit', $data))?strip_tags($data['fursuit']):0;
		$artist=(array_key_exists('artist', $data))?strip_tags($data['artist']):0;
		$sql='UPDATE registration SET room_id=:room_id, ticket=:ticket, fursuiter=:fursuiter, artist=:artist WHERE id=:id';
		$query=$this->db->prepare($sql);
		$query->execute(array(':room_id'=>$room, ':ticket'=>$ticket, ':fursuiter'=>$fursuiter, ':artist'=>$artist, ':id'=>$id));
		$sql="INSERT INTO changes(who, what, for_who, changed_at) VALUES (:who, :what, :for_who, :changed_at)";
		$query=$this->db->prepare($sql);
		$query->execute(array(':who'=>$_SESSION['account'], ':what'=>"edited their registration data for event ID $id", ':for_who'=>$_SESSION['account'], ':changed_at'=>date_format(date_create(), 'Y-m-d H:i:s')));
		return L::alerts_s_evtSuccUpdate;
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
	// Convert MySQL datetime to HTML datetime
	public function convert($date){
		return date_format(new DateTime($date),"Y-m-d\TH:i");
	}
	//true=complete
	public function checkProfile(){
		$sql='SELECT id FROM account WHERE id=:id AND fname IS NULL';
		$query=$this->db->prepare($sql);
		$query->execute(array(':id'=>$_SESSION['account']));
		return $query->rowCount()==0;
	}
	// Check if account has already registered for event
	public function registered($id, $type_id, $return_type=true){
		$id=strip_tags($id);
		$type_id=strip_tags($type_id);
		$sql='SELECT id FROM registration WHERE acc_id=:id AND '.$type_id.'=:type_id';
		$query=$this->db->prepare($sql);
		$query->execute(array(':id'=>$_SESSION['account'], ':type_id'=>$id));
		if($return_type){
			return $query->rowCount()==0;
		}
		else{
			return $query->fetch();
		}
	}
	// Check if event exists
	public function exists($id){
		$id=strip_tags($id);
		$sql='SELECT id FROM event WHERE id=:id';
		$query=$this->db->prepare($sql);
		$query->execute(array(':id'=>$id));
		return $query->rowCount()!=0;
	}
	//Get nr of attendees from each country
	public function getCountries($id){
		$id=strip_tags($id);
		$sql='SELECT country, COUNT(country) AS counter FROM account INNER JOIN registration ON account.id=registration.acc_id WHERE event_id=:id GROUP BY country';
		$query=$this->db->prepare($sql);
		$query->execute(array(':id'=>$id));
		return $query->fetchAll();
	}
	//Get nr of tickets by category
	public function getTickets($id){
		$id=strip_tags($id);
		$sql='SELECT ticket, COUNT(ticket) AS counter FROM registration WHERE event_id=:id GROUP BY ticket';
		$query=$this->db->prepare($sql);
		$query->execute(array(':id'=>$id));
		return $query->fetchAll();
	}
	//Get nr of attendees by gender
	public function getGenders($id){
		$id=strip_tags($id);
		$sql='SELECT gender, COUNT(gender) AS counter FROM account INNER JOIN registration ON account.id=registration.acc_id WHERE event_id=:id GROUP BY gender';
		$query=$this->db->prepare($sql);
		$query->execute(array(':id'=>$id));
		return $query->fetchAll();
	}
	//Get nr of attendees by room selection
	public function getRooms($id){
		$id=strip_tags($id);
		$sql='SELECT type, COUNT(type) AS counter FROM room INNER JOIN registration ON room.id=registration.room_id WHERE event_id=:id GROUP BY type';
		$query=$this->db->prepare($sql);
		$query->execute(array(':id'=>$id));
		return $query->fetchAll();
	}
	//Get nr of attendees that selected no room
	public function getNoRoom($id){
		$id=strip_tags($id);
		$sql='SELECT COUNT(*) AS counter FROM registration WHERE event_id=:id AND room_id IS NULL';
		$query=$this->db->prepare($sql);
		$query->execute(array(':id'=>$id));
		return $query->fetch();
	}
	//Get attendees registered for event
	public function getAttendees($id){
		$id=strip_tags($id);
		$sql='SELECT username, pfp, fursuiter, artist, ticket FROM account INNER JOIN registration ON registration.acc_id=account.id WHERE event_id=:id ORDER BY username';
		$query=$this->db->prepare($sql);
		$query->execute(array(':id'=>$id));
		return $query->fetchAll();
	}

	/*
	 * CAR SHARING
	*/

	//Get car shares for this event
	public function getAllCarShares($id){
		$id=strip_tags($id);
		$sql='SELECT car_share.id as id, price, description, outbound, direction, passengers, username, account.id as accId FROM car_share INNER JOIN account ON account.id=owner WHERE event_id=:id';
		$query=$this->db->prepare($sql);
		$query->execute(array(':id'=>$id));
		return $query->fetchAll();
	}
	//Get selected car share
	public function getCarShare($id){
		$id=strip_tags($id);
		$sql='SELECT * FROM car_share WHERE id=:id';
		$query=$this->db->prepare($sql);
		$query->execute(array(':id'=>$id));
		return $query->fetch();
	}
	//insert new car share
	public function newCarShare($id, $direction, $passengers, $outbound, $price, $description){
		//check if user is registered for event
		$sql='SELECT * FROM registration WHERE acc_id=:id';
		$query=$this->db->prepare($sql);
		$query->execute(array(':id'=>$_SESSION['account']));
		if($query->rowCount()==0){
			return L::alerts_d_reggedForEvt;
		}
		$id=strip_tags($id);
		$direction=strip_tags($direction);
		$passengers=strip_tags($passengers);
		$outbound=strip_tags($outbound);
		$price=strip_tags($price);
		$description=strip_tags($description);
		$sql='INSERT INTO car_share(price, description, outbound, direction, passengers, event_id, owner) VALUES (:price, :description, :outbound, :direction, :passengers, :event_id, :owner)';
		$query=$this->db->prepare($sql);
		$query->execute(array(':price'=>$price, ':description'=>$description, ':outbound'=>$outbound, ':direction'=>$direction, ':passengers'=>$passengers, ':event_id'=>$id, 'owner'=>$_SESSION['account']));
		$sql="INSERT INTO changes(who, what, for_who, changed_at) VALUES (:who, :what, :for_who, :changed_at)";
		$query=$this->db->prepare($sql);
		$query->execute(array(':who'=>$_SESSION['account'], ':what'=>"created a car share on event ID $id", ':for_who'=>$_SESSION['account'], ':changed_at'=>date_format(date_create(), 'Y-m-d H:i:s')));
	}
	//edit existing car share
	public function editCarShare($id, $direction, $passengers, $outbound, $price, $description){
		$id=strip_tags($id);
		//check if user is the owner of the car share
		$sql='SELECT * FROM car_share WHERE id=:id AND owner=:acc_id';
		$query=$this->db->prepare($sql);
		$query->execute(array(':id'=>$id, ':acc_id'=>$_SESSION['account']));
		if($query->rowCount()==0){
			return L::alerts_d_cantDoThat;
		}
		$direction=strip_tags($direction);
		$passengers=strip_tags($passengers);
		$outbound=strip_tags($outbound);
		$price=strip_tags($price);
		$description=strip_tags($description);
		$sql='UPDATE car_share SET price=:price, description=:description, outbound=:outbound, direction=:direction, passengers=:passengers WHERE id=:id';
		$query=$this->db->prepare($sql);
		$query->execute(array(':price'=>$price, ':description'=>$description, ':outbound'=>$outbound, ':direction'=>$direction, ':passengers'=>$passengers, ':id'=>$id));
		$sql="INSERT INTO changes(who, what, for_who, changed_at) VALUES (:who, :what, :for_who, :changed_at)";
		$query=$this->db->prepare($sql);
		$query->execute(array(':who'=>$_SESSION['account'], ':what'=>"edited a car share ID $id", ':for_who'=>$_SESSION['account'], ':changed_at'=>date_format(date_create(), 'Y-m-d H:i:s')));
		return L::alerts_s_saved;
	}
	//edit existing car share
	public function deleteCarShare($id){
		$id=strip_tags($id);
		//check if user is the owner of the car share
		$sql='SELECT * FROM car_share WHERE id=:id AND owner=:acc_id';
		$query=$this->db->prepare($sql);
		$query->execute(array(':id'=>$id, ':acc_id'=>$_SESSION['account']));
		if($query->rowCount()==0){
			return L::alerts_d_cantDoThat;
		}
		$sql='DELETE FROM car_share WHERE id=:id';
		$query=$this->db->prepare($sql);
		$query->execute(array(':id'=>$id));
		$sql="INSERT INTO changes(who, what, for_who, changed_at) VALUES (:who, :what, :for_who, :changed_at)";
		$query=$this->db->prepare($sql);
		$query->execute(array(':who'=>$_SESSION['account'], ':what'=>"deleted a car share ID $id", ':for_who'=>$_SESSION['account'], ':changed_at'=>date_format(date_create(), 'Y-m-d H:i:s')));
	}
}
