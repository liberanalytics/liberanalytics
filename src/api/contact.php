<?php

require_once(dirname(__DIR__, 2) . "/" . 'vendor/autoload.php');

use classes\LaData;
use enums\CrudOperation;
use enums\CrudType;
use enums\CrudResultsetType;
use enums\CrudFetchStyle;

$laData = new LaData();  

$contactData = $laData->crudData(
    CrudOperation::CREATECONTACT,
    CrudType::CRUDCREATE,
    CrudResultsetType::RESULTSET_ARRAY,
    CrudFetchStyle::FETCHSTYLE_ASSOC,
    $requestPxs
);

return $contactData;

?>