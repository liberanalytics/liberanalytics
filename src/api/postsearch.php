<?php

require_once(dirname(__DIR__) . "/" . "classes/LaData.php");
require_once(dirname(__DIR__) . "/" . "enums/CrudOperation.php");
require_once(dirname(__DIR__) . "/" . "enums/CrudType.php");
require_once(dirname(__DIR__) . "/" . "enums/CrudResultsetType.php");
require_once(dirname(__DIR__) . "/" . "enums/CrudFetchStyle.php");

use classes\LaData;
use enums\CrudOperation;
use enums\CrudType;
use enums\CrudResultsetType;
use enums\CrudFetchStyle;

$laData = new LaData();   

$posts = $laData->crudData(
    CrudOperation::GETPOSTS,
    CrudType::CRUDREAD,
    ['post_category' => 'RDBMS',
    'limit' => 100], 
    CrudResultsetType::RESULTSET_ROWS,
    CrudFetchStyle::FETCHSTYLE_ASSOC
);

?>