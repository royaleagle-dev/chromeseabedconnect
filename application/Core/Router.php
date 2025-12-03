<?php 

class Router{

    protected $currentController = '';
    protected $currentAction = 'index';
    protected $args = [];
    
    public function __construct(){
        $url = $this->getCurrentUrl();
        if($url == null){
            $this->currentController = 'Index';
        }else if ($url[1] == ''){
            $this->currentController = ucwords($url[0]);
        }else{
            $this->currentController = ucwords($url[0]);
            $this->currentAction = $url[1];
            $this->args = array_values($url[2]);
        }
        //load appropriate controller, action and arguments

        $file_path = "../application/Controllers/$this->currentController.php";
        if(file_exists($file_path)){
            require_once "../application/Controllers/$this->currentController.php";
            $controller = $this->currentController = new $this->currentController();
            $action = $this->currentAction;
            if(method_exists($controller, $action)){
                $controller->$action($this->args);
            }else{
                //exit("<b>Error 404:</b> Resource does not exist");
                redirect_with_args('404', array('page'=>strtolower($this->currentController)));
            }
        }else{
            //exit("<h1>Error 404: The resource you're looking for does not exist.</h1>");
            redirect_with_args('404', array('page'=>strtolower($this->currentController)));
        }

    }

    public function getCurrentUrl(){
        $controller = '';
        $action = '';
        $params = '';
        if (isset($_GET['url'])){
            $url = rtrim($_GET['url'], '/');
            $url = explode('/', $url);
            $controller = $url[0];
            unset($url[0]);
            if(array_key_exists(1, $url)){
                $action = $url[1];
                unset($url[1]);
                $params = $url;

            }
            return [$controller, $action, $params];
        }
        
    }
}

?>