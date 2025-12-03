<?php

require_once "../application/config/config.php";
require_once "../application/Core/Database.php";
require_once "../application/Core/SessionManager.php";

/*
echo password_hash("12345", PASSWORD_DEFAULT);
session_start();
print $_SESSION['msg'];

$token = SessionManager::generateToken();
print $token;


"font-family: 'Lato', sans-serif;
font-family: 'Noto Sans JP', sans-serif;
font-family: 'Open Sans', sans-serif;
font-family: 'Roboto', sans-serif;"
*/

print "<hr>";

$db = new Database();
$movies = $db->query("SELECT * FROM movies", $params=[], $fetchMode='fetchAll');
$base_url = "https://www.thelinkdb.com/";

$file_name = 'sitemap.txt';
$file = fopen('sitemap.txt', 'a') or die('Cannot open file');
$main_url = $base_url."\n";
fwrite($file, $main_url);
foreach($movies['data'] as $movie){
	$movie_url = $base_url . "movies/show/$movie->id"."\n";
	fwrite($file, $movie_url);
}

?>