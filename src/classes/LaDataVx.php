<?php

require_once('LaDataCrud.php');

/**
 * 
 */
class LaDataVx extends LaDataCrud
{

    private const PX_ATTR_LISTS = ['px_post_tags', 'px_post_keywords'];
    private const PX_ATTR_REFDATA = [
        'px_post_category' => 'getpostcategories'
    ];
    private const SZ_FX = 'vxSz';
    private const VX_FX = 'vxFx';


    protected function __construct()
    {
        parent::__construct();
    }

    /**
     * 
     */
    protected function vxHandler(
        string $pxCrudOperation, 
        string $pxKey, 
        mixed $pxValue, 
        string $pxType
    )
    {
        // Determine if its a refdata key.
        $pxType = (array_key_exists($pxKey, self::PX_ATTR_REFDATA)) ? 'refdata' : $pxType;

        //
        switch ($pxType)
        {
            case 'refdata':
                $vxHandler = $this->vxRefdata($pxCrudOperation, $pxKey, $pxValue);
                break;
            case 'list':

                break;
            case 'text':
            case 'int4':
                $vxHandler = $this->vxScalar($pxCrudOperation, $pxKey, $pxValue, $pxType);
                break;
        }

        if (!$vxHandler)
        {
            throw new Exception("Invalid key: {$key}");
        }
        else
        {
            return $vxHandler;
        }
    }

    /**
     * 
     */
    protected function vxRefdata(string $pxCrudOperation, $pxKey, $pxValue)
    {

    }

    /**
     * 
     */
    protected function vxListData(string $pxCrudOperation)
    {
        
    }
    
    /**
     * 
     */
    protected function vxScalar(string $pxCrudOperation, string $pxKey, mixed $pxValue, string $pxType)
    {

        $vxFxs = match($pxType)
        {
            'text' =>   [
                'szFx'  =>  'sanitizeText',
                'vxFx'  =>  'validateText'
            ],
            'int4' =>   [
                'vxFx'  =>  'validateInt'
            ]
        };


        
    }

    /**
     * 
     */
    protected function sanitizeText($pxTextValue)
    {

    }

    /**
     * 
     */
    protected function validateText($pxTextValue)
    {
        $validateText = true;

        return $validateText;
    }


}

?>