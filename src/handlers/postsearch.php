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

    // $pcs = $laData->crudData(
    //     'getpostcategories',
    //     $laData::CRUD_READ,
    //     ['post_category' => 'Analytics'],
    //     $laData::RESULTSET_ROWS,
    //     $laData::FETCHSTYLE_ASSOC        
    // );
    $a = 1;


    ?>