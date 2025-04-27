<?php

namespace classes;

require_once('LaDataDb.php');
require_once(dirname(__DIR__) . "/" . "enums/CrudOperation.php");
require_once(dirname(__DIR__) . "/" . "enums/CrudType.php");
require_once(dirname(__DIR__) . "/" . "enums/CrudResultsetType.php");
require_once(dirname(__DIR__) . "/" . "enums/CrudFetchStyle.php");

use enums\CrudOperation;
use enums\CrudFetchStyle;
use enums\CrudResultsetType;
use enums\CrudType;

class LaDataCrud extends LaDataDb {
     
    protected const COL_PX_KEY = 'px_name';
    protected const COL_PX_DEFAULTVALUE = 'px_default_value';
    protected const COL_PX_TYPE = 'px_type';
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
    private function getCrudQryFromSqlFile(CrudOperation $pxCrudOperation)
    {
        //
        $crudOperationSpecs = $this->laConfig->getConfigAttr('data', 'crud', 'operation', $pxCrudOperation->value);
        $crudQryFile = dirname(__FILE__, 3) . "/" . $crudOperationSpecs[self::FX_SQLFILE];
        $getCrudQryFromSqlFile = file_get_contents($crudQryFile);
        return $getCrudQryFromSqlFile;
    }

    /**
     * 
     */
    protected function getCrudDbFxPxSpecs(CrudOperation $pxCrudOperation): array
    {
        //
        $operationDbFxSpecs = $this->laConfig->getConfigAttr('data', 'crud', 'operation', $pxCrudOperation->value);
        $dbFxSpecs = [
            'px_fx_schema' => $operationDbFxSpecs[self::FX_SCHEMA], 
            'px_fx_name' => $operationDbFxSpecs[self::FX_NAME]
        ];

        //     
        $getCrudDbFxSpecsAsRows = $this->dispatchCrudOperation(
            CrudOperation::GETFXPARAMETERS, 
            CrudType::CRUDREAD, 
            $dbFxSpecs, 
            CrudResultsetType::RESULTSET_ROWS, 
            CrudFetchStyle::FETCHSTYLE_ASSOC
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
        CrudOperation $pxCrudOperation, 
        CrudType $pxCrudType, 
        array $pxParams, 
        CrudResultsetType $pxResultsetType, 
        CrudFetchStyle $pxFetchStyle
    )
    {
        $qry = $this->getCrudQryFromSqlFile($pxCrudOperation);
        $executeCrudOperation = $this->executeCrudOperation($qry, $pxParams, $pxFetchStyle);
        $dispatchCrudOperation = match($pxResultsetType)
        {
            CrudResultsetType::RESULTSET_ARRAY =>  $executeCrudOperation[0],
            CrudResultsetType::RESULTSET_SCALAR => $executeCrudOperation[0][0],
            CrudResultsetType::RESULTSET_ROWS => $executeCrudOperation
            // default => $executeCrudOperation
        };    

        return $dispatchCrudOperation;

    }

}

?>