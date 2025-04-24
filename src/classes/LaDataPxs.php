<?php

include('LaDataCrud.php');
include('LaDataVx.php');


class LaDataPxs extends LaDataVx {

    private const FX_PX_OPERATION = 'getfxparameters';
    private const COL_PX_KEY = 'px_name';
    private const COL_PX_DEFAULTVALUE = 'px_default_value';
    private const COL_PX_TYPE = 'px_type';
    private array $dbPxs;
    private array $clientPxs;

    function __construct()
    {
        parent::__construct();
    }

    /**
     * 
     */
    private function setDbPxs($pxDbPxData)
    {
        //
        $this->dbPxs = [];
        foreach($pxDbPxData as $row => $values)
        {
            //
            $pxNameKey = $values[self::COL_PX_KEY];

            //
            foreach($values as $key => $value)
            {
                //
                if($key == self::COL_PX_DEFAULTVALUE)
                {
                    $value = $this->coerceDbDefaultPxDataType($values[self::COL_PX_DEFAULTVALUE]);
                }
                else
                {
                    $value = $value;
                }
                $this->dbPxs[$pxNameKey][$key] = $value;
            }
        }       
    }

    /**
     * 
     */
    private function setClientPxs(string $pxCrudOperation, array $pxClientPxData)
    {
        //
        $this->clientPxs = [];
        foreach ($pxClientPxData as $key => $value)
        {
            // Convert client key to db function p(x) name.
            $pxKey = $this->convertClientPxKey($key);

            // Iterate through client p(x)'s and match to the database defaults by key to determine type.
            if (array_key_exists($pxKey, $this->dbPxs))
            {
                //
                $dbPx = $this->dbPxs[$pxKey];
                $pxType = $dbPx[self::COL_PX_TYPE];
                $pxVal = $this->vxHandler($pxCrudOperation, $pxKey, $value, $pxType);
            }
            else
            {
                throw new Exception("Invalid key: {$key}");
            }

            $this->clientPxs[$pxKey] = $pxVal;
        };     
    }

    /**
     * 
     */
    private function convertClientPxKey(string $pxKey): string
    {
        $convertClientPxKey = 'px_'. $pxKey;
        return $convertClientPxKey;
    }

    /**
     * 
     */
    private function coerceDbDefaultPxDataType($pxValue)
    {

        //
        $pxValueLower = strtolower($pxValue);
        $coercePxDataType = match($pxValueLower)
        {
            'null' => null,
            'false' => false,           
            default => null
        };
        //
        if ($coercePxDataType === null && preg_match('/^\d+$/', $pxValue))
        {
            $coercePxDataType = (int)$pxValue;
        }
        return $coercePxDataType;
    }


    /**
     * 
     */
    protected function convertPxs(string $pxCrudOperation, array $pxClientPxData): array
    {

        //
        $dbFxPxQry = $this->getCrudQryFromSqlFile(self::FX_PX_OPERATION);


        // $dbFxPxs = [
        //     'px_fx_schema' => $dbFxSpecs[self::SCHEMA_KEY], 
        //     'px_fx_name' => $dbFxSpecs[self::FX_KEY]
        // ];



        // Get the default p(x)'s and place the incoming p(x) data over the defaults based on array keys.
        $dbFxPxs = $this->getCrudDbFxSpecs($pxCrudOperation);
        $fxPxData = $this->dispatchCrudOperation(
            $dbFxPxQry, 
            self::CRUD_READ, 
            $dbFxPxs, 
            self::RESULTSET_ROWS, 
            self::FETCHSTYLE_ASSOC
        );        

        //
        $this->setDbPxs($fxPxData);
        $this->setClientPxs($pxCrudOperation, $pxClientPxData);
        return array_merge($this->dbPxs, $this->clientPxs);
    }

}

?>