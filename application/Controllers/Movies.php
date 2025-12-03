<?php

class Movies extends Controller {

	public function __construct(){
		$this->movieModel = $this->loadModel('Movie');
		$this->linkModel = $this->loadModel('LinkModel');
		$this->movieCategoryModel = $this->loadModel('MovieCategoryModel');

	}

	public function sub_stream(){
			$data = [
				'movies' => $this->movieModel->get_all_sub_movies(),
			];
			new Template("sub_stream_list.html", $data);
	}

	public function sub_movie($args){
		$id = $args[0];
		$movie = $this->movieModel->get_sub_movie($id);
		$data = ['movie'=>$movie];
		new Template("sub_movie.html", $data);
	}

	public function show($arg){
		$movieId = $arg[0];
		$data = [
			'similars' => $this->movieModel->get_similar_movies($movieId, 5),
			'movie' => $this->movieModel->getMovie($movieId),
			//'links' => $this->linkModel->getLinks($movieId, 'movie_links'),
			'categories' => $this->movieCategoryModel->getCategories($movieId),
			'featured' => $this->movieModel->get_featured(),
		];
		new Template("movie.html", $data);
	}

	public function watch($id){
		$id = $id[0];
		$data = [
			'similars' => $this->movieModel->get_similar_movies($id, 5),
			'movie' => $this->movieModel->getMovie($id),
			'featured' => $this->movieModel->get_featured(),
		];
		new Template("watch.html", $data);
	}

	public function links($id){
		$id = $id[0];
		$data = [
			'links' => $this->linkModel->getLinks($id, 'movie_links'),
			'movie' => $this->movieModel->getMovie($id),
			'similars' => $this->movieModel->get_similar_movies($id, 5),
			'featured' => $this->movieModel->get_featured(),
		];
		new Template("links.html", $data);
	}

	public function index(){

		$page_limit = 10;
		$movies_count = $this->movieModel->getAllMovies()['count'];
		$pages = ceil($movies_count/$page_limit);
		$testArray = array($page_limit, $movies_count, $pages);

		//print_r($testArray);
		if(isset($_GET['page'])){
			$start = ($_GET['page'] - 1) * $page_limit;
		}else{
			$start = 0;
		}
		$end = $page_limit;


		$data = [
			'pages' => $pages,
			'movies' => $this->movieModel->getAllMoviesPg($start, $end),
			'featured' => $this->movieModel->get_featured(),
		];
		new Template("movies.html", $data);
	}

	public function search($word){
		$word = $word[0];
		$search = $this->movieModel->searchMovie($word);
		$data = [
			'featured' => $this->movieModel->get_featured(),
			'search' => $search,
		];
		new Template("search.html", $data);
	}

	public function increase_views(){
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			$id = htmlentities($_POST['id']);
			$increase = $this->movieModel->increment_view($id);
			if($increase){
				echo json_encode(['status'=>'success', 'message'=>'View counted successfully']);
			}else{
				echo json_encode(['status' => 'success', 'message'=>'An error occured, View cannot be counted']);
			}
		}
	}

}

?>
