<?php

class Index extends Controller {

    private $reviewModel;

    public function __construct(){
        //$this->movieModel = $this->loadModel('Movie');
        $this->reviewModel = $this->loadModel('ReviewModel');
    }

    public function index(){
        $data = [
            'reviews' => $this->reviewModel->getAllReviews(),
        ];      
        //print_r($this->reviewModel->getAllReviews());
        new Template("index.html", $data);
    }

    public function review(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $firstname = htmlentities($_POST['firstname']);
            $lastname = htmlentities($_POST['lastname']);
            $review = htmlentities($_POST['review']);
        }
        $add_review = $this->reviewModel->add_review($firstname, $lastname, $review);
        if($add_review){
            echo json_encode(array('status' => 'success', 'message'=>'Review successfully added'));
        }else{
            echo json_encode(array('status' => 'error', 'message'=>'An error occured. Review cannot be added.'));
        }

    }
}

?>
