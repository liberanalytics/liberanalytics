<?php

include('LaDataDb.php');


class LaDataPxs extends LaDataDb {

    private const COL_PX_KEY = 'px_name';
    private const COL_PX_DEFAULTVALUE = 'px_default_value';

    function __construct()
    {
        parent::__construct();
    }

    /**
     * 
     */
    private function convertDatabasePxKey($pxKey)
    {
        $convertDatabasePxKey = str_replace("px", "", $pxKey);
        return $convertDatabasePxKey;
    }

    /**
     * 
     */
    private function coercePxDataType($pxValue)
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
    private function convertDbPxDefaults(array $pxDbPxDefaults): array
    {
        $convertDbPxDefaults = [];
        foreach ($pxDbPxDefaults as $pxDbPxDefault)
        {
            $key = $this->convertDatabasePxKey($pxDbPxDefault[self::COL_PX_KEY]);
            $value = $this->coercePxDataType($pxDbPxDefault[self::COL_PX_DEFAULTVALUE]);
            $convertDbPxDefaults[$key] = $value;
        }
        return $convertDbPxDefaults;
    }

    private function convertClientPxs(array $pxClientPxs): array
    {

    }

    /**
     * 
     */
    protected function convertPxs(string $pxSchema, string $pxFunction, array $pxParameterData)
    {

        //
        $qry = 
        "SELECT

        " .     self::COL_PX_KEY .
        ",
        " .   self::COL_PX_DEFAULTVALUE . "
        
        FROM meta.fx_get_fx_pxs";
        $dbPxDefaultsAsRows = $this->fetchData($qry, [$pxSchema, $pxFunction], self::RESULTSET_ROWS, self::FETCHSTYLE_ASSOC);
        $dbPxDefaultsAsArray = $this->convertDbPxDefaults($dbPxDefaultsAsRows);

        // If there is parameter data, convert the data to a LaData friendly format to use in PDO paramter binding.
        if (!empty($pxParameterData))
        {

        }

        $convertPxs = $dbPxDefaultsAsArray;

        // then do an array_merge with array_key_intersection from the $pxParameterData to get a return array
        // that consists of any passed parameters combined w/ the default values where the passed parameters
        // are precedent over defaults.

        $a = 1;

        return $convertPxs;
    }



}

?>