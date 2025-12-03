<?php

require_once "../application/Core/Database.php";

$dbh = new Database();
function get_popular($limit){
  global $dbh;
  $statement = $dbh->query("SELECT * FROM movies ORDER BY views DESC LIMIT $limit", $params=[], $fetchMode='fetchAll');
  //print_r($statement);
  return $statement;
}

function get_genres(){
  global $dbh;
  $statement = $dbh->query("SELECT * FROM categories ORDER BY category ASC", $params=[], $fetchMode='fetchAll');
  return $statement;
}

 ?>
