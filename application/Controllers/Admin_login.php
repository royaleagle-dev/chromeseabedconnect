<?php

class Admin_login{

	public function index(){
		new Template("administrator/login.html", $data=[]);
	}

	public function authenticate(){
		if(isset($_POST['email']) && isset($_POST['password'])){
			print "I'm here";
			$authentication = new Authentication($_POST['email'], $_POST['password']);
			$auth = $authentication->authenticate();
			if($auth === true){
				redirect("dashboard");
			}else{
				//display Errors
				print "Some Errors Happened Here";
				print "<hr>";
				print_r($auth);
			}

		}
	}
}

?>