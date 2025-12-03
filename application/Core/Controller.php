<?php

class Controller{

    public function loadTemplate($templatePath, $data){
        $filePath = "../application/Templates/$templatePath.php";
        if($file_exists($filePath)){
            new Template($template, $data);
        }

    }

    public function loadModel($modelClass){
        $filePath = "../application/Models/$modelClass.php";
        if(file_exists($filePath)){
            require_once "$filePath";
            return new $modelClass();
        }else{
            exit("<b>Model Error:</b>  Model $modelClass does not exist");
        }
    }

}

?>