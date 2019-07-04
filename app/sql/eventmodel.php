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
	public function addEvent($fields){
		if($account->status!=3){
			//TODO report incident
			return "dYou can't do that. This incident was reported.";
		}
		//write keys and values for debugging to file
		$myfile=fopen("fields.txt", "w");
		foreach($fields as $key=>$value){
			$txt=$key.": ".$value."\n";
			fwrite($myfile, $txt);
		}
		if(empty(preg_grep('/(type\d)+/m', array_keys($fields)))){
			fwrite($myfile, 'No rooms available.');
		}
		else{
			fwrite($myfile, implode(", ", preg_grep('/(type\d)+/m', array_keys($fields))));
		}
		fclose($myfile);

		//EVENT
		$name=strip_tags($fields["name"]);
		$start=strip_tags($fields["start"]);
		$end=strip_tags($fields["end"]);
		$location=strip_tags($fields["location"]);
		$description=strip_tags($fields["description"]);
		$reg_start=strip_tags($fields["reg_start"]);
		$pre_reg=strip_tags($fields["pre_reg"]);
		if($pre_reg=='0000-00-00 00:00'){
			$pre_reg=$reg_start;
		}
		$reg_end=strip_tags($fields["reg_end"]);
		if($reg_end=='0000-00-00 00:00'){
			$reg_end=$start;
		}
		$age=strip_tags($fields["age"]);
		$restricted_age=strip_tags($fields["restricted_age"]);
		$restricted_text=strip_tags($fields["restricted_text"]);
		$regular_price=0;
		$sponsor_price=-1;
		$super_price=-1;
		//if price!=free, then update all prices
		switch(strip_tags($fields["ticket"])){
			case 'super':
				$super_price=strip_tags($fields["super_price"]);
			case 'sponsor':
				$sponsor_price=strip_tags($fields["sponsor_price"]);
			case 'regular':
				$regular_price=strip_tags($fields["regular_price"]);
			default:
				break;
		}
		//create event, get event ID for accomodation creation
		$sql="INSERT INTO event(name, event_start, event_end, reg_start, pre_reg_start, reg_end, location, description, age, restricted_age, restricted_text, regular_price, sponsor_price, super_price) VALUES (:name, :event_start, :event_end, :reg_start, :pre_reg_start, :reg_end, :location, :description, :age, :restricted_age, :restricted_text, :regular_price, :sponsor_price, :super_price)";
		$query=$this->db->prepare($sql);
		$query->execute(array(':name'=>$name, ':event_start'=>$start, ':event_end'=>$end, ':reg_start'=>$reg_start, ':pre_reg_start'=>$pre_reg, ':reg_end'=>$reg_end, ':location'=>$location, ':description'=>$description, 'age'=>$age, 'restricted_age'=>$restricted_age, 'restricted_text'=>$restricted_text, 'regular_price'=>$regular_price, 'sponsor_price'=>$sponsor_price, 'super_price'=>$super_price));
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