<?php

class Donate extends Controller{
    public function index(){
        new Template("donate.html", $data=[]);
    }
}

?>