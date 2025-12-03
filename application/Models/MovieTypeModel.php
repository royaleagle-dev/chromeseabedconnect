<?php 

class MovieTypeModel{
	public function __construct(){
		$this->db = new Database();
		$this->tbl = 'movie_type';
	}

	public function get_all_types(){
		$statement = $this->db->query("SELECT * FROM $this->tbl ORDER BY id DESC", $params=[], $fetchMode='fetchAll');
		return $statement;
	}

	public function delete($id){
		$delete = $this->db->query("DELETE FROM $this->tbl WHERE id=?", $params=[$id]);
		if($delete){return true;}else{return false;}
	}

	public function addMovieType($type){
		$addType = $this->db->query("INSERT INTO $this->tbl (type) VALUES(?)", $params=[$type]);
		if($addType){return true;}else{return false;}
	}
}

?>