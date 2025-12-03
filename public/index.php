<?php

require_once "loader.php";
require_once "../application/Core/Database.php";

$dbh = new Database();

$visitor_addr = $_SERVER['REMOTE_ADDR'];
$visit_date = date('Y-m-d');
$already_registered = 0;

//get all visits
/*
$allVisits = $dbh->query("SELECT * FROM visitors", $params=[], $fetchMode = 'fetchAll');

foreach($allVisits as $visit){
	if($visit->ip_addr == $visitor_addr && $visit->visit_date == $visit_date){
		//do not insert into dbase
		//echo "Ip address already registered";
		$already_registered = 1;
		break;
	}
}

if($already_registered == 0){
	$query = $dbh->query("INSERT INTO visitors (ip_addr, visit_date) VALUES (?,?)", $params=[$visitor_addr, $visit_date]);
	if($query){

	}

}
*/


new Router();



?>