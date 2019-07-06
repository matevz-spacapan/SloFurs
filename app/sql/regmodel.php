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
	// Get all usere's registered current events: id, event name, duration (start, end), reg end (changes possible until then). confirmed, fursuiter, artist
	public function getREvents(){
		$sql='SELECT event_to_acc.id AS id, name, event_start, event_end, reg_end, confirmed, fursuiter, artist FROM event INNER JOIN event_to_acc ON event.id=event_to_acc.event_id WHERE ((event_start<=NOW() AND event_end>=NOW()) OR event_start>NOW()) AND acc_id=:acc_id ORDER BY event_start ASC';
		$query=$this->db->prepare($sql);
		$query->execute(array(':acc_id'=>$_SESSION['account']));
		return $query->fetchAll();
	}
	// Get all current/upcoming events
	public function getCEvents(){
		$sql='SELECT * FROM event WHERE id NOT IN (SELECT event_id FROM event_to_acc INNER JOIN event ON event.id=event_to_acc.event_id AND acc_id=:acc_id) ORDER BY event_start ASC';
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
	//Get details for selected event
	public function newEvent($id){
		$id=strip_tags($id);
		$sql='SELECT * FROM event WHERE id=:id';
		$query=$this->db->prepare($sql);
		$query->execute(array(':id'=>$id));
		return $query->fetch();
	}
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
		$sql='SELECT id FROM event_to_acc WHERE acc_id=:id AND '.$type_id.'=:type_id';
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