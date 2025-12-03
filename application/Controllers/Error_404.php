<?php

class Error_404 extends Controller{
	
	public function index(){
		new Template("core/404.html", $data=[]);
	}
}


?>