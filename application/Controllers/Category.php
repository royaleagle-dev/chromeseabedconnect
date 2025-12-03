<?php

class Category extends Controller{

	private $categoryModel;

	public function __construct(){
		$this->categoryModel = $this->loadModel('CategoryModel');
	}

	public function index(){
		$data = [
			'categories' => $this->categoryModel->get_all_categories()
		];
		new Template("categories.html", $data);
	}

	public function show($id){
		$id = $id[0];
		//show movies in a particular cagegory
		$data = [
			'category' => $this->categoryModel->get_category($id),
			'movies' => $this->categoryModel->get_movies_in_category($id),
			'categories' => $this->categoryModel->getAllCategory(),
		];
		new Template("category.html", $data);
	}

}

?>
