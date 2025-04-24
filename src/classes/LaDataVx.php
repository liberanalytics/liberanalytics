<?php

require_once('LaDataCrud.php');

/**
 * 
 */
class LaDataVx extends LaDataCrud
{

    protected function __construct()
    {
        parent::__construct();
    }

    /**
     * 
     */
    public function validate($pxValue, $pxVxType, $pxRefdataMembers = []): bool
    {
        return true;
    }

    /**
     * 
     */
    public function sanitize($pxValue, $pxVxType)
    {

        return $pxValue;
    }

}

?>