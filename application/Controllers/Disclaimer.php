<?php

class Disclaimer{
	public function index(){
		new Template("disclaimers.html", $data=[]);
	}
}

?>