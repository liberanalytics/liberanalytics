<?php

require_once('LaDataCrud.php');

/**
 * 
 */
class LaDataVx extends LaDataCrud
{

    private const PX_ATTR_LISTS = ['px_post_tags', 'px_post_keywords'];
    private const PX_REFDATA_FXS = [
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
    private function getSzVxFxHandler(string $pxType, string $fxType, string $pxKey = ''): string
    {
        $mapSzVxFxHandler = $this->getSzVxFxMap($pxType);
        $getSzVxFxHandler = $mapSzVxFxHandler[$pxType][$fxType];
        return $getSzVxFxHandler;
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
        $pxType = (array_key_exists($pxKey, self::PX_REFDATA_FXS)) ? 'refdata' : $pxType;

        //
        switch ($pxType)
        {
            case 'refdata':
                $vxHandler = $this->processRefdata($pxCrudOperation, $pxKey, $pxValue);
                break;
            case '_text':
                $vxHandler = $this->processList($pxCrudOperation, $pxKey, $pxValue);
                break;
            case 'text':
            case 'int4':
                $vxHandler = $this->processScalar($pxCrudOperation, $pxKey, $pxValue, $pxType);
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
    protected function processScalar(string $pxCrudOperation, string $pxKey, mixed $pxValue, string $pxType)
    {
        $vxScalarSzFx = $this->getSzVxFxHandler($pxType, self::SZ_FX);
        $vxScalarVxFx = $this->getSzVxFxHandler($pxType, self::VX_FX);
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
    protected function processRefdata(string $pxCrudOperation, $pxKey, $pxValue)
    {
        $vxRefdataVxFx = self::PX_REFDATA_FXS[$pxKey];
    }

    /**
     * 
     */
    protected function processList(string $pxCrudOperation)
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