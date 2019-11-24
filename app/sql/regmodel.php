<?php
class RegModel{
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

	/*
	 * DISPLAYING EVENTS
	*/

	// Get all usere's registered current events: id, event name, duration (start, end), reg end (changes possible until then). confirmed, fursuiter, artist
	public function getREvents(){
		$sql='SELECT registration.id AS id, name, event_start, event_end, reg_end, confirmed, fursuiter, artist, img, location, regular_price FROM event INNER JOIN registration ON event.id=registration.event_id WHERE ((event_start<=NOW() AND event_end>=NOW()) OR event_start>NOW()) AND acc_id=:acc_id ORDER BY event_start ASC';
		$query=$this->db->prepare($sql);
		$query->execute(array(':acc_id'=>$_SESSION['account']));
		return $query->fetchAll();
	}
	// Get all current/upcoming events
	public function getCEvents($all=false){
		$sql='SELECT * FROM event WHERE id NOT IN (SELECT event_id FROM registration INNER JOIN event ON event.id=registration.event_id AND acc_id=:acc_id) AND viewable<=NOW() AND event_end>=NOW() ORDER BY event_start ASC';
		if(isset($_SESSION['account'])){
			$sql2='SELECT * FROM account WHERE id=:id';
			$query=$this->db->prepare($sql2);
			$query->execute(array(':id'=>$_SESSION['account']));
			$account=$query->fetch();
			if($account->status>=STAFF){
				$sql='SELECT * FROM event WHERE id NOT IN (SELECT event_id FROM registration INNER JOIN event ON event.id=registration.event_id AND acc_id=:acc_id) AND event_end>=NOW() ORDER BY event_start ASC';
			}
		}
		$query=$this->db->prepare($sql);
		if(isset($_SESSION['account'])&&!$all){
			$query->execute(array(':acc_id'=>$_SESSION['account']));
		}
		else{
			$query->execute(array(':acc_id'=>null));
		}
		return $query->fetchAll();
	}
	// Get all past events
	public function getPEvents(){
		$sql='SELECT * FROM event WHERE event_end<NOW() ORDER BY event_end ASC';
		$query=$this->db->prepare($sql);
		$query->execute();
		return $query->fetchAll();
	}

	/*
	 * NEW REGISTRATION
	*/

	//Get details for selected event
	public function newReg($id){
		$sql='SELECT * FROM event WHERE id=:id';
		$query=$this->db->prepare($sql);
		$query->execute(array(':id'=>$id));
		return $query->fetch();
	}
	//Get all rooms for given event_id
	public function getAccomodation($id){
		$sql='SELECT room.id as id, type, persons, price, quantity FROM room INNER JOIN event_to_room ON room.id=event_to_room.room_id WHERE event_id=:id';
		$query=$this->db->prepare($sql);
		$query->execute(array(':id'=>$id));
		return $query->fetchAll();
	}
	//Get number of booked rooms for given event_id and room_id
	public function getBooked($eid, $rid){
		$sql='SELECT count(id) as quantity FROM registration WHERE event_id=:eid AND room_id=:rid';
		$query=$this->db->prepare($sql);
		$query->execute(array(':eid'=>$eid, ':rid'=>$rid));
		return $query->fetch();
	}
	//id=event id, data=null or [ticket selection and/or room selection and/or fursuiter and/or artist]
	public function doReg($id, $data){
		$event=$this->newReg($id);
		//if not subscribed to newsletter and ticked to subscribe, do this here
		if(array_key_exists('newsletter', $data)){
			$sql='UPDATE account SET newsletter=1 WHERE id=:id';
			$query=$this->db->prepare($sql);
			$query->execute(array(':id'=>$_SESSION['account']));
		}
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
		$notes=strip_tags($data['notes']);
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
		$sql='INSERT INTO registration(event_id, acc_id, room_id, ticket, confirmed, fursuiter, artist, created, room_confirmed, notes) VALUES (:event_id, :acc_id, :room_id, :ticket, :confirmed, :fursuiter, :artist, :created, :room_confirmed, :notes)';
		$query=$this->db->prepare($sql);
		$query->execute(array(':event_id'=>$id, ':acc_id'=>$_SESSION['account'], ':room_id'=>$room, ':ticket'=>$ticket, ':confirmed'=>$event->autoconfirm, ':fursuiter'=>$fursuiter, ':artist'=>$artist, ':created'=>$created, ':room_confirmed'=>$room_confirmed, ':notes'=>$notes));

		//registration confirmation email
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
			$this->changes($_SESSION['account'], "registered for an event ID $id", $_SESSION['account']);
			return L::alerts_s_regSucc;
		}
		else{
			$confirmed=L::register_model_manual;
			require 'app/emails/event_confirmation.php'; //$event_name, $username, $url (edit url), $recap, $bad_news, $email
			$this->changes($_SESSION['account'], "registered for an event ID $id", $_SESSION['account']);
			return L::alerts_s_regManual;
		}
	}

	/*
	 * EDIT EVENT REGISTRATIONS
	*/

	//Get details for selected event
	public function existingReg($id, $all=false){
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
		$event=$this->existingReg($id);
		//check if can edit reg (reg end)
		if(new DateTime($event->reg_end)>=new DateTime()){
			return L::alerts_d_noModeEdit;
		}
		$ticket=(array_key_exists('ticket', $data))?strip_tags($data['ticket']):'regular';
		$room=(array_key_exists('room', $data))?($data['room']!=0)?strip_tags($data['room']):null:null;
		$notes=strip_tags($data['notes']);
		$fursuiter=(array_key_exists('fursuit', $data))?strip_tags($data['fursuit']):0;
		$artist=(array_key_exists('artist', $data))?strip_tags($data['artist']):0;

		//if room has changed
		$room_confirmed=1;
		if($room!=$event->room_id){
			//a room is selected, calculate availability for this room
			if($room!=null){
				$sql='SELECT room.id as id, type, price, quantity FROM room INNER JOIN event_to_room ON room.id=event_to_room.room_id WHERE room.id=:id';
				$query=$this->db->prepare($sql);
				$query->execute(array(':id'=>$room));
				$room_selected=$query->fetch();
				$result=$room_selected->quantity-$this->getBooked($event->event_id, $room_selected->id)->quantity;
				$room_confirmed=($result>0)?1:0;
			}
			//give previous room to next in waitlist if room was confirmed
			if($event->room_confirmed==1){
				$sql='UPDATE registration SET room_confirmed=1 WHERE room_id=:room AND event_id=:event AND room_confirmed=0 ORDER BY created ASC LIMIT 1';
				$query=$this->db->prepare($sql);
				$query->execute(array(':room'=>$event->room_id, ':event'=>$event->event_id));
			}
		}
		//update the registration
		$sql='UPDATE registration SET room_id=:room_id, room_confirmed=:room_confirmed, ticket=:ticket, fursuiter=:fursuiter, artist=:artist, notes=:notes WHERE id=:id';
		$query=$this->db->prepare($sql);
		$query->execute(array(':room_id'=>$room, ':room_confirmed'=>$room_confirmed, ':ticket'=>$ticket, ':fursuiter'=>$fursuiter, ':artist'=>$artist, ':notes'=>$notes, ':id'=>$id));
		$this->changes($_SESSION['account'], "edited their registration data for event ID $id", $_SESSION['account']);
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
	//convert dates for cards (home page, events page)
	public function convertCard($date, $singleDay){
		$date=strtotime($date);
		$text='';
		$d=date('j', $date);
		$m=date('n', $date);
		$y=date('Y', $date);
		$mWord=date('M', $date);
		$min=date('i', $date);
		$hr=date('G', $date);
		$dayOfWeek=date('w', $date);
		switch($dayOfWeek){
			case '0':
				$text.=L::register_view_date_sun;
				break;
			case '1':
				$text.=L::register_view_date_mon;
				break;
			case '2':
				$text.=L::register_view_date_tue;
				break;
			case '3':
				$text.=L::register_view_date_wed;
				break;
			case '4':
				$text.=L::register_view_date_thu;
				break;
			case '5':
				$text.=L::register_view_date_fri;
				break;
			case '6':
				$text.=L::register_view_date_sat;
		}
		if($_SESSION['lang']=='si'){
			$text.=', '.$d.'.'.$m.'.'.$y.', '.$hr.':'.$min;
		}
		else{
			$text.=', '.$mWord.' '.$d.' '.$y.', '.$hr.':'.$min;
		}
		if(!$singleDay){
			$text.=' '.L::register_view_date_multiple;
		}
		return $text;
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
		$sql='SELECT id FROM event WHERE id=:id';
		$query=$this->db->prepare($sql);
		$query->execute(array(':id'=>$id));
		return $query->rowCount()!=0;
	}
	public function viewable($id){
		if(isset($_SESSION['account'])){
			$sql='SELECT * FROM account WHERE id=:id';
			$query=$this->db->prepare($sql);
			$query->execute(array(':id'=>$_SESSION['account']));
			$account=$query->fetch();
			if($account->status>=PRE_REG){
				return true;
			}
		}
		$sql='SELECT count(viewable) AS num FROM event WHERE id=:id AND viewable<=NOW()';
		$query=$this->db->prepare($sql);
		$query->execute(array(':id'=>$id));
		$event=$query->fetch();
		$event=$event->num;
		return $event==1;
	}
	//Get nr of attendees from each country
	public function getCountries($id){
		$sql='SELECT country, COUNT(country) AS counter FROM account INNER JOIN registration ON account.id=registration.acc_id WHERE event_id=:id GROUP BY country ORDER BY counter DESC';
		$query=$this->db->prepare($sql);
		$query->execute(array(':id'=>$id));
		return $query->fetchAll();
	}
	//Get nr of tickets by category
	public function getTickets($id){
		$sql='SELECT ticket, COUNT(ticket) AS counter FROM registration WHERE event_id=:id GROUP BY ticket ORDER BY counter DESC';
		$query=$this->db->prepare($sql);
		$query->execute(array(':id'=>$id));
		return $query->fetchAll();
	}
	//Get nr of attendees by gender
	public function getGenders($id){
		$sql='SELECT gender, COUNT(gender) AS counter FROM account INNER JOIN registration ON account.id=registration.acc_id WHERE event_id=:id GROUP BY gender ORDER BY counter DESC';
		$query=$this->db->prepare($sql);
		$query->execute(array(':id'=>$id));
		return $query->fetchAll();
	}
	//Get nr of attendees by room selection
	public function getRooms($id){
		$sql='SELECT type, COUNT(type) AS counter FROM room INNER JOIN registration ON room.id=registration.room_id WHERE event_id=:id GROUP BY type ORDER BY counter DESC';
		$query=$this->db->prepare($sql);
		$query->execute(array(':id'=>$id));
		return $query->fetchAll();
	}
	//Get nr of attendees that selected no room
	public function getNoRoom($id){
		$sql='SELECT COUNT(*) AS counter FROM registration WHERE event_id=:id AND room_id IS NULL';
		$query=$this->db->prepare($sql);
		$query->execute(array(':id'=>$id));
		return $query->fetch();
	}
	//Get attendees registered for event
	public function getAttendees($id){
		$sql='SELECT username, pfp, fursuiter, artist, ticket FROM account INNER JOIN registration ON registration.acc_id=account.id WHERE event_id=:id AND confirmed=1 ORDER BY ticket DESC, username ASC';
		$query=$this->db->prepare($sql);
		$query->execute(array(':id'=>$id));
		return $query->fetchAll();
	}

	//Get fursuits for confirmed registrations
	//Get fursuits that have in_use=1 and on this event ID...
	public function getFursuits($id){
		$sql='SELECT username, name, animal, img FROM fursuit INNER JOIN account ON fursuit.acc_id=account.id INNER JOIN registration ON registration.acc_id=account.id WHERE event_id=:id AND in_use=1 AND confirmed=1 ORDER BY name';
		$query=$this->db->prepare($sql);
		$query->execute(array(':id'=>$id));
		return $query->fetchAll();
	}

	/*
	 * CAR SHARING
	*/

	//Get car shares for this event (to event)
	public function getAllTo($id){
		$sql='SELECT car_share.id as id, price, description, outbound, direction, passengers, username, account.id as accId FROM car_share INNER JOIN account ON account.id=owner WHERE event_id=:id AND direction=0 ORDER BY outbound ASC';
		$query=$this->db->prepare($sql);
		$query->execute(array(':id'=>$id));
		return $query->fetchAll();
	}
	//Get car shares for this event (from event)
	public function getAllFrom($id){
		$sql='SELECT car_share.id as id, price, description, outbound, direction, passengers, username, account.id as accId FROM car_share INNER JOIN account ON account.id=owner WHERE event_id=:id AND direction=1 ORDER BY outbound ASC';
		$query=$this->db->prepare($sql);
		$query->execute(array(':id'=>$id));
		return $query->fetchAll();
	}
	//Get selected car share
	public function getCarShare($id){
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
		$sql='INSERT INTO car_share(price, description, outbound, direction, passengers, event_id, owner) VALUES (:price, :description, :outbound, :direction, :passengers, :event_id, :owner)';
		$query=$this->db->prepare($sql);
		$query->execute(array(':price'=>$price, ':description'=>$description, ':outbound'=>$outbound, ':direction'=>$direction, ':passengers'=>$passengers, ':event_id'=>$id, 'owner'=>$_SESSION['account']));
		$sql="INSERT INTO changes(who, what, for_who, changed_at) VALUES (:who, :what, :for_who, :changed_at)";
		$query=$this->db->prepare($sql);
		$query->execute(array(':who'=>$_SESSION['account'], ':what'=>"created a car share on event ID $id", ':for_who'=>$_SESSION['account'], ':changed_at'=>date_format(date_create(), 'Y-m-d H:i:s')));
	}
	//edit existing car share
	public function editCarShare($id, $direction, $passengers, $outbound, $price, $description){
		//check if user is the owner of the car share
		$sql='SELECT * FROM car_share WHERE id=:id AND owner=:acc_id';
		$query=$this->db->prepare($sql);
		$query->execute(array(':id'=>$id, ':acc_id'=>$_SESSION['account']));
		if($query->rowCount()==0){
			return L::alerts_d_cantDoThat;
		}
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
