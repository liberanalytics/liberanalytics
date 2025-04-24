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
        $dbFxQry = $this->getCrudQryFromSqlFile($pxCrudOperation);

        try
        {
            //
            $pxPxData = $this->convertPxs($pxCrudOperation, $pxClientPxs);
            $crudData = $this->dispatchCrudOperation($dbFxQry, $pxCrudType, $pxPxData, $pxResultsetType, $pxFetchType);
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