<?php

class CategoryModel{

    public function __construct(){
        $this->db = new Database();
        $this->tbl = 'categories';
    }

    public function get_movies_in_category($id){
      $statement = $this->db->query("SELECT movie_category.*, movies.* FROM movie_category
      LEFT JOIN movies ON movie_category.movie_id=movies.id WHERE movie_category.category_id=? ORDER BY date_added DESC",
    $params=[$id], $fetchMode = 'fetchAll');
      //print_r($statement);
      return $statement;
    }

    public function get_category($id){
      $statement = $this->db->query("SELECT * FROM $this->tbl WHERE id=?", $params=[$id], $fetchMode='fetch');
      if($statement){return $statement;}else{redirect('404');}
    }

    public function getAllCategory(){
        $statement = $this->db->query("SELECT * FROM $this->tbl ORDER BY id DESC", $params = [], $fetchMode = 'fetchAll');
        return $statement;
    }

    public function addCategory($category, $description){

        #check to see if category exists before
        $allCategory = $this->getAllCategory();
        foreach($allCategory['data'] as $item){
            if($category == $item->category){
                return false;
            }
        }

        #if true deny request, else create new category with supplied credentials
        $modified = date('Y-m-d');
        $statement = $this->db->query("INSERT INTO $this->tbl (category, description, modified) VALUES(?, ?, ?)", $params= [$category, $description, $modified]);
        if($statement){
            return true;
        }

    }

    public function deleteCategory($id){
        $statement = $this->db->query("DELETE FROM $this->tbl WHERE id=?", $params = [$id]);
        if($statement){
            return true;
        }
    }
}

?>
