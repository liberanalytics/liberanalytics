<?php

require_once(dirname(__DIR__, 2) . "/" . 'vendor/autoload.php');

use classes\LaData;
use enums\CrudOperation;
use enums\CrudType;
use enums\CrudResultsetType;
use enums\CrudFetchStyle;

$laData = new LaData();   

// $posts = $laData->crudData(
//     CrudOperation::GETPOSTS,
//     CrudType::CRUDREAD,
//     CrudResultsetType::RESULTSET_ROWS,
//     CrudFetchStyle::FETCHSTYLE_ASSOC,
//     []    
// );

$post = $laData->crudData(
    CrudOperation::GETPOSTS,
    CrudType::CRUDREAD,
    CrudResultsetType::RESULTSET_ROWS,
    CrudFetchStyle::FETCHSTYLE_ASSOC,
    [ 'post_name' => "whydata"]						
);

$a=1;

?>
