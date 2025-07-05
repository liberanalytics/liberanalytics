<?php

require_once(dirname(__DIR__, 2) . "/" . 'vendor/autoload.php');

use classes\LaData;
use enums\CrudOperation;
use enums\CrudType;
use enums\CrudResultsetType;
use enums\CrudFetchStyle;

$laData = new LaData();  

$contactMsg = $laData->crudData(
    CrudOperation::CREATECONTACT,
    CrudType::CRUDCREATE,
    CrudResultsetType::RESULTSET_ROWS,
    CrudFetchStyle::FETCHSTYLE_ASSOC,
    [
        'first_name' => 'phptestfname1',
        'last_name' => 'phptestlname1',
        'email' => 'test@test.com',
        'contact_message' => 'phptestmsg1'
    ]
);

return $contactMsg;

$a = 1;

?>