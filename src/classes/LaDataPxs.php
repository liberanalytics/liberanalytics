<?php

include('LaDataDb.php');
include('LaVx.php');


class LaDataPxs extends LaDataDb {

    private CONST COL_PX_KEY = 'px_name';
    private CONST COL_PX_DEFAULTVALUE = 'px_default_value';
    private CONST PX_DB_DEFAULTS = 'px_db_defaults';
    private CONST PX_CLIENT = 'px_client';
    private array $dbPxs;
    private array $clientPxs;

    function __construct()
    {
        parent::__construct();
    }

    /**
     * 
     */
    private function setDbPxs($pxDbPxData): array
    {
        //
        $setDbPxDefaults = [];
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
                $setDbPxDefaults[$pxNameKey][$key] = $value;
            }
        }       
        return $setDbPxDefaults;
    }

    /**
     * 
     */
    private function setClientPxs(array $pxClientPxData)
    {
        //
        $setClientPxs = [];
        foreach ($pxClientPxData as $pxKey => $pxVal)
        {
            $key = $this->convertClientPxKey($pxKey);
            $val = $pxVal; // sanitize here
            $setClientPxs[$key] = $pxVal;
        };     
        return $setClientPxs;
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
    protected function convertPxs(array $pxDbPxData, array $pxClientPxData): array
    {
        //
        $this->dbPxs = $this->setDbPxs($pxDbPxData);
        $this->clientPxs = $this->setClientPxs($pxClientPxData);




        return array_merge($convertDefaultPxs, $convertClientPxs);
    }

}

?>