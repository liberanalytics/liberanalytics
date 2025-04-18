<?php

include('LaDataDb.php');


class LaDataParams extends LaDataDb {

    private const PX_KEY = 'px_name';
    private const PX_DEFAULTVALUE = 'px_default_value';

    function __construct()
    {
        parent::__construct();
    }

    /**
     * 
     */
    private function convertDatabaseParamKeys()
    {

    }

    /**
     * 
     */
    protected function convertParams(string $pxSchema, string $pxFunction, array $pxParameterData)
    {

        $qry = 
        "SELECT

        " .     self::PX_KEY .
        ",
        " .   self::PX_DEFAULTVALUE . "
        
        FROM meta.fx_get_fx_pxs";

        $dbPxDefaultsAsRows = $this->fetchData($qry, [$pxSchema, $pxFunction], self::RESULTSET_ROWS, self::FETCHSTYLE_ASSOC);

        $dbPxDefaultsAsArray = [];
        foreach ($dbPxDefaultsAsRows as $dbPxDefaultAsRow)
        {
            foreach($dbPxDefaultAsRow as $key => $value)
            {
                $dbPxDefaultsAsArray[$dbPxDefaultAsRow[self::PX_KEY]] = $dbPxDefaultAsRow[self::PX_DEFAULTVALUE];
            }
        }


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