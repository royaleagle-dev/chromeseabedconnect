<?php

spl_autoload_register(function($class){
    $dirs = ['../application/helpers', '../application/Core', '/application/Models',];
    $max_count = count($dirs);
    $count = 0;
    foreach ($dirs as $dir){
        $filePath = $dir."/$class.php";
        if (file_exists($filePath)){
            require_once "$filePath";
        }else{
            $count += 1;
        }
    }
    if($count >= $max_count){
        exit("<strong>System Error:</strong> Cannot load class $class");
    }
});

require_once "../application/config/config.php";
require_once "../application/config/routes.php";
require_once "../application/config/colors.php";
//require_once "../application/Core/Login.php";
require_once "../application/helpers/redirect.php";
require_once "../application/helpers/dataHelper.php";

?>
