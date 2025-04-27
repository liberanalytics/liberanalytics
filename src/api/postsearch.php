<?php

    require_once(dirname(__DIR__) . "/" . "classes/LaData.php");
    require_once(dirname(__DIR__) . "/" . "enums/CrudOperation.php");

    use classes\LaData;
    use enums\CrudOperation;
 
    $laData = new LaData();   
 
    $posts = $laData->crudData(
        CrudOperation::GETPOSTS,
        $laData::CRUD_READ,
        ['post_category' => 'RDBMS',
        'limit' => 100], 
        $laData::RESULTSET_ROWS,
        $laData::FETCHSTYLE_ASSOC
    );

    $a = 1;

?>