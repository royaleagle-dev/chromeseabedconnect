<?php

class Pagination{

    static public function paginate($rowCount, $limit=5){
        $page = isset($_GET['page'])?$_GET['page']:1;
        $start = ($page*$limit) - $limit;
        $pages = ceil($rowCount/$limit);
        $next = $page<$pages?$page+1:$pages;
        $prev = $page==1?1:$page-1;
        return ['page'=>$page, 'start'=>$start, 'next'=>$next, 'prev'=>$prev, 'limit'=>$limit, 'pages'=>$pages];
    }

}
?>