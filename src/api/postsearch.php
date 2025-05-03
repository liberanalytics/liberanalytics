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
    [ 
        'featured'  =>  1000,
        'post_category' => "AWS",
        'post_tags'     => 'Encryption, Datasets, PostgreSQL in PHP'
    ]						
);

// px_featured INTEGER DEFAULT 1000
// ,px_post_name TEXT[] DEFAULT NULL
// ,px_post_category TEXT[] DEFAULT NULL
// ,px_post_tags TEXT[] DEFAULT NULL
// ,px_post_keywords TEXT[] DEFAULT NULL
// ,px_order_by TEXT DEFAULT NULL
// ,px_order_by_ascdesc TEXT DEFAULT NULL
// ,px_limit INTEGER DEFAULT NULL	

$a=1;

?>
