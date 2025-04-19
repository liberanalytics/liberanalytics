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

        $coercePxDataType = match(str_to_lower($pxValue))
        {
            'null' => NULL,
            'false' => FALSE,
            preg_match('/^([0-9]){1,}$/', $pxValue) => int($pxValue,)

            default => $pxValue
        };
    }

    /**
     * 
     */
    private function convertDbPxDefaults(array $pxDbPxDefaults): array
    {
        $convertDbPxDefaults = [];
        foreach ($pxDbPxDefaults as $pxDbPxDefault)
        {
            $key = $this->convertDatabasePxKeys($pxDbPxDefault[self::COL_PX_KEY]);
            $value = $this->coercePxDataTypes($pxDbPxDefault[self::COL_PX_DEFAULTVALUE]);
            $convertDbPxDefaults[$key] = $value;
        }
        return $convertDbPxDefaults;
    }

    /**
     * 
     */
    protected function convertPxs(string $pxSchema, string $pxFunction, array $pxParameterData)
    {

        $qry = 
        "SELECT

        " .     self::COL_PX_KEY .
        ",
        " .   self::COL_PX_DEFAULTVALUE . "
        
        FROM meta.fx_get_fx_pxs";

        $dbPxDefaultsAsRows = $this->fetchData($qry, [$pxSchema, $pxFunction], self::RESULTSET_ROWS, self::FETCHSTYLE_ASSOC);

        $dbPxDefaultsAsArray = $this->convertDbPxDefaults($dbPxDefaultsAsRows);


        //$dbPxDefaultsAsArray = array_map(fn($ele) => str_replace("px", "", $key) => $this->coercePxDataTypes($val), $dbPxDefaultsAsArray);


        //$dbDefaults = array_map(fn($ele) => [$ele[self::PX_KEY] => $ele[self::PX_DEFAULTVALUE]], $dbPxDefaults);

        // Nested array map with a lambda fx to convert the array of arrays to an array that looks like
        // [
        //      featured => FALSE,
        //      postname => 'whydata'
        // ]
        // but do a data coercion of the px_default_value to get into proper data types, e.g.
        // "FALSE" = FALSE, "NULL" = NULL, "0" = 0, etc..
        // then do an array_merge with array_key_intersection from the $pxParameterData to get a return array
        // that consists of any passed parameters combined w/ the default values where the passed parameters
        // are precedent over defaults.

        $a = 1;
    }



}

?>