<?php

require_once('LaConfig.php');

/**
 * 
 */
class LaVx extends LaConfig
{

    function __construct()
    {
        parent::__construct();
    }

    /**
     * 
     * 
     */
    public function vxValidateRefdata($laDb, $pxSource, $pxKey, $pxData)
    {
        $vxFx = $this->getConfigAttr('liberanalytics', 'vx', $pxSource, $pxKey);
        $vxValidateRefdata = call_user_func(array($this, $vxFx), $laDb, $pxData);
        return $vxValidateRefdata;
    }

   

    /**
     * 
     * 
     */
    public function vxPostCategory($pxLaDb, $pxCategory)
    {

        $vxPostCategories = false;
        if (!empty($pxCategory))
        {
            $postCategories = $pxLaDb->getPostCategories(NULL);
            $vxPostCategory = (in_array($pxCategory, $postCategories)) ? true : false;
        }
        else
        {
            $vxPostCategory = true;
        }

        return $vxPostCategory;
    }

}

?>