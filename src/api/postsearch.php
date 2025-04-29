<?php

require_once(dirname(__DIR__, 2) . "/" . 'vendor/autoload.php');

use classes\LaData;
use enums\CrudOperation;
use enums\CrudType;
use enums\CrudResultsetType;
use enums\CrudFetchStyle;

$laData = new LaData();   

$posts = $laData->crudData(
    CrudOperation::GETPOSTS,
    CrudType::CRUDREAD,
    [
        'order_by' => 'featured',
        'order_by_ascdesc' => 'ASC'
    ],
    CrudResultsetType::RESULTSET_ROWS,
    CrudFetchStyle::FETCHSTYLE_ASSOC
);

$a = 1;


    // 'post_tags' => ' rd(bms&, sql, , j^oin, exep%lan- Ttest, ',
    // 'limit' => 100

?>