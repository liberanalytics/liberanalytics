<?php

    require_once(dirname(__DIR__, 2) . "/" . 'vendor/autoload.php');

    use classes\LaData;
    use enums\CrudOperation;
    use enums\CrudType;
    use enums\CrudResultsetType;
    use enums\CrudFetchStyle;

    $laData = new LaData();  

    // Get the liber authoritative domain data.
    $postCategories = $laData->crudData(
        CrudOperation::GETPOSTCATEGORIES,
        CrudType::CRUDREAD,
        CrudResultsetType::RESULTSET_ROWS,
        CrudFetchStyle::FETCHSTYLE_COL
    );

    $postTags = $laData->crudData(
        CrudOperation::GETPOSTTAGS,
        CrudType::CRUDREAD,
        CrudResultsetType::RESULTSET_ROWS,
        CrudFetchStyle::FETCHSTYLE_COL
    );

    $a=1;

?>