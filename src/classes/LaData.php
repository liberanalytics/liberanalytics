<?php

require_once('LaDataPxs.php');
require_once('LaConfig.php');

/**
 * todo: merge data from handler into params.
 */

class LaData extends LaDataPxs {

    private const SCHEMA_KEY = 'schema';
    private const FX_KEY = 'fx';
    private const FX_SQLFILE = 'sqlfile';
    private const FX_PX_OPERATION = 'getfxparameters';
    

    function __construct()
    {
        parent::__construct();
                
    }

    /**
     * 
     */
    public function crudData
    (
        string $pxOperation,
        string $pxCrudType,
        array $pxClientPxs = [],
        string $pxResultsetType = self::RESULTSET_ROWS,
        string $pxFetchType = self::FETCHSTYLE_ASSOC
    )
    {
        //
        $laConfig = new LaConfig();
        $dbFxSpecs = $laConfig->getConfigAttr('data', 'crud', 'operation', $pxOperation);
        $dbFxPxSpecs = $laConfig->getConfigAttr('data', 'crud', 'operation', self::FX_PX_OPERATION);
        $dbFxQry = $this->getCrudQryFromSqlFile($dbFxSpecs);

        //
        $dbFxPxQry = $this->getCrudQryFromSqlFile($dbFxPxSpecs);
        $dbFxPxs = [
            'px_fx_schema' => $dbFxSpecs[self::SCHEMA_KEY], 
            'px_fx_name' => $dbFxSpecs[self::FX_KEY]
        ];

        try
        {
            // Get the default p(x)'s and place the incoming p(x) data over the defaults based on array keys.
            $fxPxData = $this->dispatchCrudOperation(
                $dbFxPxQry, 
                self::CRUD_READ, 
                $dbFxPxs, 
                self::RESULTSET_ROWS, 
                self::FETCHSTYLE_ASSOC
            );
            $pxPxData = $this->convertPxs($fxPxData, $pxClientPxs);

            //
            $crudData = $this->dispatchCrudOperation($dbFxQry, $pxCrudType, $pxPxData, $pxResultsetType, $pxFetchType);
        }
        catch(Throwable $error)
        {

        }
        finally
        {

        }

        return $crudData;

    }

    /**
     * 
     */
    private function getCrudQryFromSqlFile(array $pxCrudOperationSpecs)
    {
        $dbSchema = $pxCrudOperationSpecs[self::SCHEMA_KEY];
        $dbFx = $pxCrudOperationSpecs[self::FX_KEY];
        $qryFile = dirname(__FILE__, 3) . "/" . $pxCrudOperationSpecs[self::FX_SQLFILE];
        $qry = file_get_contents($qryFile);
        return $qry;
    }


}

?>