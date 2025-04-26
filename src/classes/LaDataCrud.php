<?php

require_once('LaDataDb.php');

class LaDataCrud extends LaDataDb {

    public const CRUD_CREATE = 'create';
    public const CRUD_READ = 'read';
    public const CRUD_UPDATE = 'update';
    public const CRUD_DELETE = 'delete';       
    public const RESULTSET_ARRAY = 'array';
    public const RESULTSET_ROWS = 'rows';
    public const RESULTSET_SCALAR = 'scalar';    
    protected const FX_SCHEMA = 'schema';
    protected const FX_NAME = 'fx';
    protected const FX_SQLFILE = 'sqlfile';
    protected const COL_PX_KEY = 'px_name';
    protected const COL_PX_DEFAULTVALUE = 'px_default_value';
    protected const COL_PX_TYPE = 'px_type';
    protected const FX_PX_OPERATION = 'getfxparameters';

    protected function __construct()
    {
        parent::__construct();       
    }

    /**
     * 
     */
    private function getCrudQryFromSqlFile(string $pxCrudOperation)
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
    protected function getCrudDbFxPxSpecs(string $pxCrudOperation): array
    {
        //
        $operationDbFxSpecs = $this->laConfig->getConfigAttr('data', 'crud', 'operation', $pxCrudOperation);
        $dbFxSpecs = [
            'px_fx_schema' => $operationDbFxSpecs[self::FX_SCHEMA], 
            'px_fx_name' => $operationDbFxSpecs[self::FX_NAME]
        ];

        //     
        $getCrudDbFxSpecsAsRows = $this->dispatchCrudOperation(
            self::FX_PX_OPERATION, 
            self::CRUD_READ, 
            $dbFxSpecs, 
            self::RESULTSET_ROWS, 
            self::FETCHSTYLE_ASSOC
        );

        foreach($getCrudDbFxSpecsAsRows as $row => $values)
        {
            $pxNameKey = $values[self::COL_PX_KEY];
            foreach($values as $key => $value)
            {
                $getCrudDbFxSpecs[$pxNameKey][$key] = $value;
            }
        }

        return $getCrudDbFxSpecs;
    }    

     /**
     * 
     */
    protected function getCrudDbFxPxDefaults(array $pxDbFxPxSpecs): array
    {
        //
        $getCrudDbFxPxDefaults = [];
        foreach ($pxDbFxPxSpecs as $row => $values)
        {
            $pxNameKey = $values[self::COL_PX_KEY];
            foreach ($values as $key => $value)
            {
                //
                if ($key == self::COL_PX_DEFAULTVALUE)
                {
                    $value = $this->coerceDbDefaultPxDataType($values[self::COL_PX_DEFAULTVALUE]);
                    $getCrudDbFxPxDefaults[$pxNameKey] = $value;
                }                
            }
        }
        
        return $getCrudDbFxPxDefaults;
    }

    /**
     * 
     */
    private function coerceDbDefaultPxDataType($pxValue)
    {
        // 
        if (preg_match('/^\d+$/', $pxValue))
        {
            $coercePxDataType = (int)$pxValue;
        }
        else
        {
            $pxValueLower = strtolower($pxValue);
            $coercePxDataType = match($pxValueLower)
            {
                'null' => null,
                'false' => false,           
                default => null
            };
        }

        return $coercePxDataType;
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