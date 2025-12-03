<?php

class Authentication{

    private $email;
    private $password;
    //private $statement;
    private $errors;

    public function __construct($email, $password){
        $filter = $this->filterCredentials(array('email'=>$email, 'password'=>$password));
        if($filter){
            $this->email = html_entity_decode($email);
            $this->password = html_entity_decode($password);
            $this->database = new Database();
        }else{
            $this->errors[] = 'Cannot Validate Your Email, Please Try Again';
        }
    }

    public function filterCredentials($credentials){
        //validate the email
        $validate_email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        var_dump($validate_email);
        if($validate_email){
            return true;
        }else{
            return false;
        }
    }

    public function authenticate(){
        if(!empty($this->errors)){
            return $this->errors;
        }
        $statement = $this->database->query("SELECT * FROM admin WHERE email=?", $params = array($this->email), $fetchmode = 'fetch');
        if(!$statement){
            $this->errors[] = "Email does not exist, please register new account";
            return $this->errors;
        }else{
            $supplied_password = $this->password;
            $hashed_password= $statement->password;
            if(password_verify($supplied_password, $hashed_password)){
                new SessionManager();
                $token = SessionManager::generateToken();
                $this->database->query("UPDATE admin SET token=? WHERE email=?", $params=[$token, $this->email]);
                SessionManager::set_session(array(
                    'email'=>$statement->email,
                    'fullname'=>$statement->fullname,
                    'token' => $token,
                    )
                );
                return true;
            }else{
                $this->errors[] = "Incorrect Password";
                return $this->errors;
            }    
        }
    }
}
?>