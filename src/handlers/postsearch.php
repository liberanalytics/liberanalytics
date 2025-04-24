<?php

    require_once(dirname(__DIR__) . "/" . "classes/LaData.php");
 
    $laData = new LaData();   
 
    $posts = $laData->crudData(
        'getposts',
        $laData::CRUD_READ,
        ['post_name' => 'whydata'], 
        $laData::RESULTSET_ROWS,
        $laData::FETCHSTYLE_ASSOC
    );

    ?>