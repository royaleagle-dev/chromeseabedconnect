<?php

class Movie{

	public function __construct(){
		$this->db = new Database();
		$this->tbl = 'movies';
	}

	public function delete_sub_movie($id){
		$statement = $this->db->query("DELETE FROM sub_stream WHERE id=?", $params=[$id]);
		if($statement){ return true; }else{ return false; }
	}

	public function get_sub_movie($id){
		$statement = $this->db->query("SELECT * FROM sub_stream WHERE id=?", $params=[$id], $fetchMode='fetch');
		if($statement){ return $statement;}
		else{exit("Resource Not Available");}
	}

	public function get_all_sub_movies(){
		$statement = $this->db->query("SELECT * FROM sub_stream ORDER BY date_added", $params=[], $fetchMode='fetchAll');
		return $statement;
	}

	public function add_sub_movie($title, $description, $link, $date){
		$statement = $this->db->query("INSERT INTO sub_stream(title, description, link, date_added) VALUES(?,?,?,?)", $params=[$title, $description, $link, $date]);
		if($statement){ return true; }
		else{ return false; }
	}

	public function edit_sub_movie($title, $description, $link, $id){
		$statement = $this->db->query("UPDATE sub_stream SET title=?, description=?, link=? WHERE id=?", $params=[$title, $description, $link, $id]);
		if($statement){
			return true;
		}else{
			return false;
		}
	}

	public function get_carousel_items($max){
		$statement = $this->db->query("SELECT * FROM $this->tbl ORDER BY id DESC LIMIT $max", $params=[], $fetchMode = 'fetchAll');
		return $statement;
	}

	public function update_featured($id, $featured){
		$statement = $this->db->query("UPDATE $this->tbl SET featured=? WHERE id=?", $params=[$featured, $id]);
		if($statement){
			return true;
		}
	}

	public function update_series($movie_id, $val){
		$statement = $this->db->query("UPDATE $this->tbl SET series=? WHERE id=?", $params=[$val, $movie_id]);
		if($statement){
			return true;
		}else{
			return false;
		}
	}

	public function getAmericanMovies($max){
		$statement = $this->db->query("SELECT * FROM $this->tbl WHERE movie_type=? ORDER BY id DESC LIMIT $max", $params=[1], $fetchMode = 'fetchAll');
		return $statement;
	}

	public function getKoreanMovies($max){
		$statement = $this->db->query("SELECT * FROM $this->tbl WHERE movie_type=? ORDER BY id DESC LIMIT $max", $params=[11], $fetchMode = 'fetchAll');
		return $statement;
	}

	public function getChineseMovies($max){
		$statement = $this->db->query("SELECT * FROM $this->tbl WHERE movie_type=? ORDER BY id DESC LIMIT $max", $params=[5], $fetchMode = 'fetchAll');
		return $statement;
	}

	public function getNigerianMovies($max){
		$statement = $this->db->query("SELECT * FROM $this->tbl WHERE movie_type=? ORDER BY id DESC LIMIT $max", $params=[15], $fetchMode = 'fetchAll');
		return $statement;
	}

	public function getIndianMovies($max){
		$statement = $this->db->query("SELECT * FROM $this->tbl WHERE movie_type=? OR movie_type=? ORDER BY id DESC LIMIT $max", $params=[4, 14], $fetchMode = 'fetchAll');
		return $statement;
	}

	public function getSpanishMovies($max){
		$statement = $this->db->query("SELECT * FROM $this->tbl WHERE movie_type=? ORDER BY id DESC LIMIT $max", $params=[10], $fetchMode = 'fetchAll');
		return $statement;
	}

	public function getJapaneseMovies($max){
		$statement = $this->db->query("SELECT * FROM $this->tbl WHERE movie_type=? ORDER BY id DESC LIMIT $max", $params=[16], $fetchMode = 'fetchAll');
		return $statement;
	}

	public function get_featured(){
		$statement = $this->db->query("SELECT * FROM $this->tbl WHERE featured=?", $params=[1], $fetchMode='fetch');
		//print $statement;
		return $statement;
	}

	public function get_similar_movies($movie_id, $max){
		$movie = $this->db->query("SELECT movie_type FROM $this->tbl WHERE id=?", $params=[$movie_id], $fetchMode='fetch');
		$similars = $this->db->query("SELECT * FROM $this->tbl WHERE movie_type=? ORDER BY RAND() LIMIT $max", $params=[$movie->movie_type], $fetchMode='fetchAll');
		return $similars;
	}

	public function searchMovie($word){
		$pattern = "%$word%";
		$statement = $this->db->query("SELECT * FROM $this->tbl WHERE title LIKE ? OR description LIKE ? ", $params=[$pattern, $pattern], $fetchMode = 'fetchAll');
		if($statement){
			return $statement;
		}
	}

	public function getMovieId($poster_link){
		$statement = $this->db->query("SELECT id FROM movies WHERE poster_link=?", $params=[$poster_link], $fetchMode='fetch');
		if($statement){
			$id = $statement->id;
		}else{
			return "false";
		}
		return $id;
	}


	public function insertMovie($title, $description, $casts, $duration, $release_date, $poster_link, $date_added, $movieType, $trailer, $status, $watch_link, $iframe_watch, $carousel_picture){
		$statement = $this->db->query("INSERT INTO $this->tbl(title, description, cast, duration, release_date, poster_link, date_added, movie_type, trailer_url, movie_status, watch_link, iframe_watch, carousel_picture) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", $params=[$title, $description, $casts, $duration, $release_date, $poster_link, $date_added, $movieType, $trailer, $status, $watch_link, $iframe_watch, $carousel_picture]);
		if($statement){
			return true;
		}
	}


	public function updateMovie($title, $description, $casts, $duration, $release_date, $poster_link, $movieType, $trailer, $status, $watch_link, $iframe_watch, $carousel_picture, $movie_plot, $id){
		$statement = $this->db->query("UPDATE $this->tbl SET title=?, description=?, cast=?, duration=?, release_date=?, poster_link=?, movie_type=?, trailer_url=?, movie_status=?, watch_link=?, iframe_watch=?, carousel_picture=?, plot=? WHERE id=?",
			$params = [$title, $description, $casts, $duration, $release_date, $poster_link, $movieType, $trailer, $status, $watch_link, $iframe_watch, $carousel_picture, $movie_plot, $id]);
		if($statement){
			return true;
		}
	}

	public function getMovies($max_number = null){
		if(!is_null($max_number)){

			$statement = $this->db->query("SELECT movies.*, movie_type.type FROM movies
			LEFT JOIN movie_type ON movies.movie_type=movie_type.id
			ORDER BY id DESC",
				$params=[],
				$fetchMode='fetchAll'
			);

			$result = [];
			for($i=0; $i<$max_number; $i++){
				if($i > $statement['count']-1){
					break;
				}
				$result[] = $statement['data'][$i];
			}
			return $result;
		}
	}

	public function randomPicks($max_number = null){
		if(!is_null($max_number)){
			$statement = $this->db->query("SELECT * FROM movies ORDER BY RAND() LIMIT $max_number", $params = [], $fetchMode = 'fetchAll');
			return $statement;
		}
	}

	public function getMovie($identifier){
		$statement = $this->db->query(
			"SELECT movies.*, movie_type.type FROM  $this->tbl
			LEFT JOIN movie_type ON movies.movie_type=movie_type.id
			WHERE movies.id=?",
			$params = [$identifier],
			$fetchMode = 'fetch'
		);
		return $statement;
	}

	public function getAllMovies(){
		$statement = $this->db->query("SELECT * FROM $this->tbl ORDER BY id DESC", $params=[], $fetchMode='fetchAll');
		return $statement;
	}

	public function getAllMoviesPg($start, $end){
		$statement = $this->db->query("SELECT * FROM $this->tbl ORDER BY id DESC LIMIT $start, $end", $params=[], $fetchMode='fetchAll');
		return $statement;
	}

	public function deleteMovie($identifier){
		$statement = $this->db->query("DELETE FROM $this->tbl WHERE id=?", $params=[$identifier]);
		if($statement){return true;}
		else{return false;}
	}

	public function increment_view($id){
		$statement = $this->db->query("UPDATE $this->tbl SET views=views+1 WHERE id=?", $params=[$id]);
		if($statement){
			return true;
		}else{
			return false;
		}
	}

}


?>
