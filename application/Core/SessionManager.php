<?php

class SessionManager{

    public function __construct(){
        session_start();
    }

    public static function set_session(array $session_items){
        foreach ($session_items as $key => $value){
            $_SESSION[$key] = $value;
        }

    }

    public  static function check_session_exists(array $session_items){
        $successful_checks = [];
        foreach($session_items as $item){
            if(isset($_SESSION[$item])){$successful_checks[] = 1;}
        }
        if(count($successful_checks) == count($session_items)){
            return true;
        }else{
            return false;
        }
    }

    public static function end_session(){
        session_destroy();
    }

    public static function flash_message($message){
        $_SESSION['msg'] = $message;
        //return $_SESSION['msg'];
    }

    public static function clear_flash_message(){
        unset($_SESSION['msg']);
    }

    public static function getItem($key){
        return $_SESSION[$key];
    }

    public static function generateToken(){
        $now = date('His');

        $exp_mins = date("i")+60;
        $exp_hr = date("H");

        if($exp_mins >= 60){
            $exp_mins = $exp_mins-60;
            $exp_hr += 1;
        }

        $expire = mktime($exp_hr, $exp_mins, date('s'), date('m'), date('d'), date('Y'));
        return $now.'.'.$expire;
    }

}

?>