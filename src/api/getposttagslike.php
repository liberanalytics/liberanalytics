<?php

require_once(dirname(__DIR__, 2) . "/" . 'vendor/autoload.php');

use classes\LaData;
use enums\CrudOperation;
use enums\CrudType;
use enums\CrudResultsetType;
use enums\CrudFetchStyle;

$laData = new LaData();  

// $postPxs = [];
// // $postCategory = $requestPxs['postCategory'];
// $postCategory = ($requestPxs['postCategory'] == 'Select Category' ? NULL : $requestPxs['postCategory']);
// $postPxs =[
//     'post_category' => $postCategory
// ];

$postTags = $laData->crudData(
    CrudOperation::GETPOSTTAGSLIKE,
    CrudType::CRUDREAD,
    CrudResultsetType::RESULTSET_ROWS,
    CrudFetchStyle::FETCHSTYLE_ASSOC,
    ['post_tag_term' => 'AW']
);

return $postTags;

?>
