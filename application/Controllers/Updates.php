<?php

class Updates extends Controller{

	public function __construct(){
		$this->updateModel = $this->loadModel('UpdateModel');
	}

	public function index(){
		$data = [
			'updates' => $this->updateModel->get_all(),
		];
		new Template("updates.html", $data);
	}

}

?>