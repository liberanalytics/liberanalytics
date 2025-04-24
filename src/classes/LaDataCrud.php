<?php

require_once('LaDataDb.php');

class LaDataCrud extends LaDataDb {

    private const FX_SCHEMA = 'schema';
    private const FX_NAME = 'fx';
    private const FX_SQLFILE = 'sqlfile';    
    

    protected function __construct()
    {
        parent::__construct();                
    }

    /**
     * 
     */
    protected function getCrudDbFxSpecs(string $pxOperation): array
    {
        //
        $operationDbFxSpecs = $this->laConfig->getConfigAttr('data', 'crud', 'operation', $pxOperation);
        $getCrudDbFxSpecs = [
            'px_fx_schema' => $operationDbFxSpecs[self::FX_SCHEMA], 
            'px_fx_name' => $operationDbFxSpecs[self::FX_NAME]
        ];
        return $getCrudDbFxSpecs;
    }

    /**
     * 
     */
    protected function getCrudQryFromSqlFile(string $pxOperation)
    {
        $crudOperationSpecs = $this->laConfig->getConfigAttr('data', 'crud', 'operation', $pxOperation);
        // $dbSchema = $pxCrudOperationSpecs[self::SCHEMA_KEY];
        // $dbFx = $pxCrudOperationSpecs[self::FX_KEY];
        $crudQryFile = dirname(__FILE__, 3) . "/" . $crudOperationSpecs[self::FX_SQLFILE];
        $getCrudQryFromSqlFile = file_get_contents($crudQryFile);
        return $getCrudQryFromSqlFile;
    }

}

?>