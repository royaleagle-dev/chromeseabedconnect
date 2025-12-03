<?php

class SeriesModel extends Database{
    public function __construct(){
        parent::__construct();
        $this->tbl = 'movies';
    }

    public function get_all(){
        $statement = $this->query("SELECT * FROM $this->tbl WHERE series=? ORDER BY title ASC", $params=[1], $fetchMode='fetchAll');
        return $statement;
    }

    public function get_recent($max){
      $statement = $this->query("SELECT * FROM $this->tbl WHERE series=? ORDER BY id DESC LIMIT $max", $params=[1], $fetchMode='fetchAll');
      return $statement;
    }

    public function getAllSeriesPg($start, $end){
  		$statement = $this->query("SELECT * FROM $this->tbl WHERE series=? ORDER BY id DESC LIMIT $start, $end", $params=[1], $fetchMode='fetchAll');
  		return $statement;
  	}
}

?>
