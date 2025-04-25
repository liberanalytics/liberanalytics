<?php

require_once('LaDataPxs.php');


/**
 * todo: merge data from handler into params.
 */

class LaData extends LaDataPxs {

    public function __construct()
    {
        parent::__construct();
                
    }

    /**
     * 
     */
    public function crudData(
        string $pxCrudOperation,
        string $pxCrudType,
        array $pxClientPxs = [],
        string $pxResultsetType = self::RESULTSET_ROWS,
        string $pxFetchType = self::FETCHSTYLE_ASSOC
    )
    {
        //
        try
        {
            $pxPxData = $this->convertPxs($pxCrudOperation, $pxClientPxs);
            // $dbFxQry = $this->getCrudQryFromSqlFile($pxCrudOperation);
            $crudData = $this->dispatchCrudOperation($pxCrudOperation, $pxCrudType, $pxPxData, $pxResultsetType, $pxFetchType);
        }
        catch(Throwable $error)
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