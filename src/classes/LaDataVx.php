<?php

namespace classes;

use enums\CrudOperation;
use enums\CrudType;
use enums\CrudResultsetType;
use enums\CrudFetchStyle;

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
        CrudOperation $pxCrudOperation, 
        string $pxKey, 
        mixed $pxValue, 
        string $pxType
    )
    {
        // Determine if its a refdata key.
        // $pxType = (array_key_exists($pxKey, self::PX_REFDATA_FX_OPS)) ? self::PX_REFDATA_TYPE : $pxType;
        $fxs = $this->getSzVxFxMap()[$pxType];
        $vxSz = $fxs[self::SZ_FX];         
        $vxFx = $fxs[self::VX_FX];

        //
        $pxValue = ($vxSz) ? call_user_func_array([$this, $vxSz], [$pxKey, $pxValue]) : $pxValue;
        $vxValueIsValid = call_user_func_array([$this, $vxFx], [$pxKey, $pxValue]);
        return ($vxValueIsValid) ? $pxValue : throw new Exception("{$pxKey} {$pxValue} is invalid.");

    }

    /**
     * 
     */
    private function sanitizeText($pxKey, $pxValue): string
    {
        // Remove non-printable control chars (0-31, 127) with [:cntrl] char class.
        $sanitizeText = trim($pxValue);
        $sanitizeText = strip_tags($sanitizeText);        
        $sanitizeText = preg_replace('/\s+/', ' ', $sanitizeText);
        $sanitizeText = preg_replace('/[[:cntrl:]]/', '', $sanitizeText);
        return $sanitizeText;
    }

    /**
     * 
     */
    private function sanitizeInt($pxKey, $pxValue): int
    {
        $sanitizeInt = $pxValue;
        return $sanitizeInt;
    }

    /**
     * 
     */
    private function validateText($pxKey, $pxValue): bool
    {
        $validChars = (preg_match('/^([\x20-\x7E]){2,}$/', $pxValue));
        $validType = (gettype($pxValue) === 'string');
        $validateText = ($validChars && $validType);
        return $validateText;
    }

    /**
     * 
     */
    private function validateInt($pxKey, $pxValue): bool
    {
        $validChars = (preg_match('/^\d+$/', $pxValue));
        $validType = (gettype($pxValue) === 'integer');
        $validateInt = ($validChars && $validType);
        return $validateInt;
    }

    /**
     * 
     */
    private function validateRefdata($pxKey, $pxValue): bool
    {
        $refdataCrudOperation = CrudOperation::from(self::PX_REFDATA_FX_OPS[$pxKey]);
        $refdataMember = $this->dispatchCrudOperation(
            $refdataCrudOperation, 
            CrudType::CRUDREAD, 
            CrudResultsetType::RESULTSET_ROWS, 
            CrudFetchStyle::FETCHSTYLE_ASSOC,
            [$pxKey => $pxValue]
        );
        $validateRefdata = !empty($refdataMember);
        return $validateRefdata;
    }

    /**
     * 
     */
    private function sanitizeList(string $pxKey, string $pxValue): string
    {
        // Sanitize list - remove unwanted characters.
        $sanitizePatterns = [
            '/[\x21-\x2B]/',
            '/[\x2E-\x2F]/',
            '/[\x3A-\x40]/',
            '/[\x5B-\x60]/',
            '/[\x7B-\xFF]/'
        ];
        $sanitizeListAsString = preg_replace($sanitizePatterns, '', $pxValue);

        // Sanitize list - remove extraneous spaces and commas.
        $sanitizeListAsString = preg_replace(
            [
                '/(\s){2,}/',
                '/(\s*,\s*|,+\s*)/',
                '/(,){2,}/'
            ],
            [
                ' ',
                ',',
                ','
            ],
            $sanitizeListAsString
        );
        
        // Sanitize list - trim from beginning and end extraneous spaces and commas.
        $sanitizeListAsString = trim($sanitizeListAsString);
        $sanitizeListAsString = trim($sanitizeListAsString, ',');

        // Serialize a postgresql array.
        $sanitizeListAsArray = preg_split('/,/', $sanitizeListAsString);
        $sanitizeListLastKey = array_key_last($sanitizeListAsArray);
        $sanitizeList = "{";
        foreach ($sanitizeListAsArray as $key => $value)
        {
            $sanitizeList .= '"' . $value . '"' ;
            $sanitizeList .= ($key != $sanitizeListLastKey) ? "," : "}";
        }

        return $sanitizeList;

    }

    /**
     * 
     */
    private function validateList(string $pxKey, string $pxList): bool
    {
        $validateList = TRUE;
        $pxList = ltrim($pxList, "{");
        $pxList = rtrim($pxList, "}");
        $pxListAsArray = preg_split('/,/', $pxList);
        foreach ($pxListAsArray as $key => $val)
        {
            if (!$this->validateText($pxKey, trim($val, "'")))
            {
                $validateList = FALSE;
                return $validateList;
            }
        }

        return $validateList ;
    }

}

?>
