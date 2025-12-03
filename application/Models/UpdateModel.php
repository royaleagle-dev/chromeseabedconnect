<?php

class UpdateModel extends Database {
	public function __construct(){
		parent::__construct();
		$this->tbl = 'movie_updates';
	}

	public function get_all(){
		$statement = $this->query("SELECT * FROM $this->tbl ORDER BY date DESC", $params=[], $fetchMode = 'fetchAll');
		return $statement;
	}

	public function get_updates($max){
		$statement = $this->query("SELECT * FROM $this->tbl ORDER BY date DESC LIMIT $max", $params =[], $fetchMode='fetchAll');
		//print_r($statement);
		return $statement;
	}

	public function addUpdate($id, $update_text, $movie_link, $date_added){
		$statement = $this->query("INSERT INTO $this->tbl(id, update_text, movie_link, date) VALUES(?,?,?,?)", $params=[$id, $update_text, $movie_link, $date_added]);
		if($statement){
			return true;
		}else{
			return false;
		}
	}

	public function delete($update_id){
		$statement = $this->query("DELETE FROM $this->tbl WHERE id=?", $params=[$update_id]);
		if($statement){ return true; }else{ return false; }
	}

	public function get($update_id){
		$statement = $this->query("SELECT * FROM $this->tbl WHERE id=?", $params=[$update_id], $fetchMode='fetch');
		if($statement){
			return $statement;
		}else{
			redirect('404');
		}
	}

	public function update($update_text, $movie_link, $id){
		$statement = $this->query("UPDATE $this->tbl SET update_text=?, movie_link=? WHERE id=?", $params=[$update_text, $movie_link, $id]);
		if($statement){ return true; }else{ return false; }
	}
}

?>