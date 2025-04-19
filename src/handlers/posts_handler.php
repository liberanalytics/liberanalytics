<?php

    require_once(dirname(__DIR__) . "/" . "classes/LaData.php");
    require_once(dirname(__DIR__) . "/" . "classes/LaVx.php");
    require_once(dirname(__DIR__) . "/" . "classes/LaDataDb.php");

    $laData = new LaData();
    $laVx = new LaVx();    
    // $laData->openConn();



    $pc = $laData->crudData(
        'getpostcategories',
        $laData::CRUD_READ,
        [], 
        LaDataDb::RESULTSET_ROWS,
        LaDataDb::FETCHSTYLE_ASSOC
    );

    $inputData = [
        'post_category' => 'Analytics',
        'tags' => 'sql rdbms theory'
    ];

    // $laData->closeConn();

?>