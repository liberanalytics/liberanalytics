<?php

    require_once(dirname(__DIR__) . "/" . "classes/LaData.php");
 
    $laData = new LaData();   
 
    $posts = $laData->crudData(
        'getposts',
        $laData::CRUD_READ,
        ['post_category' => 'RDBMS',
        'limit' => 100], 
        $laData::RESULTSET_ROWS,
        $laData::FETCHSTYLE_ASSOC
    );

    $a = 1;

    ?>