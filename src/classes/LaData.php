<?php
namespace classes;

require_once('LaDataPxs.php');
require_once(dirname(__DIR__) . "/" . "enums/CrudOperation.php");
require_once(dirname(__DIR__) . "/" . "enums/CrudType.php");
require_once(dirname(__DIR__) . "/" . "enums/CrudResultsetType.php");
require_once(dirname(__DIR__) . "/" . "enums/CrudFetchStyle.php");
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