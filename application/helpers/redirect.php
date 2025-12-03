<?php

function redirect($urlAlias){
    //print_r(ROUTES);
    if (array_key_exists($urlAlias, ROUTES)){
        $url = ROUTES[$urlAlias];
        $url = URL_ROOT.$url;
        header("location: $url");
    }else{
        exit("<b>Redirect Error: </b> Cannot redirect to $urlAlias because it's not registered");
    }
}

function redirect_with_args($urlAlias, array $args){
    if (array_key_exists($urlAlias, ROUTES)){
        $orig_url = ROUTES[$urlAlias];
        $urlArgs = '';
        if($args){
            foreach($args as $key => $value){
                $urlArgs .= $key.'='.$value.'&';
            }
            $urlArgs = rtrim($urlArgs, '&');
        }
        $orig_url = $orig_url.'?'.$urlArgs;
        $orig_url = URL_ROOT.$orig_url;
        header("location: $orig_url");
    }else{
        exit("<b>Redirect Error:</b> Cannot Redirect to $urlAlias because it's not registered");
    }
}

function show_movie_type($id){
  $db = new Database();
  $statement = $db->query("SELECT type FROM movie_type WHERE id=?", $params=[$id], $fetchMode='fetch');
  if($statement){return $statement->type;}else{redirect('404');}
}


?>
