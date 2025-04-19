<?php

require_once('LaDataPxs.php');
require_once('LaConfig.php');

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
    public function crudData(string $pxOperation, string $pxCrudType, array $pxParameterData, string $pxResultsetType, string $pxFetchType)
    {
        //
        $laConfig = new LaConfig();
        $dbFxSpecs = $laConfig->getConfigAttr('data', 'db_to_client_fx', 'client_fx', $pxOperation);
        $dbSchema = $dbFxSpecs[self::SCHEMA_KEY];
        $dbFx = $dbFxSpecs[self::FX_KEY];


        $qryFile = dirname(__FILE__, 3) . "/" . $dbFxSpecs[self::FX_SQLFILE];
        $qry = file_get_contents($qryFile);
        $parameterData = $this->convertPxs($dbSchema, $dbFx, $pxParameterData);

        //
        
        return $crudData;

    }

    


}

?>