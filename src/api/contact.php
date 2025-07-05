<?php

require_once(dirname(__DIR__, 2) . "/" . 'vendor/autoload.php');

use classes\LaData;
use enums\CrudOperation;
use enums\CrudType;
use enums\CrudResultsetType;
use enums\CrudFetchStyle;

$laData = new LaData();  

$postTags = $laData->crudData(
    CrudOperation::GETPOSTTAGSLIKE,
    CrudType::CRUDREAD,
    CrudResultsetType::RESULTSET_ROWS,
    CrudFetchStyle::FETCHSTYLE_ASSOC,
    ['post_tag_term' => $postTagTerm]
);

return $postTags;


?>