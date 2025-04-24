<?php

require_once('LaConfig.php');

/**
 * 
 */
class LaVx
{

    private LaConfig $laConfig;

    function __construct()
    {
        $this->laConfig = new LaConfig();
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