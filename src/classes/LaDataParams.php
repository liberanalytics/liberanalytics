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

    protected function convertParams(string $pxSchema, string $pxFunction, array $pxParameterData)
    {

        // Get the default param values from the pg catalog view meta fx.

    }



}

?>