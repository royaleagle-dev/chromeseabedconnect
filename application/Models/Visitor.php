<?php

class Visitor{
	
	public function __construct(){
		$this->db = new Database();
	}

	public function getAllVisitors(){
		$statement = $this->db->query("SELECT * FROM visitors", $params=[], $fetchMode = 'fetchAll');
		return $statement;
	}

	public function getTodayVisitors(){
		$today = date('Y-m-d');
		$statement = $this->db->query("SELECT * FROM visitors WHERE visit_date=?", $params=[$today], $fetchMode = 'fetchAll');
		return $statement;
	}
}

?>