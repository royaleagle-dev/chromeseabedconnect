<?php 

class LinkModel{

	public function __construct(){
		$this->db = new Database();
		$this->tbl = 'movie_links';
	}

	public function insertLink($link_addr, $link_text, $movie){
		$statement = $this->db->query("INSERT INTO movie_links (link_addr, link_text, movie) VALUES(?,?,?)", $params=[$link_addr, $link_text, $movie]);
		if($statement){
			return true;
		}
	}

	public function updateLink($link_addr, $link_text, $movie, $id, $type){
		if($type == 'movie'){
			$statement = $this->db->query("UPDATE movie_links SET link_addr=?, link_text=?, movie=? WHERE id=?", 
			$params =[$link_addr, $link_text, $movie, $id]);
			if($statement){
				return true;
			}
		}
	}

	public function deleteLink($link_addr){
		$statement = $this->db->query("DELETE FROM $this->tbl WHERE link_addr=?", $params=[$link_addr]);
		if($statement){
			return true;
		}
	}

	public function getLinks($identifier, $tbl){
		$statement = '';
		if($tbl == 'series_links'){
			$statement = $this->db->query("SELECT * FROM $tbl WHERE episode = ?", $params=[$identifier], $fetchMode = 'fetchAll');
		}
		elseif($tbl = 'movie_links'){
			$statement = $this->db->query("SELECT * FROM $tbl WHERE movie = ?", $params=[$identifier], $fetchMode = 'fetchAll');	
		}
		//print_r($statement);
		return $statement;
	}

	public function getAllMovieLinks(){
		$statement = $this->db->query("SELECT * FROM movie_links", $params=[], $fetchMode = 'fetchAll');
		return $statement;
	}

	public function getAllLinks(){
		$movies = $this->db->query("SELECT * FROM movie_links", $params=[], $fetchMode = 'fetchAll');
		$results = [$movies];
		return $results;
	}

	public function delete($id){
		$statement = $this->db->query("DELETE FROM $this->tbl WHERE id=?", $params=[$id]);
		if($statement){
			return true;
		}else{
			return false;
		}
	}
}

?>