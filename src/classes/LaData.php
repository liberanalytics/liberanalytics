<?php
namespace classes;

require_once('LaDataPxs.php');
require_once(dirname(__DIR__ . "/" . "enums/CrudOperation.php"));

use enums\CrudOperation;

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
        string $pxCrudType,
        array $pxClientPxs = [],
        string $pxResultsetType = self::RESULTSET_ROWS,
        string $pxFetchType = self::FETCHSTYLE_ASSOC
    )
    {
        //
        try
        {
            $pxPxData = $this->convertPxs($pxCrudOperation->value, $pxClientPxs);
            $crudData = $this->dispatchCrudOperation($pxCrudOperation->value, $pxCrudType, $pxPxData, $pxResultsetType, $pxFetchType);
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