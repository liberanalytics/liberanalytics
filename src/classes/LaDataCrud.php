<?php

require_once('LaDataDb.php');

class LaDataCrud extends LaDataDb {

    protected const FX_SCHEMA = 'schema';
    protected const FX_NAME = 'fx';
    protected const FX_SQLFILE = 'sqlfile';
    public const CRUD_CREATE = 'create';
    public const CRUD_READ = 'read';
    public const CRUD_UPDATE = 'update';
    public const CRUD_DELETE = 'delete';     
    public const RESULTSET_ARRAY = 'array';
    public const RESULTSET_ROWS = 'rows';
    public const RESULTSET_SCALAR = 'scalar';       

    protected function __construct()
    {
        parent::__construct();       
    }

    /**
     * 
     */
    protected function getCrudQryFromSqlFile(string $pxCrudOperation)
    {
        //
        $crudOperationSpecs = $this->laConfig->getConfigAttr('data', 'crud', 'operation', $pxCrudOperation);
        $crudQryFile = dirname(__FILE__, 3) . "/" . $crudOperationSpecs[self::FX_SQLFILE];
        $getCrudQryFromSqlFile = file_get_contents($crudQryFile);
        return $getCrudQryFromSqlFile;
    }

    /**
     * 
     */
    protected function dispatchCrudOperation(
        string $pxCrudOperation, 
        string $pxCrudType, 
        array $pxParams, 
        string $pxResultsetType, 
        string $pxFetchStyle
    )
    {
        $qry = $this->getCrudQryFromSqlFile($pxCrudOperation);
        $executeCrudOperation = $this->executeCrudOperation($qry, $pxParams, $pxFetchStyle);
        $dispatchCrudOperation = match($pxResultsetType)
        {
            self::RESULTSET_ARRAY =>  $executeCrudOperation[0],
            self::RESULTSET_SCALAR => $executeCrudOperation[0][0],
            self::RESULTSET_ROWS => $executeCrudOperation,
            default => $executeCrudOperation
        };    

        return $dispatchCrudOperation;

    }

}

?>