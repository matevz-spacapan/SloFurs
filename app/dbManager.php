<?php
class Connection{
	public $db=null;
	function __construct(){   
		if(!isset($_SESSION['account'])){
			session_start();
		}
		$this->openDbConnection();
	}
	//db connection
	private function openDbConnection(){
		$options=array(PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_OBJ, PDO::ATTR_ERRMODE=>PDO::ERRMODE_WARNING);
		$this->db=new PDO(DB_TYPE.':host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS, $options);
	}
	//session acc - who's logged in
	public function getSessionAcc(){
		if(isset($_SESSION['account'])&&$_SESSION['account']!=''){
			$sql='SELECT * FROM account WHERE id=:id';
			$query=$this->db->prepare($sql);
			$query->execute(array(':id'=>$_SESSION['account']));
			return $query->fetch();
		}
	}
	//crop domain
	public function getBaseUrl(){
		$base=$_SERVER['REQUEST_URI'];
		return $base;
	}
	//sql queries
	public function loadSQL($sql_site_name){
		require_once('app/sql/'.strtolower($sql_site_name).'.php');
		return new $sql_site_name($this->db);
	}
}