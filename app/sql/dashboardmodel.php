<?php
class DashboardModel{
	// Database
	function __construct($db){
		try{
			$this->db=$db;
		}
		catch (PDOException $e){
			exit('Database connection could not be established.');
		}
	}
	// Account brief
	public function accountsB1(){
		$sql='SELECT count(*) AS tot FROM account';
		$query=$this->db->prepare($sql);
		$query->execute();
		return $query->fetch();
	}
	public function accountsB2(){
		$sql='SELECT count(*) AS tot FROM account WHERE fname IS NULL';
		$query=$this->db->prepare($sql);
		$query->execute();
		return $query->fetch();
	}
	// Events brief
	public function eventsB1(){
		$sql='SELECT count(*) AS tot FROM event';
		$query=$this->db->prepare($sql);
		$query->execute();
		return $query->fetch();
	}
	public function eventsB2(){
		$sql='SELECT count(*) AS tot FROM event WHERE event_start>NOW()';
		$query=$this->db->prepare($sql);
		$query->execute();
		return $query->fetch();
	}
	// Fursuits brief
	public function fursuitsB(){
		$sql='SELECT count(*) AS tot FROM fursuit';
		$query=$this->db->prepare($sql);
		$query->execute();
		return $query->fetch();
	}
	// Emails brief
	public function emailsB(){
		require("public/sendgrid-php/sendgrid-php.php");
		$apiKey = getenv('SENDGRID_API_KEY');
		$sg = new \SendGrid($apiKey);
		$query_params = json_decode('{"aggregated_by": "month", "limit": 1, "start_date": "2019-07-01", "offset": 1}');
		$response = $sg->client->stats()->get(null, $query_params);
		return $response->body();
	}
	//Recent changes

	//Newest accounts
	public function newest(){
		$sql='SELECT pfp, username, email, fname, lname, created FROM account ORDER BY created DESC LIMIT 5';
		$query=$this->db->prepare($sql);
		$query->execute();
		return $query->fetchAll();
	}
	//Top countries
	public function countries(){
		$sql='SELECT country, count(*) as tot FROM account WHERE country IS NOT NULL GROUP BY country ORDER BY tot DESC LIMIT 10';
		$query=$this->db->prepare($sql);
		$query->execute();
		return $query->fetchAll();
	}
}
