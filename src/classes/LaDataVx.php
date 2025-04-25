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
    private function getSzVxFxMap(): array
    {
        // _text for postgre text arrays (TEXT[]).
        return
        [
            'text' =>   [
                self::SZ_FX =>  'sanitizeText',
                self::VX_FX =>  'validateText'
            ],
            'int4' =>   [
                self::SZ_FX =>  'sanitizeInt',
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
            $fxs = $this->getSzVxFxMap()[$pxType];
            $vxSz = $fxs[self::SZ_FX]; 
            $vxFx = $fxs[self::VX_FX];
        }
        else
        {
            $vxSz = null;
            $vxFx = self::PX_REFDATA_FX;
        }

        //
        $pxValue = ($vxSz) ? call_user_func_array([$this, $vxSz], [$pxKey, $pxValue]) : $pxValue;
        $vxValueIsValid = call_user_func_array([$this, $vxFx], [$pxKey, $pxValue]);
        return ($vxValueIsValid) ? $pxValue : throw new Exception("{$pxKey} {$pxValue} is invalid.");

    }

    /**
     * 
     */
    protected function sanitizeText($pxKey, $pxValue): string
    {
        $sanitizeText = trim($pxValue);
        $sanitizeText = strip_tags($sanitizeText);        
        $sanitizeText = preg_replace('/\s+/', ' ', $sanitizeText);
        $sanitizeText = preg_replace('/[[:cntrl:]]/', '', $sanitizeText);
        return $sanitizeText;
    }

    /**
     * 
     */
    protected function sanitizeInt($pxKey, $pxValue): int
    {
        $sanitizeInt = $pxValue;
        return $sanitizeInt;
    }

    /**
     * 
     */
    protected function validateText($pxKey, $pxValue): bool
    {
        $validChars = (preg_match('/^([\x20-\x7E]){3,}$/', $pxValue));
        $validType = (gettype($pxValue) === 'string');
        $validateText = ($validChars && $validType);
        return $validateText;
    }

    /**
     * 
     */
    protected function validateInt($pxKey, $pxValue): bool
    {
        $validChars = (preg_match('/^\d+$/', $pxValue));
        $validType = (gettype($pxValue) === 'integer');
        $validateInt = ($validChars && $validType);
        return $validateInt;
    }

    /**
     * 
     */
    protected function validateRefdata($pxKey, $pxValue): bool
    {
        $refdataCrudOperation = PX_REFDATA_FX_OPS[$pxKey];
    }

}

?>