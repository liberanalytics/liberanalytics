<?php

    require_once(dirname(__DIR__) . "/" . "classes/LaData.php");
    require_once(dirname(__DIR__) . "/" . "classes/LaVx.php");
    require_once(dirname(__DIR__) . "/" . "classes/LaDataDb.php");

    $laData = new LaData();
    $laVx = new LaVx();    
    // $laData->openConn();

    // $posts = $laData->crudData(
    //     'getPosts',
    //     [
    //         TRUE, //bool $pxFeatured
    //         'whydata', //string $pxPostName = NULL      
    //         NULL, //string $pxPostCategory = NULL,
    //         NULL, // '{"sql", "python", "rdbms"}', // array $pxPostTags = NULL,
    //         NULL, // array $pxPostKeywords = NULL,
    //         NULL, // string $pxOrderBy = NULL,
    //         NULL, // string $pxOrderByAscDesc = NULL,
    //         NULL, // int $pxLimit = NULL
    //     ],
    //     LaDataDb::RESULTSET_ROWS,
    //     LaDataDb::FETCHSTYLE_ASSOC
    // );

    $pc = $laData->crudData(
        'getPostCategories', 
        [], 
        LaDataDb::RESULTSET_ROWS,
        LaDataDb::FETCHSTYLE_ASSOC
    );

    $inputData = [
        'post_category' => 'Analytics',
        'tags' => 'sql rdbms theory'
    ];

    

?>