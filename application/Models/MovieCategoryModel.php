<?php
class MovieCategoryModel{
    
    public function __construct(){
        $this->db = new Database();
        $this->tbl = 'movie_category';
    }

    public function add_category_to_movie($category_id, $movie_id){
        $statement = $this->db->query("INSERT INTO $this->tbl (category_id, movie_id) VALUES (?, ?)", $params = [$category_id, $movie_id]);
        return $statement;
    }

    public function getCategories($movie_id){
        $statement = $this->db->query("SELECT movie_category.*,categories.category FROM $this->tbl 
        LEFT JOIN categories ON category_id = categories.id WHERE movie_id=? ", 
        $params=[$movie_id], $fetchMode = 'fetchAll');
        return $statement;        
    }
    
    public function getCategoryIds($movie_id){
        $statement = $this->db->query("SELECT movie_category.*,categories.category FROM $this->tbl 
        LEFT JOIN categories ON category_id = categories.id WHERE movie_id=? ", 
        $params=[$movie_id], $fetchMode = 'fetchAll');
        $result = [];
        foreach($statement['data'] as $item){
            $result[] = $item->category_id;
        }
        return $result;

    }
    
    public function update_categories($movie_id, array $categories){
        $movie_categories = $this->db->query("DELETE FROM $this->tbl WHERE movie_id =?", $params =[$movie_id]);
        foreach($categories as $category){
            $this->add_category_to_movie($category, $movie_id);
        }
        return true;
    }
}
?>