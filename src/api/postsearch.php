<?php

require_once(dirname(__DIR__, 2) . "/" . 'vendor/autoload.php');

use enums\CrudOperation;
use enums\CrudType;
use enums\CrudResultsetType;
use enums\CrudFetchStyle;

$laData = new classes\LaData();
$laViewConverter = new classes\LaViewConverter();

$posts = $laData->crudData(
    CrudOperation::GETPOSTS,
    CrudType::CRUDREAD,
    CrudResultsetType::RESULTSET_ROWS,
    CrudFetchStyle::FETCHSTYLE_ASSOC,
    $requestPxs						
);

return $posts;

?>
