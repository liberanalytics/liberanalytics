<?php

include('LaDataDb.php');
include('LaVx.php');


class LaDataPxs extends LaDataDb {

    private const COL_PX_KEY = 'px_name';
    private const COL_PX_DEFAULTVALUE = 'px_default_value';
    private const COL_PX_TYPE = 'px_type';
    private const PX_ATTR_LISTS = ['px_post_tags', 'px_post_keywords'];
    private const PX_ATTR_REFDATA = [
        'px_post_category' => 'getpostcategories'
    ];
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
    private function setClientPxs(array $pxClientPxData)
    {
        $laVx = new LaVx();
        //
        $this->clientPxs = [];
        foreach ($pxClientPxData as $pxKey => $pxValue)
        {
            // Convert client key to db function p(x) name.
            $key = $this->convertClientPxKey($pxKey);

            // Iterate through client p(x)'s and match to the database defaults by key to vx.
            if (array_key_exists($key, $this->dbPxs))
            {
                //
                if(array_key_exists($key, self::PX_ATTR_REFDATA))
                {
                    
                }
                // 
                $dbPx = $this->dbPxs[$key];
                $pxValue = $laVx->sanitize($pxValue, $this->dbPxs[$key][self::COL_PX_TYPE]);

                if (!$laVx->validate($pxValue, $this->dbPxs[$key][self::COL_PX_TYPE]))
                {
                    throw new Exception("");
                }

            }
            else
            {
                throw new Exception("Invalid key: {$pxKey}");
            }

            $this->clientPxs[$key] = $pxVal;
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
    protected function convertPxs(array $pxDbPxData, array $pxClientPxData): array
    {
        //
        $this->setDbPxs($pxDbPxData);
        $this->setClientPxs($pxClientPxData);
        return array_merge($this->dbPxs, $this->clientPxs);
    }

}

?>