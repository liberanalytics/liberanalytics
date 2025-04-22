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
    private function convertPxKey(array $pxKeySearch, array $pxKeyReplace, $pxSubject): string
    {
        $convertPxKey = str_replace($pxKeySearch, $pxKeyReplace, $pxSubject);
        return $convertPxKey;
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
                    $key = $this->convertPxKey(['px'], [''], $pxVal[self::COL_PX_KEY]);
                    $val = $this->coerceDbDefaultPxDataType($pxVal[self::COL_PX_DEFAULTVALUE]);
                    break;
                
                case self::PX_CLIENT:
                    $key = $this->convertPxKey(['_'], [""], $pxPx);
                    $val = $pxVal;
                    break;
            };            

            $convertPxsKeyValues[$key] = $val;
        }
        return $convertPxsKeyValues;
    }

    private function convertClientPxs(array $pxClientPxs): array
    {

    }

    /**
     * 
     */
    protected function convertPxs(array $pxDefaultPxData, array $pxClientPxData): array
    {

        $convertDefaultPxs = $this->convertPxsKeyValues($pxDefaultPxData, self::PX_DB_DEFAULTS);
        $convertClientPxs = $this->convertPxsKeyValues($pxClientPxData, self::PX_CLIENT);

        $a = 1;

        // If there is parameter data, convert the data to a LaData friendly format to use in PDO paramter binding.
        if (!empty($pxClientPxData))
        {

        }

        // then do an array_merge with array_key_intersection from the $pxParameterData to get a return array
        // that consists of any passed parameters combined w/ the default values where the passed parameters
        // are precedent over defaults.

        $a = 1;

        return $convertPxs;
    }



}

?>