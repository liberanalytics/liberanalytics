<?php

include('LaDataDb.php');


class LaDataParams extends LaDataDb {

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

         px_name
        ,px_default_value
        
        FROM meta.fx_get_fx_pxs";

        $dbPxDefaults = $this->fetchData($qry, [$pxSchema, $pxFunction], self::RESULTSET_ROWS, self::FETCHSTYLE_ASSOC);

        $a = 1;

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

    }



}

?>