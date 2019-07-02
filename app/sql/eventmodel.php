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
	// Convert MySQL datetime to HTML datetime
	public function convert($date, $t=true){
		return ($t)?date_format(new DateTime($date),"Y-m-d\TH:i"):date_format(new DateTime($date),"d.m.Y");
	}
	// Add new event
	public function addEvent($type, $name, $start, $end, $reg, $pre_reg, $reg_end, $loc, $desc){
		if(isset($type)&&isset($name)&&isset($start)&&isset($end)&&isset($reg)&&isset($loc)&&isset($desc)){
			$type=strip_tags($type);
			$name=strip_tags($name);
			$start=str_replace('T',' ',strip_tags($start));
			$end=str_replace('T',' ',strip_tags($end));
			$reg=str_replace('T',' ',strip_tags($reg));
			$pre_reg=str_replace('T',' ',strip_tags($pre_reg));
			$reg_end=str_replace('T',' ',strip_tags($reg_end));
			//if no reg end is set, then reg ends at start of event
			if($reg_end=='0000-00-00 00:00'){
				$reg_end=$start;
			}
			$loc=strip_tags($loc);
			$desc=strip_tags($desc);
			$format='Y-m-d H:i';
			$now=new DateTime();
			$start_time=date_create_from_format($format, $start);
			$end_time=date_create_from_format($format, $end);
			$reg_time=date_create_from_format($format, $reg);
			$pre_time=date_create_from_format($format, $pre_reg);
			$a=$start_time<$now;
			$b=$end_time<$start_time;
			$c=$start_time<$reg_time;
			$d=$reg_time<$pre_time;
			if($a||$b||$c||$d){
				return 'dInvalid date/time inputs. END>START>NOW and START>REG>PRE-REG';
			}
			$sql="INSERT INTO event(type, name, event_start, event_end, reg_start, pre_reg_start, reg_end, location, description) VALUES (:type, :name, :event_start, :event_end, :reg_start, :pre_reg_start, :reg_end, :location, :description)";
			$query=$this->db->prepare($sql);
			$query->execute(array(':type'=>$type, ':name'=>$name, ':event_start'=>$start, ':event_end'=>$end, ':reg_start'=>$reg, ':pre_reg_start'=>$pre_reg, ':reg_end'=>$reg_end, ':location'=>$loc, ':description'=>$desc));
			return 'sEvent added successfully!';
		}
		else{
			return 'dPlease fill out all non-optional fields.';
		}
			
	}
	// Edit an event
	public function editEvent($id, $type, $name, $start, $end, $reg, $pre_reg, $reg_end, $loc, $desc){
		if(isset($id)&&isset($type)&&isset($name)&&isset($start)&&isset($end)&&isset($reg)&&isset($loc)&&isset($desc)){
			$sql='SELECT status FROM account WHERE id=:id';
			$query=$this->db->prepare($sql);
			$query->execute(array(':id'=>$_SESSION['account']));
			$account=$query->fetch();
			if($account->status==3){
				$id=strip_tags($id);
				$type=strip_tags($type);
				$name=strip_tags($name);
				$start=str_replace('T',' ',strip_tags($start));
				$end=str_replace('T',' ',strip_tags($end));
				$reg=str_replace('T',' ',strip_tags($reg));
				$pre_reg=str_replace('T',' ',strip_tags($pre_reg));
				$reg_end=str_replace('T',' ',strip_tags($reg_end));
				$loc=strip_tags($loc);
				$desc=strip_tags($desc);
				$format='Y-m-d H:i';
				$now=new DateTime();
				$start_time=date_create_from_format($format, $start);
				$end_time=date_create_from_format($format, $end);
				$reg_time=date_create_from_format($format, $reg);
				$pre_time=date_create_from_format($format, $pre_reg);
				$a=$start_time<$now;
				$b=$end_time<$start_time;
				$c=$start_time<$reg_time;
				$d=$reg_time<$pre_time;
				if($a||$b||$c||$d){
					return 'dInvalid date/time inputs. END>START>NOW and START>REG>PRE-REG';
				}
				$sql="UPDATE event SET type=:type, name=:name, event_start=:event_start, event_end=:event_end, reg_start=:reg_start, pre_reg_start=:pre_reg_start, reg_end=:reg_end, location=:location, description=:description WHERE id=:id";
				$query=$this->db->prepare($sql);
				$query->execute(array(':type'=>$type, ':name'=>$name, ':event_start'=>$start, ':event_end'=>$end, ':reg_start'=>$reg, ':pre_reg_start'=>$pre_reg, ':reg_end'=>$reg_end, ':location'=>$loc, ':description'=>$desc, ':id'=>$id));
				return 'sEvent updated successfully!';
			}
			else{
				//TODO report incident
				return "dYou can't do that. This incident was reported.";
			}
			
		}
		else{
			return 'dPlease fill out all non-optional fields.';
		}
			
	}
}