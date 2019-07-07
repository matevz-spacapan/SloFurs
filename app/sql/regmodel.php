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
		$sql='SELECT * FROM event WHERE event_end<NOW() ORDER BY event_end ASC';
		$query=$this->db->prepare($sql);
		$query->execute();
		return $query->fetchAll();
	}

	/*
	 * NEW REGISTRATION
	*/

	//Get details for selected event
	public function newEvent($id){
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
	public function getAvailable($eid, $rid){
		$eid=strip_tags($eid);
		$rid=strip_tags($rid);
		$sql='SELECT count(id) as quantity FROM registration WHERE event_id=:eid AND room_id=:rid';
		$query=$this->db->prepare($sql);
		$query->execute(array(':eid'=>$eid, ':rid'=>$rid));
		return $query->fetch();
	}

	/*
	 * CHECKING DATA
	*/

	//how=true: just date; how=false: just hours
	public function convertViewable($date, $how){
		return ($how)?date_format(new DateTime($date),"d.m.Y"):date_format(new DateTime($date),"H:i");
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