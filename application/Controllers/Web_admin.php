<?php

class Web_admin extends Controller{

	public function __construct(){
		new SessionManager();

		if (!array_key_exists("token", $_SESSION)){
			//redirect to login page
			redirect('adminLogin');
		}

		setcookie('userToken', $_SESSION['token']);
		$current_token = $_SESSION['token'];
		$expiry = explode('.', $current_token);
		if(date('dHi', $expiry[1]) < date('dHi')){
			print "This token is no more valid";
			print date('Y-m-d H:i:s', $expiry[1]);
			print "<hr>";
			print date('Y-m-d H:i:s');
			print "<hr>";
			print date('Hi');
			print "---";
			print date('Hi', $expiry[1]);
			exit();
		}

		else{
			$this->movieModel = $this->loadModel('Movie');
			$this->linkModel = $this->loadModel('LinkModel');
			$this->categoryModel = $this->loadModel('CategoryModel');
			$this->movieCategoryModel = $this->loadModel('MovieCategoryModel');
			$this->movieTypeModel = $this->loadModel('MovieTypeModel');
			$this->updateModel = $this->loadModel('UpdateModel');
		}

	}

	#----------------------------------------------------------------------------------------------
	#----------------------------------------------------------------------------------------------
	#----------------------------------------------------------------------------------------------
	#----------------------------------------------------------------------------------------------
	#----------------------------SERIES UPDATES --------------------------------

	public function updates(){
		$data = [
			'updates' => $this->updateModel->get_all(),
		];
		new Template("administrator/movie_updates.html", $data);
	}

	public function add_update(){
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			$update_text = htmlentities($_POST['update-text']);
			$movie_link = htmlentities($_POST['movie-link']);
			$date_added = date('Y-m-d');

			$id = new GenerateId('upd');
			$id = $id->generate();

			$add = $this->updateModel->addUpdate($id, $update_text, $movie_link, $date_added);
			if($add){
				echo json_encode(['status' => 'success', 'message' => 'Update successfully added']);
			}else{
				echo json_encode(['status' => 'error', 'message' => 'An error occured, Update cannot be added.']);
			}


		}else{
			//get request
			new Template("administrator/add_update.html", $data=[]);
		}
	}

	public function delete_update(){
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			$update_id = $_POST['id'];
			$delete = $this->updateModel->delete($update_id);
			if($delete){
				echo json_encode(['status' => 'success', 'message'=>'Update deleted Successfully']);
			}else{
				echo json_encode(['status'=>'error', 'message'=>'Update cannot be deleted. Try Again.']);
			}
		}
	}

	public function delete_multiple_update(){
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			$success_delete_list = [];
			$data = $_POST['data'];
			foreach($data as $item){
				$delete = $this->updateModel->delete($item);
				if($delete){
					$success_delete_list[] = 1;
				}
			}

			if(count($success_delete_list) == count($data)){
				echo json_encode(['status' => 'success', 'message'=>'Multiple Updates Deleted Successfully']);
			}else{
				echo json_encode(['status' => 'error', 'Updates cannot be fully deleted, try again later.']);
			}
		}
	}

	public function edit_update($args){
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			$update_text = $_POST['update-text'];
			$movie_link = $_POST['movie-link'];
			$id = $_POST['update-id'];
			$update = $this->updateModel->update($update_text, $movie_link, $id);
			if($update){
				echo json_encode(['status'=>'success', 'message'=>'Update edited successfully']);
			}else{
				echo json_encode(['status'=>'error', 'message'=>'Update can not be edited, try again']);
			}
		}else{
			//get request displays update form.
			$id = $args[0];
			new Template("administrator/edit_update.html", ['update' => $this->updateModel->get($id)]);
		}
	}

	#----------------------------END OF MOVIE UPDATES ----------------------------------------------------
	#----------------------------------------------------------------------------------------------
	#----------------------------------------------------------------------------------------------
	#----------------------------------------------------------------------------------------------


	public function logout(){
		SessionManager::end_session();
		redirect('adminLogin');
	}

	public function index(){
		$data = [
			'movies' => $this->movieModel->getMovies(20),
			'movies_count'=>$this->movieModel->getAllMovies()['count'],
			'links_count'=>$this->linkModel->getAllLinks()[0]['count'],
		];
		new Template('administrator/dashboard.html', $data);
	}

	public function allMovies(){
		$data = [
			'movies' => $this->movieModel->getAllMovies(),
		];
		new Template("administrator/admin_movies_list.html", $data);
	}

	public function delete_sub_movie($args){
		$id = $args[0];
		$delete = $this->movieModel->delete_sub_movie($id);
		if($delete){
			redirect('adminSubMovies');
		}
	}

	public function all_sub_movies(){
		$data = [
			'movies' => $this->movieModel->get_all_sub_movies(),
		];
		new Template("administrator/admin_sub_movie_list.html", $data);
	}

	/*Movie CRUD Function */

	public function deleteMovie(){
		$delete = $this->movieModel->deleteMovie($_POST['id']);
		if($delete){echo json_encode(['status'=>'success']);}
		else{echo json_encode(['status'=>'failure']);}
	}

	public function add_sub_stream(){
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			$title = htmlentities($_POST['title']);
			$description = htmlentities($_POST['description']);
			$link = htmlentities($_POST['link']);
			$date = date('Y-m-d');

			$add_sub_movie = $this->movieModel->add_sub_movie($title, $description, $link, $date);

			if($add_sub_movie){
				echo json_encode(['status'=>'success', 'message'=>'Movie successfully added']);
			}else{
				echo json_encode(['status'=>'error', 'message'=>'An error occured, movie cannot be added']);
			}
		}else{
			new Template("administrator/add_sub_stream.html", $data=[]);
		}
	}

	public function edit_sub_stream($args){
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			$id = htmlentities($_POST['id']);
			$title = htmlentities($_POST['title']);
			$description = htmlentities($_POST['description']);
			$link = htmlentities($_POST['link']);

			$update = $this->movieModel->edit_sub_movie($title, $description, $link, $id);
			if($update){
				echo json_encode(['status'=>'success', 'message'=>'Movie Updated successfully']);
			}else{
				echo json_encode(['status'=>'error', 'message'=>'An error occured, movie cannot be updated']);
			}
		}else{
			$id = $args[0];
			$movie = $this->movieModel->get_sub_movie($id);
			$data = ['movie' => $movie];
			new Template("administrator/edit_sub_stream.html", $data);
		}
	}

	public function addMovie(){
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			$title = html_entity_decode($_POST['title']);
			$description = html_entity_decode($_POST['description']);
			$casts = html_entity_decode($_POST['casts']);
			$duration = html_entity_decode($_POST['duration']);
			$release_date = html_entity_decode($_POST['release_date']);
			$poster_link = html_entity_decode($_POST['poster_link']);
			$date_added = date('Y-m-d');
			$categories = $_POST['category'];
			$movieType = $_POST['movie-type'];
			$links = html_entity_decode($_POST['links']);
			$trailer = htmlentities($_POST['trailer']);
			$status = htmlentities($_POST['status']);
			$watch_link = $_POST['watch_link'];
			$iframe_watch = $_POST['iframe_watch'];
			$carousel_picture = htmlentities($_POST['carousel_link']);

			if (strlen($title) > 1 && strlen($description) >1 && strlen($casts) > 1 && strlen($duration) > 1){
				$insertMovie = $this->movieModel->insertMovie($title, $description, $casts, $duration, $release_date, $poster_link, $date_added, $movieType, $trailer, $status, $watch_link, $iframe_watch, $carousel_picture);
				$movie = $this->movieModel->getMovieId($poster_link);
				if($insertMovie){
					//successfully inserted;

					foreach($categories as $category){
						$this->movieCategoryModel->add_category_to_movie($category, $movie);
					}


					$links= explode("\n", $links);
					print_r($links);
					$links_splitted = [];
					foreach($links as $link){
						$links_splitted[] = explode(', ', $link);
					}
					$success_link_counter = 0;
					foreach($links_splitted as $link){
						#$movie = $this->movieModel->getMovieId($poster_link);
						$insertLink = $this->linkModel->insertLink($link[0], $link[1], $movie);
						if($insertLink){
							$success_link_counter += 1;
						}
					}
					if($success_link_counter == count($links_splitted)){
						SessionManager::flash_message("Movie and Links successfully added to database");
						redirect('dashboard');
					}
				}
			}
		}else{
			$data = [
				'categories' => $this->categoryModel->getAllCategory(),
				'movieTypes' => $this->movieTypeModel->get_all_types(),
			];
			new Template("administrator/addMovie.html", $data);
		}
	}

	public function editMovie($id){
		$id = $id[0];
		if($_SERVER['REQUEST_METHOD'] == 'POST'){

			$title = htmlentities($_POST['title']);
			$description = htmlentities($_POST['description']);
			$casts = htmlentities($_POST['casts']);
			$duration = htmlentities($_POST['duration']);
			$release_date = htmlentities($_POST['release_date']);
			$poster_link = htmlentities($_POST['poster_link']);
			$featured = filter_input(INPUT_POST, 'featured', FILTER_SANITIZE_STRING);
			$series = filter_input(INPUT_POST, 'series', FILTER_SANITIZE_STRING);
			$trailer = htmlentities($_POST['trailer']);
			$status = htmlentities($_POST['status']);
			$watch_link = $_POST['watch_link'];
			$iframe_watch = $_POST['iframe_watch'];
			$carousel_picture = htmlentities($_POST['carousel_link']);
			$movie_plot = htmlentities($_POST['movie_plot']);
			//print $series;

			$id = $_POST['id'];
			$links = $_POST['link'];
      		$categories = $_POST['category'];
    		$movieType = $_POST['movie-type'];

			if($featured){
				$movies = $this->movieModel->getAllMovies();
				foreach($movies['data'] as $movie){
					$this->movieModel->update_featured($movie->id, 0);
				}
				$featured = 1;
			}else{
				$featured = 0;
			}

			if($series){
				$this->movieModel->update_series($id, 1);
			}else{
				$this->movieModel->update_series($id, 0);
			}
			$updateMovie = $this->movieModel->updateMovie($title, $description, $casts, $duration, $release_date, $poster_link, $movieType, $trailer, $status, $watch_link, $iframe_watch, $carousel_picture, $movie_plot, $id);
			$update_featured = $this->movieModel->update_featured($id, $featured);

			if($updateMovie){

				//get all movie links
				$DBlinkArray = [];
				$linkArray = [];
				$movieLinks = $this->linkModel->getLinks($id, 'movie_links');

                //categories
                $this->movieCategoryModel->update_categories($id, $categories);

				//delete links
				foreach($movieLinks['data'] as $link){
					$this->linkModel->deleteLink($link->link_addr);
				}

				//extract links from POST value;
				foreach($links as $link){
					$split = explode(', ', $link);
					$linkArray[$split[0]] = $split[1];
				}

				//insert new links
				foreach($linkArray as $link => $link_txt){
					$this->linkModel->insertLink($link, $link_txt, $id);
				}

				SessionManager::flash_message("Movie and Links successfully successfully edited");

        //redirect to dashboard
				redirect('dashboard');

			}

		}else{
			$links = $this->linkModel->getLinks($id, 'movie_links');
			$data = [
				'movie_types' => $this->movieTypeModel->get_all_types(),
				'movie' => $this->movieModel->getMovie($id),
				'links' => $links,
                'categories' => $this->categoryModel->getAllCategory(),
                'movie_categories' => $this->movieCategoryModel->getCategoryIds($id),
			];
			new Template("administrator/editMovie.html", $data);
		}
	}

	/* END */

	/* SERIES CRUD FUNCTION */
	/* END */

	public function genres(){
		if ($_SERVER['REQUEST_METHOD'] == 'POST'){
			$category = htmlentities($_POST['category']);
			$description = htmlentities($_POST['description']);

			$addCategory = $this->categoryModel->addCategory($category, $description);
			if($addCategory){
				echo json_encode(array('status'=>'success'));
			}else{
				echo json_encode(array('status'=>'failure'));
			}

		}else{
			$data = [
				'categories'=>$this->categoryModel->getAllCategory(),
			];
			new Template("administrator/genres.html", $data);
		}
	}

	public function movie_type(){
		if ($_SERVER['REQUEST_METHOD'] == 'POST'){
				$addType = $this->movieTypeModel->addMovieType($_POST['type']);
				if($addType){echo json_encode(['status'=>'success']);}
				else{echo json_encode(['status'=>'failure']);}
		}else{
			$data = [
				'categories' => $this->movieTypeModel->get_all_types(),
			];
			new Template("administrator/movieTypes.html", $data);
		}
	}

	public function deleteMovieType(){
		$delete = $this->movieTypeModel->delete($_POST['id']);
		if($delete){echo json_encode(['status'=>'success']);}
		else{echo json_encode(['status'=>'failure']);}
	}

	public function deleteCategory($id){
		$id = $id[0];
		$deleteCategory = $this->categoryModel->deleteCategory($id);
		if($deleteCategory){
			echo json_encode(array("status" => "success"));
		}else{
			echo json_encode(array("status" => "failure"));
		}
	}

	public function deleteLink(){
		$id = htmlentities($_POST['id']);
		$delete = $this->linkModel->delete($id);
		if($delete){
			echo json_encode(['status'=>'success', 'message'=>'Link Deleted Successfully']);
		}else{
			echo json_encode(['status'=>'error', 'message'=>'An error occured, Link cannot be deleted']);
		}
	}
}

?>
