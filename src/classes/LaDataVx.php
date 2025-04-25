<?php

require_once('LaDataCrud.php');

/**
 * 
 */
class LaDataVx extends LaDataCrud
{

    private const PX_ATTR_LISTS = ['px_post_tags', 'px_post_keywords'];
    private const PX_REFDATA_TYPE = 'refdata';
    private const PX_REFDATA_FX = 'validateRefdata';
    private const PX_REFDATA_FX_OPS = [
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
    private function getSzVxFxMap($pxKey = ''): array
    {
        // _text for postgre text arrays (TEXT[]).
        return
        [
            'text' =>   [
                self::SZ_FX =>  'sanitizeText',
                self::VX_FX =>  'validateText'
            ],
            'int4' =>   [
                self::VX_FX =>  'validateInt'
            ],
            '_text'  =>   [
                self::SZ_FX => 'sanitizeList',
                self::VX_FX =>  'validateList'
            ]
        ];
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
        $pxType = (array_key_exists($pxKey, self::PX_REFDATA_FX_OPS)) ? self::PX_REFDATA_TYPE : $pxType;
        if ($pxType != self::PX_REFDATA_TYPE)
        {
            $fxs = $this->getSzVxMap()[$pxType];
            $vxSz = $fxs[self::VX_FX]; 
            $vxFx = $fxs[self::SZ_FX];
        }
        else
        {
            $vxFx = self::PX_REFDATA_FX;
        }

        //
        $pxValue = ($vxSz) ? call_user_func([$this, $vxSz], $pxValue) : $pxValue;
    }    

    /**
     * 
     */
    protected function validateText($pxTextValue):bool
    {
        $validateText = true;
        return $validateText;
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
    protected function validateRefdata($pxKey, $pxValue): bool
    {

    }

}

?>