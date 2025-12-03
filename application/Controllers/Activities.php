<?php

class Activities extends Controller{

    private $categoryModel;

	public function __construct(){
        //loadModels
		$this->categoryModel = $this->loadModel('CategoryModel');
	}

    public function sendMessage(){

        //print_r($_POST);

        $post_data = file_get_contents('php://input');
        $post_data = json_decode($post_data);

        //print_r($post_data);

        $email = $_POST['sender-email'];
        $subject = $_POST['subject'];
        $message = $_POST['message'];

        $recipient = ALTERNATE_WEBSITE_EMAIL;

        //send Message

        $subject = $subject;
        $from = WEBSITE_EMAIL;
        $to = 'ayotundeokunubi73@gmail.com';

        //content type - Mail headers
        $headers = 'MIME-Version: 1.0'. "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1'. "\r\n";
        $headers .= "From: Website User <$from>". PHP_EOL;
        $headers .= 'Reply-To: '. "<$email>"."\r\n".'X-Mailer: PHP/' . phpversion();

        if(mail($from, $subject, $message, $headers)){

            echo json_encode([
                'status' => 'success',
            ]);

        }else{
            echo json_encode([
                'status' => 'error',
            ]);
        }
    }

}

?>