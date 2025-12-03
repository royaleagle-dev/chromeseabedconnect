<?php

class Series extends Controller{
    public function __construct(){
        $this->seriesModel = $this->loadModel('SeriesModel');
        $this->movieModel = $this->loadModel('Movie');
    }
    public function index(){
      $page_limit = 10;
  		$movies_count = $this->seriesModel->get_all()['count'];
  		$pages = ceil($movies_count/$page_limit);
  		$testArray = array($page_limit, $movies_count, $pages);

      if(isset($_GET['page'])){
  			$start = ($_GET['page'] - 1) * $page_limit;
  		}else{
  			$start = 0;
  		}
  		$end = $page_limit;

      $data = [
            'pages' => $pages,
            'featured' => $this->movieModel->get_featured(),
            'series' => $this->seriesModel->getAllSeriesPg($start, $end),
            'recent' => $this->seriesModel->get_recent(4),
      ];

      new Template("series.html", $data);
    }
}
