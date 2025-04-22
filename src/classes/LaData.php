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
        array $pxParameterData,
        string $pxResultsetType, //= self::RESULTSET_ROWS,
        string $pxFetchType, // = self::FETCHSTYLE_ASSOC
    )
    {
        //
        $laConfig = new LaConfig();
        $dbFxSpecs = $laConfig->getConfigAttr('data', 'client_operation_to_db_fx', 'client_fx', $pxOperation);



        $dbSchema = $dbFxSpecs[self::SCHEMA_KEY];
        $dbFx = $dbFxSpecs[self::FX_KEY];
        $qryFile = dirname(__FILE__, 3) . "/" . $dbFxSpecs[self::FX_SQLFILE];
        $qry = file_get_contents($qryFile);



        $parameterData = $this->convertPxs($dbSchema, $dbFx, $pxParameterData);

        //
        $crudData = $this->dispatchCrudOperation($qry, $pxCrudType, $parameterData, $pxResultsetType, $pxFetchType);

        return $crudData;

    }

    /**
     * 
     */
    


}

?>