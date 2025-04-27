<?php

namespace classes;

use enums\CrudOperation;
use enums\CrudType;
use enums\CrudResultsetType;
use enums\CrudFetchStyle;

class LaData extends LaDataPxs {

    public function __construct()
    {
        parent::__construct();
                
    }

    /**
     * 
     */
    public function crudData(
        CrudOperation $pxCrudOperation,
        CrudType $pxCrudType,
        array $pxClientPxs = [],
        CrudResultsetType $pxResultsetType,
        CrudFetchStyle $pxFetchStyle
    )
    {
        //
        try
        {
            $pxPxData = $this->convertPxs($pxCrudOperation, $pxClientPxs);
            $crudData = $this->dispatchCrudOperation($pxCrudOperation, $pxCrudType, $pxPxData, $pxResultsetType, $pxFetchStyle);
        }
        catch (\Throwable $error)
        {
            echo $error->getMessage();
        }
        finally
        {
            return $crudData;
        }
    }

}

?>