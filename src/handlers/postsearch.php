<?php

    require_once(dirname(__DIR__) . "/" . "classes/LaData.php");
    require_once(dirname(__DIR__) . "/" . "classes/LaVx.php");
 
    $laData = new LaData();
    $laVx = new LaVx();    
    // $laData->openConn();
    // $laData = new LaData();
    // $laVx = new LaVx();    
    // // $laData->openConn();

 
 
    $pc = $laData->crudData(
        'getposts',
        $laData::CRUD_READ,
        [], 
        $laData::RESULTSET_ROWS,
        $laData::FETCHSTYLE_ASSOC
    );


    // $pc = $laData->crudData(
    //     'getpostcategories',
    //     $laData::CRUD_READ,
    //     [], 
    //     $laData::RESULTSET_ROWS,
    //     $laData::FETCHSTYLE_ASSOC
    // );

    ?>