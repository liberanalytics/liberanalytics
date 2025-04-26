<?php

include('LaDataVx.php');

class LaDataPxs extends LaDataVx {

    function __construct()
    {
        parent::__construct();
    }

    /**
     * 
     */
    private function setClientPxs(string $pxCrudOperation, array $pxClientPxData, array $pxDbFxPxSpecs): array
    {
        //
        $setClientPxs = [];
        foreach ($pxClientPxData as $key => $value)
        {
            // Convert client key to db function p(x) name.
            $pxKey = $this->convertClientPxKey($key);

            // Iterate through client p(x)'s and match to the database defaults by key to determine type.
            if (array_key_exists($pxKey, $pxDbFxPxSpecs))
            {
                //
                $dbPx = $pxDbFxPxSpecs[$pxKey];
                $pxType = $dbPx[self::COL_PX_TYPE];
                $pxVal = $this->vxHandler($pxCrudOperation, $pxKey, $value, $pxType);
            }
            else
            {
                throw new \Exception("Invalid key: {$key}");
            }

            $setClientPxs[$pxKey] = $pxVal;
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
    protected function convertPxs(string $pxCrudOperation, array $pxClientPxData): array
    {
        $crudDbFxSpecs = $this->getCrudDbFxPxSpecs($pxCrudOperation);
        $crudDbFxPxDefaults = $this->getCrudDbFxPxDefaults($crudDbFxSpecs);
        $clientPxs = $this->setClientPxs($pxCrudOperation, $pxClientPxData, $crudDbFxSpecs);
        return array_merge($crudDbFxPxDefaults, $clientPxs);
    }

}

?>