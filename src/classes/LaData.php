<?php

require_once('LaDataPxs.php');
require_once('LaConfig.php');

class LaData extends LaDataPxs {

    private const SCHEMA_KEY = 'schema';
    private const FX_KEY = 'fx';
    private const FX_SQLFILE = 'sqlfile';
    public const CRUD_CREATE = 'create';
    public const CRUD_READ = 'read';
    public const CRUD_UPDATE = 'update';
    public const CRUD_DELETE = 'delete';


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
        string $pxResultsetType = self::RESULTSET_ROWS,
        string $pxFetchType = self::FETCHSTYLE_ASSOC
    )
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
        $crudData = match($pxCrudType)
        {
            self::CRUD_READ => $this->fetchData($qry, $parameterData, $pxResultsetType, $pxFetchType)
        };
        return $crudData;

    }

    


}

?>