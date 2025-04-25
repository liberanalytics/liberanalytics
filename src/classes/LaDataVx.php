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
    private function getSzVxMap($pxKey = ''): array
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
            ],
            'refdata'   =>  [
                self::VX_FX =>  self::PX_ATTR_REFDATA[$pxKey]
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
        $pxType = (array_key_exists($pxKey, self::PX_ATTR_REFDATA)) ? 'refdata' : $pxType;

        //
        switch ($pxType)
        {
            case 'refdata':
                $vxHandler = $this->vxRefdata($pxCrudOperation, $pxKey, $pxValue);
                break;
            case '_text':
                $vxHandler = $this->vxList($pxCrudOperation, $pxKey, $pxValue);
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
    protected function vxScalar(string $pxCrudOperation, string $pxKey, mixed $pxValue, string $pxType)
    {
        $vxScalarFxs = $this->getSzVxMap()[$pxType];
        $vxScalarSzFx = $vxScalarFxs[self::SZ_FX];
        $vxScalarVxFx = $vxScalarFxs[self::VX_FX];
    }

    /**
     * 
     */
    protected function validateText($pxTextValue)
    {
        $validateText = true;
        return $validateText;
    }

    /**
     * 
     */
    protected function vxRefdata(string $pxCrudOperation, $pxKey, $pxValue)
    {
        $vxRefdataFxs = $this->getSzVxMap()[$pxType];
        $vxRefdataVxFx = $vxRefdataFxs[self::VX_FX];
    }

    /**
     * 
     */
    protected function vxList(string $pxCrudOperation)
    {
        $vxListFxs = $this->getSzVxMap()[$pxType];
        $vxListSzFx = $vxListFxs[self::SZ_FX];
        $vxListVxFx = $vxListFxs[self::VX_FX]; 
    }

    /**
     * 
     */
    protected function sanitizeText($pxTextValue)
    {

    }

}

?>