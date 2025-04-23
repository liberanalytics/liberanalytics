<?php

include('LaDataDb.php');


class LaDataPxs extends LaDataDb {

    private CONST COL_PX_KEY = 'px_name';
    private CONST COL_PX_DEFAULTVALUE = 'px_default_value';
    private CONST PX_DB_DEFAULTS = 'px_db_defaults';
    private CONST PX_CLIENT = 'px_client';    

    function __construct()
    {
        parent::__construct();
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
    private function convertPxsKeyValues(array $pxPxs, string $pxSource): array
    {
        $convertPxsKeyValues = [];
        foreach ($pxPxs as $pxPx => $pxVal)
        {
            switch($pxSource)
            {
                case self::PX_DB_DEFAULTS:
                    $key = $pxVal[self::COL_PX_KEY];
                    $val = $this->coerceDbDefaultPxDataType($pxVal[self::COL_PX_DEFAULTVALUE]);
                    break;
                
                case self::PX_CLIENT:
                    $key = $this->convertClientPxKey($pxPx);
                    $val = $pxVal;
                    break;
            };            

            $convertPxsKeyValues[$key] = $val;
        }
        return $convertPxsKeyValues;
    }

    /**
     * 
     */
    protected function convertPxs(array $pxDefaultPxData, array $pxClientPxData): array
    {
        //
        $convertDefaultPxs = $this->convertPxsKeyValues($pxDefaultPxData, self::PX_DB_DEFAULTS);

        // If there is parameter data, convert the data to a LaData friendly format to use in PDO paramter binding.
        $convertClientPxs = [];
        if (!empty($pxClientPxData))
        {
            $convertClientPxs = $this->convertPxsKeyValues($pxClientPxData, self::PX_CLIENT);
        }

        return array_merge($convertDefaultPxs, $convertClientPxs);
    }

}

?>